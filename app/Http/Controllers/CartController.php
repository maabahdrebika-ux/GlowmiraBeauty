<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Discount;
use App\Models\products;
use Illuminate\Http\Request;

class CartController extends Controller
{
     public function __construct()
    {
        // Only authenticated customers can add reviews
        $this->middleware('auth.customer');
    }
    public function store(Request $request)
    {
        try {
            if ($request->method() !== 'POST') {
                return response()->json(['success' => false, 'message' => __('products.invalid_request_method')], 400);
            }

            // Check if user is authenticated
            if (!auth('customer')->check()) {
                return response()->json(['success' => false, 'message' => __('app.must_login_to_order')], 401);
            }

            $request->validate([
                'product_id' => 'required|exists:products,id',
                'color' => 'nullable|string',
                'size' => 'nullable|string',
                'quantity' => 'required|integer|min:1',
                'discount_code' => 'nullable|string',
            ]);

            $cart = session()->get('cart', []);
            $product = products::find($request->product_id);

            // More flexible stock checking - try multiple approaches
            $availableStock = 0;
            $stockFound = false;
            
            if ($request->size && $request->color) {
                // Check for exact match with both size and color
                $sizeId = $product->sizes()->where('name', $request->size)->value('id');
                $colorId = $product->coolors()->where('name', $request->color)->value('id');
                
                if ($sizeId && $colorId) {
                    $stockRecord = $product->stocks()
                        ->where('sizes_id', $sizeId)
                        ->where('coolors_id', $colorId)
                        ->first();
                    
                    if ($stockRecord) {
                        $availableStock = (int) $stockRecord->quantty;
                        $stockFound = true;
                    }
                }
            }
            
            // If no exact match found, try size only
            if (!$stockFound && $request->size) {
                $sizeId = $product->sizes()->where('name', $request->size)->value('id');
                
                if ($sizeId) {
                    $stockRecord = $product->stocks()
                        ->where('sizes_id', $sizeId)
                        ->whereNull('coolors_id')
                        ->first();
                    
                    if ($stockRecord) {
                        $availableStock = (int) $stockRecord->quantty;
                        $stockFound = true;
                    }
                }
            }
            
            // If still no match, try color only
            if (!$stockFound && $request->color) {
                $colorId = $product->coolors()->where('name', $request->color)->value('id');
                
                if ($colorId) {
                    $stockRecord = $product->stocks()
                        ->where('coolors_id', $colorId)
                        ->whereNull('sizes_id')
                        ->first();
                    
                    if ($stockRecord) {
                        $availableStock = (int) $stockRecord->quantty;
                        $stockFound = true;
                    }
                }
            }
            
            // If still no match, try any stock record for this product (fallback)
            if (!$stockFound) {
                $stockRecord = $product->stocks()
                    ->whereNull('sizes_id')
                    ->whereNull('coolors_id')
                    ->first();
                
                if ($stockRecord) {
                    $availableStock = (int) $stockRecord->quantty;
                    $stockFound = true;
                }
            }
            
            // If still no specific stock found, use total stock from all records
            if (!$stockFound) {
                $totalStock = $product->stocks()->sum('quantty');
                $availableStock = (int) $totalStock;
                $stockFound = true;
            }
            
            // If no stock records exist at all, allow adding (assuming unlimited stock)
            if (!$stockFound) {
                $availableStock = 999999; // Unlimited stock for products without stock records
            }
            
            // Check if enough stock is available (only if we have a valid stock number)
            if ($availableStock > 0 && $availableStock < $request->quantity) {
                return response()->json([
                    'success' => false, 
                    'message' => __('products.insufficient_stock') . ' (Available: ' . $availableStock . ')'
                ], 400);
            }

            $productDiscount = Discount::where('products_id', $request->product_id)->first();
            
            // Enhanced check for existing item in cart
            $existingItemKey = null;
            $existingItemFound = false;
            
            foreach ($cart as $key => $item) {
                // Check if product ID matches
                if ($item['product_id'] == $request->product_id) {
                    // Check if color and size match (handling null values properly)
                    $itemColorMatches = ($item['color'] === $request->color) || 
                                       ($item['color'] == null && $request->color == null);
                    $itemSizeMatches = ($item['size'] === $request->size) || 
                                      ($item['size'] == null && $request->size == null);
                    
                    if ($itemColorMatches && $itemSizeMatches) {
                        $existingItemKey = $key;
                        $existingItemFound = true;
                        
                        // Check if adding this quantity would exceed available stock
                        $newQuantity = $item['quantity'] + $request->quantity;
                        
                        if ($availableStock > 0 && $newQuantity > $availableStock) {
                            return response()->json([
                                'success' => false, 
                                'message' => __('products.exceeds_stock_limit') . 
                                            ' (Current: ' . $item['quantity'] . 
                                            ', Available: ' . $availableStock . ')'
                            ], 400);
                        }
                        
                        break;
                    }
                }
            }

            if ($existingItemFound) {
                // Update existing item quantity
                $cart[$existingItemKey]['quantity'] += $request->quantity;
                
                // Update the total price for this item
                $cart[$existingItemKey]['total_price'] = $cart[$existingItemKey]['quantity'] * 
                                                       ($cart[$existingItemKey]['discounted_price'] ?? $cart[$existingItemKey]['price']);
                
                $actionMessage = __('products.quantity_updated_in_cart');
            } else {
                // Add new item to cart
                $discountedPrice = $productDiscount ? ($product->price - ($product->price * ($productDiscount->percentage / 100))) : $product->price;
                
                $cart[] = [
                    'product_id'      => $request->product_id,
                    'product_name'    => $product->name,
                    'product_image'   => $product->image,
                    'product_code'    => $product->barcode,
                    'color'           => $request->color,
                    'coolors_id'      => $request->color ? $product->coolors()->where('name', $request->color)->value('id') : null,
                    'size'            => $request->size,
                    'sizes_id'        => $request->size ? $product->sizes()->where('name', $request->size)->value('id') : null,
                    'quantity'        => $request->quantity,
                    'price'           => $product->price,
                    'discount'        => $productDiscount ? $productDiscount->percentage : 0,
                    'discounted_price'=> $discountedPrice,
                    'total_price'     => $request->quantity * $discountedPrice,
                    'added_at'        => now()->toDateTimeString()
                ];

                $actionMessage = __('products.added_to_cart');
            }

            session()->put('cart', $cart);
  
            $cartTotal = array_reduce($cart, function ($total, $item) {
                return $total + (($item['discounted_price'] ?? 0) * $item['quantity']);
            }, 0);

            return response()->json([
                'success' => true,
                'message' => $actionMessage,
                'cart' => $cart,
                'total' => $cartTotal,
                'cart_count' => count($cart)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('products.error_occurred') . ': ' . $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        $subtotal = 0;
        $discount = 0;

        foreach ($cart as &$item) {
            $itemSubtotal = ($item['price'] ?? 0) * $item['quantity'];
            $itemDiscount = !empty($item['discount']) ? $itemSubtotal * ($item['discount'] / 100) : 0;

            $item['discount_percentage'] = !empty($item['discount']) ? $item['discount'] : 0; // Pass discount percentage
            $item['discounted_price'] = $itemSubtotal - $itemDiscount;

            $subtotal += $itemSubtotal;
            $discount += $itemDiscount;
        }

        $total = $subtotal - $discount;

        $addresses=Address::get();
        return view('front.cart', compact('cart', 'subtotal', 'discount', 'total','addresses'));
    }

    public function getCartItems()
    {
        $cart = session()->get('cart', []);
        return response()->json(['success' => true, 'cart' => $cart]);
    }

    /**
     * Debug stock information for a product (for troubleshooting)
     */
    public function debugStock(Request $request)
    {
        try {
            $productId = $request->input('product_id');
            $product = products::find($productId);
            
            $stocks = $product->stocks()->get();
            $totalStock = $product->stocks()->sum('quantty');
            
            return response()->json([
                'success' => true,
                'product_id' => $productId,
                'product_name' => $product->name,
                'stocks' => $stocks,
                'total_stock' => $totalStock,
                'stock_count' => $stocks->count()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error debugging stock: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if a specific item exists in cart
     */
    public function checkCartItem(Request $request)
    {
        try {
            $productId = $request->input('product_id');
            $color = $request->input('color');
            $size = $request->input('size');
            
            $cart = session()->get('cart', []);
            $existingItem = null;
            
            foreach ($cart as $key => $item) {
                if ($item['product_id'] == $productId) {
                    // Check if color and size match (handling null values properly)
                    $itemColorMatches = ($item['color'] === $color) || 
                                       ($item['color'] == null && $color == null);
                    $itemSizeMatches = ($item['size'] === $size) || 
                                      ($item['size'] == null && $size == null);
                    
                    if ($itemColorMatches && $itemSizeMatches) {
                        $existingItem = $item;
                        break;
                    }
                }
            }
            
            return response()->json([
                'success' => true,
                'exists' => $existingItem !== null,
                'item' => $existingItem
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking cart item: ' . $e->getMessage()
            ], 500);
        }
    }

    public function clear(Request $request)
    {
        $orderStatus = $request->input('order_status');
        
        // Check if this is a direct cart clear request (no order status required)
        if ($request->header('Accept') === 'application/json') {
            // API/JSON request - clear cart and return JSON response
            session()->forget('cart');
            return response()->json([
                'success' => true,
                'message' => __('products.cart_cleared'),
                'cart_count' => 0
            ]);
        }
        
        // Regular web request
        if (in_array($orderStatus, ['completed', 'canceled', 'cleared'])) {
            // Order-based clearing (for completed/canceled orders) or manual clearing
            session()->forget('cart');
            return redirect()->back()->with('success', __('products.cart_cleared'));
        } elseif (!$orderStatus) {
            // Direct cart clearing without order status
            session()->forget('cart');
            return redirect()->back()->with('success', __('products.cart_cleared'));
        }

        return redirect()->back()->with('error', __('products.cart_not_cleared'));
    }

    public function remove($product_id)
    {
        $cart = session()->get('cart', []);
        $cart = array_filter($cart, fn($item) => $item['product_id'] != $product_id);
        session()->put('cart', $cart);

        return response()->json(['success' => true, 'message' => __('products.item_removed')]);
    }

    public function getCartItemCount()
    {
        $cart = session()->get('cart', []);
        $count = array_reduce($cart, fn($total, $item) => $total + $item['quantity'], 0);

        return response()->json(['success' => true, 'count' => $count]);
    }

    public function updateQuantity(Request $request, $product_id)
    {
        $cart = session()->get('cart', []);
        $newQuantity = $request->input('quantity');

        foreach ($cart as &$item) {
            if ($item['product_id'] == $product_id) {
                $item['quantity'] = $newQuantity;
                $item['discounted_price'] = $item['price'] - ($item['price'] * ($item['discount'] / 100));
                break;
            }
        }

        session()->put('cart', $cart);

        $grandTotal = array_reduce($cart, function ($total, $item) {
            return $total + ($item['discounted_price'] * $item['quantity']);
        }, 0);

        return response()->json([
            'success' => true,
            'totalPrice' => $cart[array_search($product_id, array_column($cart, 'product_id'))]['discounted_price'] * $newQuantity,
            'grandTotal' => $grandTotal,
        ]);
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', __('products.cart_empty'));
        }

        $subtotal = 0;
        $discount = 0;

        foreach ($cart as &$item) {
            $itemSubtotal = ($item['price'] ?? 0) * $item['quantity'];
            $itemDiscount = !empty($item['discount']) ? $itemSubtotal * ($item['discount'] / 100) : 0;

            $item['discount_percentage'] = !empty($item['discount']) ? $item['discount'] : 0;
            $item['discounted_price'] = $itemSubtotal - $itemDiscount;

            $subtotal += $itemSubtotal;
            $discount += $itemDiscount;
        }

        $total = $subtotal - $discount;
        $addresses = Address::all(); // Get all available addresses/cities for customer to choose from

        return view('front.checkout', compact('cart', 'subtotal', 'discount', 'total', 'addresses'));
    }
}
