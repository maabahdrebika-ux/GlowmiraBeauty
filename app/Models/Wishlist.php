<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'product_id',
    ];

    /**
     * Get the customer that owns the wishlist item
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the product that belongs to the wishlist item
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Check if a product is in customer's wishlist
     */
    public static function isInWishlist($customerId, $productId): bool
    {
        return self::where('customer_id', $customerId)
                  ->where('product_id', $productId)
                  ->exists();
    }

    /**
     * Toggle product in wishlist
     */
    public static function toggle($customerId, $productId): array
    {
        $inWishlist = self::isInWishlist($customerId, $productId);

        if ($inWishlist) {
            // Remove from wishlist
            self::where('customer_id', $customerId)
                ->where('product_id', $productId)
                ->delete();
            
            return [
                'in_wishlist' => false,
                'action' => 'removed',
                'message' => __('products.removed_from_wishlist')
            ];
        } else {
            // Add to wishlist
            self::create([
                'customer_id' => $customerId,
                'product_id' => $productId
            ]);
            
            return [
                'in_wishlist' => true,
                'action' => 'added',
                'message' => __('products.added_to_wishlist')
            ];
        }
    }

    /**
     * Get wishlist count for a customer
     */
    public static function getCount($customerId): int
    {
        return self::where('customer_id', $customerId)->count();
    }

    /**
     * Get wishlist items for a customer with products
     */
    public static function getWithProducts($customerId)
    {
        return self::with('product')
                  ->where('customer_id', $customerId)
                  ->get();
    }
}
