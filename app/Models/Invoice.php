<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invoice extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $casts = [
        'created_at' => 'datetime',
    ];
    protected $fillable = [
        'total_price',
        'users_id',
        'customers_id',
        'phonenumber',
        'invoice_types_id'

    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($request) {
            // الحصول على آخر رقم طلب
            $lastRequest = self::latest('id')->first();

            // تحديد الرقم الجديد
            $nextNumber = $lastRequest ? ((int) substr($lastRequest->invoice_number, 5)) + 1 : 1;

            // إنشاء رقم الطلب بالتنسيق المطلوب
            $request->invoice_number = 'REC-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
        });
    }

    public function invoice_types()
    {
        return $this->belongsTo(InvoiceType::class);
    }
    public function users()
    {
        return $this->belongsTo(User::class);
    }
    public function customers()
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class, 'invoices_id');
    }
}
