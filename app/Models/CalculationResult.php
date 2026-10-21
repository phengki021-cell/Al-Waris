<?php

namespace App\Models;

use Database\Factories\CalculationResultFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalculationResult extends Model
{
    /** @use HasFactory<CalculationResultFactory> */
    use HasFactory;

    protected $fillable = [
        'calculation_id', 'participant_id', 'numerator', 'denominator',
        'share_text', 'amount', 'rounding_method', 'note',
    ];

    protected $casts = ['amount' => 'decimal:2'];

    /** @return BelongsTo<Calculation, $this> */
    public function calculation(): BelongsTo
    {
        return $this->belongsTo(Calculation::class);
    }

    /** @return BelongsTo<CalculationParticipant, $this> */
    public function participant(): BelongsTo
    {
        return $this->belongsTo(CalculationParticipant::class, 'participant_id');
    }
}
