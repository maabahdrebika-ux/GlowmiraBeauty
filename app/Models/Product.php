<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'namee',
        'material',
        'brandname_ar',
        'brandname_en',
        'country_of_origin_ar',
        'country_of_origin_en',
        'description_ar',
        'description_en',
        'barcode',
        'price',
        'categories_id',
        'image',
        'notes',
        'is_available',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    /**
     * Get the category that owns the product
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }

    /**
     * Get all discounts for the product
     */
    public function discounts(): HasMany
    {
        return $this->hasMany(Discount::class, 'products_id');
    }

    /**
     * Get the active discount for the product
     */
    public function getActiveDiscountAttribute()
    {
        return $this->discounts()
                 
                   ->first();
    }

    /**
     * Get the discounted price
     */
    public function getDiscountedPriceAttribute()
    {
        $discount = $this->active_discount;
        if ($discount && isset($discount->percentage)) {
            return $this->price - ($this->price * $discount->percentage / 100);
        }
        return $this->price;
    }

    /**
     * Get the display name based on locale
     */
    public function getDisplayNameAttribute()
    {
        if (app()->getLocale() == 'ar') {
            return $this->name ?? $this->namee;
        }
        return $this->namee ?? $this->name;
    }

    /**
     * Get the display description based on locale
     */
    public function getDisplayDescriptionAttribute()
    {
        if (app()->getLocale() == 'ar') {
            return $this->description_ar ?? $this->description_en;
        }
        return $this->description_en ?? $this->description_ar;
    }

    /**
     * Check if product is on sale
     */
    public function getOnSaleAttribute()
    {
        return $this->active_discount !== null;
    }

    /**
     * Check if product is new (created within last 30 days)
     */
    public function getIsNewAttribute()
    {
        return $this->created_at > now()->subDays(30);
    }

    /**
     * Scope for available products
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope for products with active discounts
     */
    public function scopeOnSale($query)
    {
       
    }
}
