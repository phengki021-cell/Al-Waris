<?php

namespace App\Models;

use Database\Factories\CalculationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Calculation extends Model
{
    /** @use HasFactory<CalculationFactory> */
    use HasFactory;

    public const STATUSES = ['draft', 'valid', 'needs_attention', 'invalid'];

    public const METHODS = ['amal_ummi', 'aul', 'radd', 'gawalan', 'mixed'];

    protected $fillable = [
        'user_id', 'session_token', 'reference_code', 'estate_total', 'debts_total',
        'bequests_total', 'funeral_expense', 'net_estate', 'method', 'status', 'notes',
    ];

    protected $casts = [
        'estate_total' => 'decimal:2',
        'debts_total' => 'decimal:2',
        'bequests_total' => 'decimal:2',
        'funeral_expense' => 'decimal:2',
        'net_estate' => 'decimal:2',
    ];

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return HasMany<CalculationParticipant, $this> */
    public function participants(): HasMany
    {
        return $this->hasMany(CalculationParticipant::class);
    }

    /** @return HasMany<CalculationResult, $this> */
    public function results(): HasMany
    {
        return $this->hasMany(CalculationResult::class);
    }

    /** @return BelongsToMany<Source, $this> */
    public function sources(): BelongsToMany
    {
        return $this->belongsToMany(Source::class, 'calculation_source')->withPivot('rule_code');
    }

    public static function nextReferenceCode(): string
    {
        $year = now()->year;
        $count = static::whereYear('created_at', $year)->count() + 1;

        return sprintf('WRS-%d-%06d', $year, $count);
    }
}
