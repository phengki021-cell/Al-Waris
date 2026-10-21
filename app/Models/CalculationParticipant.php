<?php

namespace App\Models;

use Database\Factories\CalculationParticipantFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CalculationParticipant extends Model
{
    /** @use HasFactory<CalculationParticipantFactory> */
    use HasFactory;

    protected $fillable = [
        'calculation_id', 'heir_id', 'code', 'name_id', 'count', 'attributes', 'sort_order',
    ];

    protected $casts = ['attributes' => 'array'];

    /** @return BelongsTo<Calculation, $this> */
    public function calculation(): BelongsTo
    {
        return $this->belongsTo(Calculation::class);
    }

    /** @return BelongsTo<Heir, $this> */
    public function heir(): BelongsTo
    {
        return $this->belongsTo(Heir::class);
    }

    /** @return HasMany<CalculationResult, $this> */
    public function results(): HasMany
    {
        return $this->hasMany(CalculationResult::class, 'participant_id');
    }
}
