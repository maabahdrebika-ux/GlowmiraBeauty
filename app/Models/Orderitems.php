<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orderitems extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'orders_id',
        'products_id',
        'coolors_id',
        'sizes_id',
        'price',
        'quantty'
    ];
    
    public function products() {
        return $this->belongsTo(products::class);
    }
    // Add relationship to retrieve the associated order
    public function orders() {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function sizes() {
        return $this->belongsTo(Size::class);
    }

    public function coolors() {
        return $this->belongsTo(Coolor::class);
    }
}
