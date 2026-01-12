<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'products_id',
        'percentage',
        
    ];

    public $timestamps = false;

    protected $casts = [
        'percentage' => 'integer',
      
    ];

    /**
     * Get the product that owns the discount
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(products::class, 'products_id');
    }
 public function products(): BelongsTo
    {
        return $this->belongsTo(products::class, 'products_id');
    }
    
}
