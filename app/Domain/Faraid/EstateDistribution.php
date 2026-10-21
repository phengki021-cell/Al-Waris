<?php

namespace App\Domain\Faraid;

/**
 * Struktur hasil lengkap perhitungan harta (diproduksi oleh
 * App\Services\EstateCalculationService): input → mesin → nominal.
 * Murni data, tanpa dependensi Illuminate.
 */
final class EstateDistribution
{
    /**
     * @param  array{estate: float, debts: float, bequest: float, funeral: float}  $inputs
     * @param  string  $status  'valid'|'not_supported'|'error'
     * @param  list<array<string, mixed>>  $shares
     * @param  list<array{code: string, name: string, reason: string}>  $blocked
     * @param  array{method: string, floor_per_share: bool, remainder_assigned_to: string|null, remainder_cents: int}  $rounding
     */
    public function __construct(
        public readonly array $inputs,
        public readonly float $netEstate,
        public readonly string $status,
        public readonly ?string $method,
        public readonly ?string $methodLabel,
        public readonly string $engineVersion,
        public readonly array $shares,
        public readonly array $blocked,
        public readonly array $trace,
        public readonly array $notes,
        public readonly bool $disputed,
        public readonly array $rounding,
        public readonly int $totalCents,
    ) {}

    public function isValid(): bool
    {
        return $this->status === 'valid';
    }

    public function isDisputed(): bool
    {
        return $this->disputed;
    }

    /** @return array<string, mixed> bentuk siap-render Livewire/Blade */
    public function toDisplayArray(): array
    {
        return [
            'inputs' => $this->inputs,
            'net_estate' => $this->netEstate,
            'status' => $this->status,
            'method' => $this->method,
            'method_label' => $this->methodLabel,
            'engine_version' => $this->engineVersion,
            'shares' => $this->shares,
            'blocked' => $this->blocked,
            'trace' => $this->trace,
            'notes' => $this->notes,
            'disputed' => $this->disputed,
            'rounding' => $this->rounding,
            'total_cents' => $this->totalCents,
        ];
    }
}
