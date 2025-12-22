<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receiptitem extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'sizes_id',
        'products_id',
        'coolors_id',
        'price',
        'quantty',
        'users_id',
        'receipts_id',
        'expired_date',

    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function receipts()
    {
        return $this->belongsTo(Receipt::class);
    }
    public function coolors()
    {
        return $this->belongsTo(Coolor::class);
    }
    public function sizes()
    {
        return $this->belongsTo(Size::class);
    }
    public function products()
    {
        return $this->belongsTo(products::class);
    }
}
