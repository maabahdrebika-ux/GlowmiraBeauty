<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceType extends Model
{
    use HasFactory;
    
    protected $table = 'invoice_types';
    
    public $timestamps = false;
    
    protected $fillable = [
        'name'
    ];
    
    protected $casts = [
        'created_at' => 'datetime',
    ];
    
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'invoice_types_id');
    }
}
