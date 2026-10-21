<?php

namespace App\Domain\Faraid;

/**
 * Hasil perhitungan mesin faraid (immutable hasil dari FaraidEngine).
 *
 * Semua pecahan eksak (RationalNumber), tanpa floating point.
 * Setiap share membawa label DEMO/verifikasi agar UI tidak menyembunyikan statusnya.
 */
final class FaraidResult
{
    /**
     * @param  string  $status  'valid'|'not_supported'|'error'
     * @param  string|null  $method  'amal_ummi'|'aul'|'radd'|'gharawain'|null
     * @param  list<array<string, mixed>>  $shares
     * @param  list<string>  $trace  langkah-langkah perhitungan untuk UI
     * @param  list<string>  $notes  catatan penting (perbedaan pendapat, disclaimer)
     */
    public function __construct(
        public readonly string $status,
        public readonly ?string $method,
        public readonly array $shares,
        public readonly array $trace = [],
        public readonly array $notes = [],
        public readonly array $blocked = [],
    ) {}

    public static function valid(
        string $method,
        array $shares,
        array $trace = [],
        array $notes = [],
        array $blocked = [],
    ): self {
        return new self('valid', $method, $shares, $trace, $notes, $blocked);
    }

    public static function notSupported(string $reason, array $trace = []): self
    {
        return new self('not_supported', null, [], $trace, [$reason]);
    }

    public static function error(string $reason): self
    {
        return new self('error', null, [], [], [$reason]);
    }

    public function isValid(): bool
    {
        return $this->status === 'valid';
    }

    public function isDisputed(): bool
    {
        return $this->method === 'gharawain' || in_array(
            'Perbedaan pendapat fikih pada komposisi ini.',
            $this->notes,
            true
        );
    }

    /** @return array<string, mixed> rekap tampilan UI per share */
    public function toDisplayArray(): array
    {
        return [
            'status' => $this->status,
            'method' => $this->method,
            'method_label' => $this->methodLabel(),
            'shares' => $this->shares,
            'trace' => $this->trace,
            'notes' => $this->notes,
            'disputed' => $this->isDisputed(),
        ];
    }

    private function methodLabel(): ?string
    {
        return match ($this->method) {
            'amal_ummi' => 'Amal Ummi (bagian pas)',
            'aul' => "'Aul (asal masalah dinaikkan)",
            'radd' => 'Radd (sisa dikembalikan proporsional)',
            'gharawain' => 'Gharawain (+ radd, kasus berbeda pendapat)',
            default => null,
        };
    }
}
