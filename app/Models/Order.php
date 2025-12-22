<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
       'customer_id',
        'total_price',
        'order_statues_id'
    ];
    protected $casts = [
    'created_at' => 'datetime',
];


    protected $dates = [
        'created_at',
        'updated_at'
        
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            if (empty($order->ordersnumber)) {
                $order->ordersnumber = 'ORD-' . strtoupper(Str::random(8));
            }
        });
    }
    
    public function orderstatues() {
        return $this->belongsTo(OrderStatues::class, 'order_statues_id');
    }

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    // Accessor for customer name (falls back to full_name if no customer)
    public function getCustomerNameAttribute() {
        return $this->customer ? $this->customer->name : ($this->full_name ?? null);
    }

    // Accessor for customer phone (falls back to phonenumber if no customer)
    public function getCustomerPhoneAttribute() {
        return $this->customer ? $this->customer->phone : ($this->phonenumber ?? null);
    }

    // Accessor for customer address (falls back to address if no customer)
    public function getCustomerAddressAttribute() {
        return $this->customer ? $this->customer->address : ($this->address ?? null);
    }

    public function items() {
        return $this->hasMany(Orderitems::class, 'orders_id');
    }

    public function orderitems()
    {
        return $this->hasMany(Orderitems::class, 'orders_id');
    }
}
