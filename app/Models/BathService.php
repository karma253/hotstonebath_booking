<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BathService extends Model
{
    use HasFactory;

    protected $fillable = [
        'bath_id',
        'dzongkhag_id',
        'service_type',
        'description',
        'location',
        'duration_minutes',
        'price',
        'max_guests',
        'is_available',
        'approval_status',
        'approval_notes',
        'reviewed_at',
        'image',
        'opening_time',
        'closing_time',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'price' => 'decimal:2',
        'reviewed_at' => 'datetime',
    ];

    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    public function scopePendingApproval($query)
    {
        return $query->where('approval_status', 'pending');
    }

    public function bath(): BelongsTo
    {
        return $this->belongsTo(Bath::class, 'bath_id');
    }

    public function dzongkhag(): BelongsTo
    {
        return $this->belongsTo(Dzongkhag::class, 'dzongkhag_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'service_id');
    }
}
