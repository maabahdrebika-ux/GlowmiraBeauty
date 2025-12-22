<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * The table associated with the model.
     */
    protected $table = 'notifications';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'orders_id',
        'message',
        'messageen'
    ];

    /**
     * Get the order associated with the notification.
     */
    public function orders()
    {
        return $this->belongsTo(Order::class, 'orders_id');
    }

    /**
     * Mark the notification as read.
     */
    public function markAsRead()
    {
        $this->is_read = true;
        $this->save();
    }
}
