<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'user_id',
        'booking_id',
        'payment_method',
        'amount',
        'status',
        'error_message',
        'retry_count',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    /**
     * Get the user that made the transaction
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the booking associated with the transaction
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    /**
     * Generate a unique transaction ID
     */
    public static function generateTransactionId(): string
    {
        $timestamp = now()->format('YmdHis');
        $random = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        return 'TXN' . $timestamp . $random;
    }
}
