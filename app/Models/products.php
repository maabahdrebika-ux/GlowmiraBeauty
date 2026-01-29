<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class products extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'categories_id',
        'name',
        'namee',
        'barcode',
        'price',
        'description',
        'descriptione',
        'description_ar',
        'description_en',
        'notes',
        'brandname_ar',
        'brandname_en',
        'country_of_origin_ar',
        'country_of_origin_en',
    ];

    public function categories() {
        return $this->belongsTo(Categories::class);
    }
    public function coolors()
    {
        return $this->hasMany(Coolor::class, 'products_id');
    }
    public function sizes()
    {
        return $this->hasMany(Size::class, 'products_id');
    }

    public function imagesfiles()
    {
        return $this->hasMany(Imagesfile::class);
    }

    /**
     * Define the relationship with the Discount model.
     */
    public function discounts()
    {
        return $this->hasMany(Discount::class, 'products_id');
    }

    /**
     * Define the relationship with the Stock model.
     */
    public function stocks()
    {
        return $this->hasMany(Stock::class, 'products_id');
    }

    /**
     * Define the relationship with the Review model.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'products_id');
    }

    /**
     * Get the total stock quantity across all variations
     */
    public function getTotalStockAttribute()
    {
        return $this->stocks()->sum('quantty');
    }

    /**
     * Check if product has low stock (3-4)
     */
    public function getIsLowStockAttribute()
    {
        return $this->total_stock > 0 && $this->total_stock <= 4;
    }

    /**
     * Check if product has critical stock (1-2)
     */
    public function getIsCriticalStockAttribute()
    {
        return $this->total_stock > 0 && $this->total_stock <= 2;
    }

    /**
     * Get the stock level class for styling
     */
    public function getStockLevelClassAttribute()
    {
        if ($this->total_stock <= 0) {
            return 'stock-out';
        } elseif ($this->total_stock <= 2) {
            return 'stock-critical';
        } elseif ($this->total_stock <= 4) {
            return 'stock-low';
        }
        return '';
    }

    /**
     * Get the low stock message based on locale
     */
    public function getLowStockMessageAttribute()
    {
        if (app()->getLocale() == 'ar') {
            return 'المتبقى ' . $this->total_stock . ' قطع فقط!';
        }
        return 'Only ' . $this->total_stock . ' left!';
    }

    /**
     * Get the critical stock message based on locale
     */
    public function getCriticalStockMessageAttribute()
    {
        if (app()->getLocale() == 'ar') {
            return 'قطعتين فقط!';
        }
        return 'Only 2 left!';
    }

    /**
     * Check if product is out of stock
     */
    public function getIsOutOfStockAttribute()
    {
        return $this->total_stock <= 0;
    }

    /**
     * Get out of stock message based on locale
     */
    public function getOutOfStockMessageAttribute()
    {
        if (app()->getLocale() == 'ar') {
            return 'نفذت الكمية';
        }
        return 'Out of Stock';
    }
}
