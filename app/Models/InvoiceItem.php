<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invoiceitem extends Model
{
    use HasFactory;
    public $timestamps = false;

    public $table = 'invoice_items';
    protected $fillable = [
        'sizes_id',
        'products_id',
        'coolors_id',
        'price',
        'quantty',
        'users_id',
        'invoices_id',

    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function invoices()
    {
        return $this->belongsTo(invoice::class);
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
