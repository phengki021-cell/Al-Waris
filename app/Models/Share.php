<?php

namespace App\Models;

use Database\Factories\ShareFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Share extends Model
{
    /** @use HasFactory<ShareFactory> */
    use HasFactory;

    protected $fillable = [
        'heir_id', 'numerator', 'denominator', 'condition', 'condition_hash', 'note', 'source_id',
    ];

    /** @return BelongsTo<Heir, $this> */
    public function heir(): BelongsTo
    {
        return $this->belongsTo(Heir::class);
    }

    /** @return BelongsTo<Source, $this> */
    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }

    /** Teks pecahan untuk tampilan, dibentuk dari kolom rasional. */
    public function toText(): string
    {
        return (int) $this->denominator === 1
            ? (string) $this->numerator
            : $this->numerator.'/'.$this->denominator;
    }
}
