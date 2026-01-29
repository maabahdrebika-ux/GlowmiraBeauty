<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReviewReply extends Model
{
    use HasFactory;

    protected $table = 'review_replies';

    protected $fillable = [
        'review_id',
        'customer_id',
        'admin_id',
        'comment',
    ];

    protected $casts = [
        'review_id' => 'integer',
        'customer_id' => 'integer',
        'admin_id' => 'integer',
    ];

    /**
     * Get the review that owns the reply
     */
    public function review(): BelongsTo
    {
        return $this->belongsTo(Review::class);
    }

    /**
     * Get the customer that created the reply
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the admin that created the reply
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'admin_id');
    }

    /**
     * Check if the reply is from an admin
     */
    public function isFromAdmin(): bool
    {
        return $this->admin_id !== null;
    }

    /**
     * Check if the reply is from a customer
     */
    public function isFromCustomer(): bool
    {
        return $this->customer_id !== null;
    }
}
