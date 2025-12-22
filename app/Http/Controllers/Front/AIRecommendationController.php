<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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


        // Get categories from recent orders
        $orderedCategoryIds = [];
        foreach ($recentOrders as $order) {
            if ($order->orderitems) {
                foreach ($order->orderitems as $item) {
                    if ($item->products && $item->products->categories) {
                        $orderedCategoryIds[] = $item->products->categories->id;
                    }
                }
            }
        }

        // Get popular products from the same categories
        $recommendations = Product::with('category')
            ->whereHas('category', function($query) use ($orderedCategoryIds) {
                if (!empty($orderedCategoryIds)) {
                    $query->whereIn('id', array_unique($orderedCategoryIds));
                }
            })
            ->where('is_available', true)
            ->inRandomOrder()
            ->take($limit)
            ->get();

        // If no recommendations from ordered categories, get popular products overall
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

        // Get recommendations
        $recommendationsResponse = $this->getRecommendations($request);
        if ($recommendationsResponse->getStatusCode() !== 200) {
            return $recommendationsResponse;
        }
        $recommendations = $recommendationsResponse->getData()->recommendations;

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

        if (empty($customerMessage)) {
            // Default greeting with recommendations
            if ($locale == 'ar') {
                return "مرحباً {$customerName}! بناءً على طلباتك السابقة، أقترح عليك هذه المنتجات التي قد تعجبك:";
            } else {
                return "Hello {$customerName}! Based on your previous orders, I recommend these products you might like:";
            }
        } else {
            // Response based on customer message
            if ($locale == 'ar') {
                return "شكراً لرسالتك، {$customerName}! بناءً على طلباتك واهتماماتك، أقترح عليك هذه المنتجات:";
            } else {
                return "Thank you for your message, {$customerName}! Based on your orders and interests, I recommend these products:";
            }
        }
    }
}