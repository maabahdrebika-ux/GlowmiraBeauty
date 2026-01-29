<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\ReviewReply;
use App\Models\products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class ReviewController extends Controller
{
    public function __construct()
    {
        // Only authenticated customers can add reviews
        $this->middleware('auth.customer');
    }

    /**
     * Store a newly created review
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ], [
            'product_id.required' => __('reviews.product_id_required'),
            'product_id.exists' => __('reviews.product_not_found'),
            'rating.required' => __('reviews.rating_required'),
            'rating.integer' => __('reviews.rating_must_be_integer'),
            'rating.min' => __('reviews.rating_min'),
            'rating.max' => __('reviews.rating_max'),
            'comment.required' => __('reviews.comment_required'),
            'comment.min' => __('reviews.comment_min_length'),
            'comment.max' => __('reviews.comment_max_length'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            $customer = Auth::guard('customer')->user();
            $product = products::findOrFail($request->product_id);

            // Check if customer already reviewed this product
            $existingReview = Review::where('customer_id', $customer->id)
                                  ->where('products_id', $product->id)
                                  ->first();

            if ($existingReview) {
                Alert::warning(__('reviews.already_reviewed'));
                return redirect()->back();
            }

            // Create new review
            $review = new Review();
            $review->customer_id = $customer->id;
            $review->products_id = $product->id;
            $review->rating = $request->rating;
            $review->comment = $request->comment;
            $review->is_approved = false; // Reviews need admin approval
            $review->is_verified_purchase = $this->checkVerifiedPurchase($customer->id, $product->id);
            $review->save();

            Alert::success(__('reviews.review_submitted_success'));
            return redirect()->back();

        } catch (\Exception $e) {
            Alert::error(__('reviews.error_submitting_review'), $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Update an existing review
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ], [
            'rating.required' => __('reviews.rating_required'),
            'rating.integer' => __('reviews.rating_must_be_integer'),
            'rating.min' => __('reviews.rating_min'),
            'rating.max' => __('reviews.rating_max'),
            'comment.required' => __('reviews.comment_required'),
            'comment.min' => __('reviews.comment_min_length'),
            'comment.max' => __('reviews.comment_max_length'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            $customer = Auth::guard('customer')->user();
            $review = Review::where('id', $id)
                          ->where('customer_id', $customer->id)
                          ->firstOrFail();

            $review->rating = $request->rating;
            $review->comment = $request->comment;
            $review->is_approved = false; // Reset approval status on edit
            $review->save();

            Alert::success(__('reviews.review_updated_success'));
            return redirect()->back();

        } catch (\Exception $e) {
            Alert::error(__('reviews.error_updating_review'));
            return redirect()->back()->withInput();
        }
    }

    /**
     * Delete a review
     */
    public function destroy($id)
    {
        try {
            $customer = Auth::guard('customer')->user();
            $review = Review::where('id', $id)
                          ->where('customer_id', $customer->id)
                          ->firstOrFail();

            $review->delete();

            Alert::success(__('reviews.review_deleted_success'));
            return redirect()->back();

        } catch (\Exception $e) {
            Alert::error(__('reviews.error_deleting_review'));
            return redirect()->back();
        }
    }

    /**
     * Get reviews for a product (AJAX)
     */
    public function getProductReviews(Request $request)
    {
        try {
            $productId = $request->product_id;
            
            if (!$productId || !products::find($productId)) {
                return response()->json(['success' => false, 'message' => __('reviews.product_not_found')]);
            }

            $reviews = Review::with('customer')
                           ->where('products_id', $productId)
                           ->where('is_approved', true)
                           ->latest()
                           ->paginate(10);

            $averageRating = Review::getAverageRating($productId);
            $reviewCount = Review::getReviewCount($productId);
            $ratingDistribution = Review::getRatingDistribution($productId);

            return response()->json([
                'success' => true,
                'reviews' => $reviews,
                'average_rating' => round($averageRating, 1),
                'review_count' => $reviewCount,
                'rating_distribution' => $ratingDistribution
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => __('reviews.error_loading_reviews')]);
        }
    }

    /**
     * Store a reply to a review
     */
    public function storeReply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'review_id' => 'required|exists:reviews,id',
            'comment' => 'required|string|min:2|max:1000',
        ], [
            'review_id.required' => __('reviews.review_id_required'),
            'review_id.exists' => __('reviews.review_not_found'),
            'comment.required' => __('reviews.reply_required'),
            'comment.min' => __('reviews.reply_min_length'),
            'comment.max' => __('reviews.reply_max_length'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            $customer = Auth::guard('customer')->user();
            $review = Review::findOrFail($request->review_id);

            // Check if customer already replied to this review
            $existingReply = ReviewReply::where('review_id', $review->id)
                                      ->where('customer_id', $customer->id)
                                      ->first();

            if ($existingReply) {
                Alert::warning(__('reviews.already_replied'));
                return redirect()->back();
            }

            // Create new reply
            $reply = new ReviewReply();
            $reply->review_id = $review->id;
            $reply->customer_id = $customer->id;
            $reply->comment = $request->comment;
            $reply->save();

            Alert::success(__('reviews.reply_submitted_success'));
            return redirect()->back();

        } catch (\Exception $e) {
            Alert::error(__('reviews.error_submitting_reply'), $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Update a reply
     */
    public function updateReply(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required|string|min:2|max:1000',
        ], [
            'comment.required' => __('reviews.reply_required'),
            'comment.min' => __('reviews.reply_min_length'),
            'comment.max' => __('reviews.reply_max_length'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            $customer = Auth::guard('customer')->user();
            $reply = ReviewReply::where('id', $id)
                              ->where('customer_id', $customer->id)
                              ->firstOrFail();

            $reply->comment = $request->comment;
            $reply->save();

            Alert::success(__('reviews.reply_updated_success'));
            return redirect()->back();

        } catch (\Exception $e) {
            Alert::error(__('reviews.error_updating_reply'));
            return redirect()->back()->withInput();
        }
    }

    /**
     * Delete a reply
     */
    public function destroyReply($id)
    {
        try {
            $customer = Auth::guard('customer')->user();
            $reply = ReviewReply::where('id', $id)
                              ->where('customer_id', $customer->id)
                              ->firstOrFail();

            $reply->delete();

            Alert::success(__('reviews.reply_deleted_success'));
            return redirect()->back();

        } catch (\Exception $e) {
            Alert::error(__('reviews.error_deleting_reply'));
            return redirect()->back();
        }
    }

    /**
     * Check if customer has purchased the product (verified purchase)
     */
    private function checkVerifiedPurchase($customerId, $productId)
    {
        // This is a simplified check. In a real application, you would check
        // against actual order/purchase records
        // For now, we'll assume it's a verified purchase if the customer has any completed orders
        
        // You can implement this based on your order/order_items structure
        // return \App\Models\Order::where('customer_id', $customerId)
        //                         ->where('status', 'completed')
        //                         ->whereHas('items', function($query) use ($productId) {
        //                             $query->where('products_id', $productId);
        //                         })
        //                         ->exists();

        // For demonstration, returning false. Implement based on your order system.
        return false;
    }
}
