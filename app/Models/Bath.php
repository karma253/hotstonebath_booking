<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bath extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'name',
        'property_type',
        'dzongkhag_id',
        'full_address',
        'latitude',
        'longitude',
        'short_description',
        'detailed_description',
        'tourism_license_number',
        'issuing_authority',
        'license_issue_date',
        'license_expiry_date',
        'license_status',
        'max_guests',
        'price_per_hour',
        'price_per_session',
        'booking_type',
        'cancellation_policy',
        'status',
        'verified_at',
        'verification_notes',
    ];

    protected $casts = [
        'license_issue_date' => 'date',
        'license_expiry_date' => 'date',
        'verified_at' => 'datetime',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function dzongkhag(): BelongsTo
    {
        return $this->belongsTo(Dzongkhag::class, 'dzongkhag_id');
    }

    public function services(): HasMany
    {
        return $this->hasMany(BathService::class, 'bath_id');
    }

    public function facilities(): HasMany
    {
        return $this->hasMany(BathFacility::class, 'bath_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(BathImage::class, 'bath_id');
    }

    public function availabilities(): HasMany
    {
        return $this->hasMany(Availability::class, 'bath_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(VerificationDocument::class, 'bath_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'bath_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'bath_id');
    }
}
