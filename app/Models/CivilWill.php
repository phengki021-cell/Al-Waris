<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CivilWill extends Model
{
    protected $fillable = ['session_token', 'user_id', 'has_will', 'will_date', 'will_form', 'will_document', 'notes'];
    protected $casts = ['will_date' => 'date', 'has_will' => 'boolean'];

    public function beneficiaries(): HasMany
    {
        return $this->hasMany(CivilWillBeneficiary::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

class CivilWillBeneficiary extends Model
{
    protected $fillable = ['civil_will_id', 'name', 'relation', 'gift_type', 'object', 'value', 'notes'];
    protected $casts = ['value' => 'decimal:2'];

    public function will(): BelongsTo
    {
        return $this->belongsTo(CivilWill::class, 'civil_will_id');
    }
}

class CivilBequest extends Model
{
    protected $fillable = ['session_token', 'user_id', 'donor', 'recipient', 'relation', 'given_at', 'object', 'asset_type', 'value', 'document', 'notes'];
    protected $casts = ['given_at' => 'date', 'value' => 'decimal:2'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}