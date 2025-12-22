<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aboutus extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = [
        'intro_one_title_ar',
        'intro_one_title_en',
        'intro_one_desc_ar',
        'intro_one_desc_en',
        'intro_one_bg1',
        'intro_one_bg2',
       
    ];
}
