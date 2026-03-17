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
        'service_type',
        'description',
        'duration_minutes',
        'price',
        'max_guests',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function bath(): BelongsTo
    {
        return $this->belongsTo(Bath::class, 'bath_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'service_id');
    }
}
