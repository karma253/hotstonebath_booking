<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'password',
        'role',
        'status',
        'rejection_reason',
        'approved_at',
        'reviewed_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'approved_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Get all baths owned by this user.
     */
    public function baths(): HasMany
    {
        return $this->hasMany(Bath::class, 'owner_id');
    }

    /**
     * Get all bookings made by this user.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'guest_id');
    }

    /**
     * Get all reviews made by this user.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'guest_id');
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is owner/provider.
     */
    public function isOwner(): bool
    {
        return in_array($this->role, ['owner', 'manager']);
    }

    /**
     * Check if user is guest.
     */
    public function isGuest(): bool
    {
        return $this->role === 'guest';
    }

    /**
     * Check if user is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved' || $this->status === 'active';
    }
}
