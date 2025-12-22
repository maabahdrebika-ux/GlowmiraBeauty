<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imagesfile extends Model
{
    use HasFactory;
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
      
        'products_id',
        'name'];
}
