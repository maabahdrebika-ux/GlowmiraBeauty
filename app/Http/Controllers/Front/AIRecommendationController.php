<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AIRecommendationController extends Controller
{
     public function __construct()
    {
        // Only authenticated customers can add reviews
        $this->middleware('auth.customer');
    }
    /**
     * Get product recommendations based on customer's order history
     */
    public function getRecommendations(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not authenticated'
            ], 401);
        }

        $limit = $request->input('limit', 4);

        // Get customer's recent orders
        $recentOrders = Order::where('customer_id', $customer->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get products from recent orders
        $orderedProductIds = [];
        $orderedCategoryIds = [];
        foreach ($recentOrders as $order) {
            if ($order->orderitems) {
                foreach ($order->orderitems as $item) {
                    if ($item->products) {
                        $orderedProductIds[] = $item->products->id;
                        if ($item->products->category) {
                            $orderedCategoryIds[] = $item->products->category->id;
                        }
                    }
                }
            }
        }

        $recommendations = collect();

        // 1. Collaborative filtering based on reviews (users who reviewed similar products)
        if (!empty($orderedProductIds)) {
            $similarUsers = DB::table('reviews')
                ->whereIn('products_id', $orderedProductIds)
                ->where('rating', '>=', 4) // High ratings
                ->select('customer_id')
                ->distinct()
                ->pluck('customer_id');

            if ($similarUsers->isNotEmpty()) {
                $recommendedProductIds = DB::table('reviews')
                    ->whereIn('customer_id', $similarUsers)
                    ->where('rating', '>=', 4)
                    ->whereNotIn('products_id', $orderedProductIds)
                    ->select('products_id', DB::raw('COUNT(*) as count'))
                    ->groupBy('products_id')
                    ->orderBy('count', 'desc')
                    ->limit($limit * 2)
                    ->pluck('products_id');

                $recs = Product::whereIn('id', $recommendedProductIds)
                    ->where('is_available', true)
                    ->get();
                $recommendations = $recommendations->merge($recs);
            }
        }

        // 2. Based on wishlist
        $wishlistItems = Wishlist::where('customer_id', $customer->id)
            ->with('product.category')
            ->get();
        $wishlistCategoryIds = $wishlistItems->map(function($item) {
            return $item->product->category->id ?? null;
        })->filter()->unique()->values();

        if ($wishlistCategoryIds->isNotEmpty()) {
            $recs = Product::with('category')
                ->whereHas('category', function($query) use ($wishlistCategoryIds) {
                    $query->whereIn('id', $wishlistCategoryIds);
                })
                ->where('is_available', true)
                ->whereNotIn('id', $orderedProductIds)
                ->inRandomOrder()
                ->take($limit)
                ->get();
            $recommendations = $recommendations->merge($recs);
        }

        // 3. Based on ordered categories
        if (!empty($orderedCategoryIds)) {
            $recs = Product::with('category')
                ->whereHas('category', function($query) use ($orderedCategoryIds) {
                    $query->whereIn('id', array_unique($orderedCategoryIds));
                })
                ->where('is_available', true)
                ->whereNotIn('id', $orderedProductIds)
                ->inRandomOrder()
                ->take($limit)
                ->get();
            $recommendations = $recommendations->merge($recs);
        }

        // Remove duplicates and limit
        $recommendations = $recommendations->unique('id')->take($limit);

        // If still empty, get popular products overall
        if ($recommendations->isEmpty()) {
            $recommendations = Product::with('category')
                ->where('is_available', true)
                ->inRandomOrder()
                ->take($limit)
                ->get();
        }


        return response()->json([
            'success' => true,
            'recommendations' => $recommendations->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->display_name,
                    'name_ar' => $product->name,
                    'name_en' => $product->namee,
                    'price' => $product->price,
                    'discounted_price' => $product->discounted_price,
                    'on_sale' => $product->on_sale,
                    'image' => $product->image ? asset('images/product/' . $product->image) : asset('public/images/no-image.png'),
                    'category' => $product->category ? [
                        'id' => $product->category->id,
                        'name' => app()->getLocale() == 'ar' ? $product->category->name : $product->category->englishname,
                    ] : null,
                    'url' => route('product/info', $product->id)
                ];
            })
        ]);
    }

    /**
     * Get AI chat response with product recommendations
     */
    public function getAIChatResponse(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not authenticated'
            ], 401);
        }

        $message = $request->input('message', '');

        $recommendations = [];

        // If message contains keywords, perform hybrid search
        if (!empty($message)) {
            $products = Product::with('category')
                ->select('products.*')
                ->selectRaw('MATCH(products.name, products.namee, products.description_ar, products.description_en) AGAINST(? IN NATURAL LANGUAGE MODE) as relevance_score', [$message])
                ->selectRaw('CASE WHEN products.name LIKE ? THEN 10 WHEN products.namee LIKE ? THEN 10 WHEN products.brandname_ar LIKE ? THEN 8 WHEN products.brandname_en LIKE ? THEN 8 WHEN products.barcode LIKE ? THEN 5 ELSE 0 END as keyword_score', ['%' . $message . '%', '%' . $message . '%', '%' . $message . '%', '%' . $message . '%', '%' . $message . '%'])
                ->leftJoin('categories', 'products.categories_id', '=', 'categories.id')
                ->where(function($query) use ($message) {
                    $query->whereRaw('MATCH(products.name, products.namee, products.description_ar, products.description_en) AGAINST(? IN NATURAL LANGUAGE MODE)', [$message])
                          ->orWhere('products.name', 'LIKE', '%' . $message . '%')
                          ->orWhere('products.namee', 'LIKE', '%' . $message . '%')
                          ->orWhere('products.brandname_ar', 'LIKE', '%' . $message . '%')
                          ->orWhere('products.brandname_en', 'LIKE', '%' . $message . '%')
                          ->orWhere('products.barcode', 'LIKE', '%' . $message . '%')
                          ->orWhere('categories.name', 'LIKE', '%' . $message . '%')
                          ->orWhere('categories.englishname', 'LIKE', '%' . $message . '%');
                })
                ->where('products.is_available', true)
                ->orderByRaw('(relevance_score + keyword_score) DESC')
                ->take(4)
                ->get();

            if ($products->isNotEmpty()) {
                $recommendations = $products->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->display_name,
                        'name_ar' => $product->name,
                        'name_en' => $product->namee,
                        'price' => $product->price,
                        'discounted_price' => $product->discounted_price,
                        'on_sale' => $product->on_sale,
                        'image' => $product->image ? asset('images/product/' . $product->image) : asset('public/images/no-image.png'),
                        'category' => $product->category ? [
                            'id' => $product->category->id,
                            'name' => app()->getLocale() == 'ar' ? $product->category->name : $product->category->englishname,
                        ] : null,
                        'url' => route('product/info', $product->id)
                    ];
                })->toArray();
            }
        }

        // If no search results, get personalized recommendations
        if (empty($recommendations)) {
            $recommendationsResponse = $this->getRecommendations($request);
            if ($recommendationsResponse->getStatusCode() !== 200) {
                return $recommendationsResponse;
            }
            $recommendations = $recommendationsResponse->getData()->recommendations;
        }

        // Generate AI response
        $responseMessage = $this->generateAIResponse($message, $recommendations);

        return response()->json([
            'success' => true,
            'message' => $responseMessage,
            'recommendations' => $recommendations
        ]);
    }

    /**
     * Generate AI response based on customer message and recommendations
     */
    private function generateAIResponse($customerMessage, $recommendations)
    {
        $locale = app()->getLocale();
        $customerName = Auth::guard('customer')->user()->name;

        if (empty($recommendations)) {
            if ($locale == 'ar') {
                return "عذراً {$customerName}، لا يوجد منتجات مشابهة لاهتماماتك حالياً.";
            } else {
                return "Sorry {$customerName}, there are no similar products to your interests at the moment.";
            }
        }

        // Build product list
        $productNames = array_map(function($rec) {
            return $rec['name'];
        }, $recommendations);
        $productList = implode(', ', $productNames);

        if (empty($customerMessage)) {
            // Default greeting with recommendations
            if ($locale == 'ar') {
                return "مرحباً {$customerName}! بناءً على طلباتك السابقة، أقترح عليك هذه المنتجات: {$productList}";
            } else {
                return "Hello {$customerName}! Based on your previous orders, I recommend these products: {$productList}";
            }
        } else {
            // Response based on customer message
            if ($locale == 'ar') {
                return "شكراً لرسالتك، {$customerName}! وجدت هذه المنتجات المناسبة: {$productList}";
            } else {
                return "Thank you for your message, {$customerName}! I found these suitable products: {$productList}";
            }
        }
    }
}