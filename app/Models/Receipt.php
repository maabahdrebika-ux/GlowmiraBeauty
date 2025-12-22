<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $casts = [
        'created_at' => 'datetime',
    ];
    protected $fillable = [
        'total_price',
        'users_id',
        'suppliers_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($request) {
            // الحصول على آخر رقم طلب
            $lastRequest = self::latest('id')->first();

            // تحديد الرقم الجديد
            $nextNumber = $lastRequest ? ((int) substr($lastRequest->receiptnumber, 5)) + 1 : 1;

            // إنشاء رقم الطلب بالتنسيق المطلوب
            $request->receiptnumber = 'REC-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
        });
    }
    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function suppliers()
    {
        return $this->belongsTo(Supplier::class, 'suppliers_id');
    }
}
