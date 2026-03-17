<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BathFacility extends Model
{
    use HasFactory;

    protected $fillable = [
        'bath_id',
        'facility_name',
        'description',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function bath(): BelongsTo
    {
        return $this->belongsTo(Bath::class, 'bath_id');
    }
}
