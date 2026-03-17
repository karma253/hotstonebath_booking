<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BathImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'bath_id',
        'image_path',
        'image_type',
        'description',
        'order',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function bath(): BelongsTo
    {
        return $this->belongsTo(Bath::class, 'bath_id');
    }
}
