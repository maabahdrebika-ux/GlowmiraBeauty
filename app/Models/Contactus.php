<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contactus extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'name',
        'email', // Added email here
        'message',
        'phone', // Add other fields as needed
    ];

}
