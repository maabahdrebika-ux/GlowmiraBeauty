<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatues extends Model
{
    use HasFactory;
    public $timestamps = false;

    // Add relationship to retrieve orders for this status
    public function orders() {
        return $this->hasMany(Order::class, 'order_status_id');
    }
}
