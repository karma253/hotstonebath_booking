<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dzongkhag extends Model
{
    use HasFactory;

    protected $table = 'dzongkhags';

    protected $fillable = [
        'name',
        'bhutanese_name',
        'description',
    ];

    public function baths(): HasMany
    {
        return $this->hasMany(Bath::class, 'dzongkhag_id');
    }
}
