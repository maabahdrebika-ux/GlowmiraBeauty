<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'notes',
    ];

    /**
     * Get the receipts for the supplier.
     */
    public function receipts()
    {
        return $this->hasMany(Receipt::class, 'suppliers_id');
    }
}
