<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews';

    protected $fillable = [
        'customer_id',
        'products_id',
        'rating',
        'comment',
        'is_approved',
        'is_verified_purchase',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean',
        'is_verified_purchase' => 'boolean',
    ];

    /**
     * Get the customer that owns the review
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the product that owns the review
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(products::class, 'products_id');
    }

    /**
     * Scope for approved reviews only
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope for verified purchase reviews
     */
    public function scopeVerifiedPurchase($query)
    {
        return $query->where('is_verified_purchase', true);
    }

    /**
     * Get the average rating for a product
     */
    public static function getAverageRating($productId)
    {
        return static::where('products_id', $productId)
                   ->where('is_approved', true)
                   ->avg('rating');
    }

    /**
     * Get the total review count for a product
     */
    public static function getReviewCount($productId)
    {
        return static::where('products_id', $productId)
                   ->where('is_approved', true)
                   ->count();
    }

    /**
     * Get the rating distribution for a product
     */
    public static function getRatingDistribution($productId)
    {
        $distribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $distribution[$i] = static::where('products_id', $productId)
                                     ->where('is_approved', true)
                                     ->where('rating', $i)
                                     ->count();
        }
        return $distribution;
    }
}
