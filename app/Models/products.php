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
}
