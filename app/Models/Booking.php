<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'guest_id',
        'bath_id',
        'service_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'booking_date',
        'start_time',
        'end_time',
        'number_of_guests',
        'total_price',
        'payment_method',
        'payment_status',
        'payment_date',
        'status',
        'cancellation_reason',
        'cancelled_at',
        'confirmed_at',
        'completed_at',
        'special_requests',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'payment_date' => 'datetime',
        'cancelled_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'completed_at' => 'datetime',
        'total_price' => 'decimal:2',
    ];

    public function guest(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guest_id');
    }

    public function bath(): BelongsTo
    {
        return $this->belongsTo(Bath::class, 'bath_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(BathService::class, 'service_id');
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class, 'booking_id');
    }
}
