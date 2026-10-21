<?php

namespace App\Models;

use Database\Factories\HeirFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Heir extends Model
{
    /** @use HasFactory<HeirFactory> */
    use HasFactory;

    public const CATEGORIES = ['fard', 'asabah', 'arham', 'hajib'];

    protected $fillable = [
        'code', 'name_id', 'name_ar', 'slug', 'gender', 'category',
        'max_share_numerator', 'max_share_denominator', 'detail', 'source_id', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    /** @return HasMany<Share, $this> */
    public function shares(): HasMany
    {
        return $this->hasMany(Share::class);
    }

    /** @return BelongsTo<Source, $this> */
    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}
