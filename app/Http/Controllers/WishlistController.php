<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
     public function __construct()
    {
        // Only authenticated customers can add reviews
        $this->middleware('auth.customer');
    }
    /**
     * Toggle product in user's wishlist
     */
    public function toggle(Request $request)
    {
        try {
            // Check if user is authenticated as customer
            if (!Auth::guard('customer')->check()) {
                return response()->json([
                    'success' => false,
                    'message' => __('products.login_required_wishlist'),
                    'login_required' => true
                ], 401);
            }

            $request->validate([
                'product_id' => 'required|integer|exists:products,id',
            ]);

            $customerId = Auth::guard('customer')->id();
            $productId = $request->product_id;

            // Toggle the product in wishlist
            $result = Wishlist::toggle($customerId, $productId);

            // Get updated wishlist count
            $wishlistCount = Wishlist::getCount($customerId);

            return response()->json([
                'success' => true,
                'in_wishlist' => $result['in_wishlist'],
                'action' => $result['action'],
                'message' => $result['message'],
                'wishlist_count' => $wishlistCount
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('products.error_processing_wishlist'),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get wishlist count for authenticated user
     */
    public function getCount()
    {
        try {
            if (!Auth::guard('customer')->check()) {
                return response()->json([
                    'success' => false,
                    'count' => 0
                ]);
            }

            $customerId = Auth::guard('customer')->id();
            $count = Wishlist::getCount($customerId);

            return response()->json([
                'success' => true,
                'count' => $count
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'count' => 0,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if product is in user's wishlist
     */
    public function checkProduct(Request $request)
    {
        try {
            if (!Auth::guard('customer')->check()) {
                return response()->json([
                    'success' => true,
                    'in_wishlist' => false
                ]);
            }

            $request->validate([
                'product_id' => 'required|integer|exists:products,id',
            ]);

            $customerId = Auth::guard('customer')->id();
            $productId = $request->product_id;

            $inWishlist = Wishlist::isInWishlist($customerId, $productId);

            return response()->json([
                'success' => true,
                'in_wishlist' => $inWishlist
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'in_wishlist' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's wishlist items
     */
    public function index()
    {
        try {
            if (!Auth::guard('customer')->check()) {
                return redirect()->route('customer.login')
                    ->with('error', __('products.login_required_wishlist'));
            }

            $customerId = Auth::guard('customer')->id();
            $wishlistItems = Wishlist::with('product.category')->where('customer_id', $customerId)->get();

            return view('front.customer.wishlist', compact('wishlistItems'));

        } catch (\Exception $e) {
            return redirect()->route('customer.dashboard')
                ->with('error', __('products.error_loading_wishlist'));
        }
    }

    /**
     * Remove item from wishlist
     */
    public function remove(Request $request)
    {
        try {
            if (!Auth::guard('customer')->check()) {
                return response()->json([
                    'success' => false,
                    'message' => __('products.login_required_wishlist'),
                    'login_required' => true
                ], 401);
            }

            $request->validate([
                'product_id' => 'required|integer|exists:products,id',
            ]);

            $customerId = Auth::guard('customer')->id();
            $productId = $request->product_id;

            // Remove from wishlist
            Wishlist::where('customer_id', $customerId)
                    ->where('product_id', $productId)
                    ->delete();

            // Get updated wishlist count
            $wishlistCount = Wishlist::getCount($customerId);

            return response()->json([
                'success' => true,
                'message' => __('products.removed_from_wishlist'),
                'wishlist_count' => $wishlistCount
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('products.error_removing_wishlist'),
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
