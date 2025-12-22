<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coolor extends Model
{
    use HasFactory;
    
    protected $table = 'coolors';
    public $timestamps = false;
    
    protected $fillable = [
        'products_id',
        'name',
        'namee',
    ];
}
