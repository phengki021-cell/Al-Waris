<?php

namespace App\Models;

use Database\Factories\SourceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Source extends Model
{
    /** @use HasFactory<SourceFactory> */
    use HasFactory;

    public const TYPES = ['quran', 'hadis', 'kitab', 'kompilasi', 'fatwa', 'lainnya'];

    protected $fillable = ['code', 'title', 'type', 'reference', 'url', 'excerpt', 'is_primary'];

    protected $casts = ['is_primary' => 'boolean'];

    /** @return HasMany<Heir, $this> */
    public function heirs(): HasMany
    {
        return $this->hasMany(Heir::class);
    }
}
