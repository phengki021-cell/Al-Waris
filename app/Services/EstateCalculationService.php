<?php

namespace App\Services;

use App\Domain\Faraid\ConsistencyException;
use App\Domain\Faraid\EstateDistribution;
use App\Domain\Faraid\FaraidEngine;
use App\Domain\Faraid\FaraidResult;
use App\Domain\Faraid\RationalNumber;

/**
 * Layanan pembagian harta (application layer): memvalidasi nominal,
 * memanggil mesin faraid, lalu mengonversi pecahan menjadi rupiah dengan
 * kebijakan pembulatan eksplisit dan verifikasi konsistensi total.
 */
final class EstateCalculationService
{
    public function __construct(private readonly FaraidEngine $engine) {}

    /**
     * @param  array<string, mixed>  $heirs  lihat FaraidEngine::calculate()
     * @param  array{estate?: float|int|string, debts?: float|int|string, bequest?: float|int|string, funeral?: float|int|string}  $money
     */
    public function calculate(array $heirs, array $money): EstateDistribution
    {
        $estate = (float) ($money['estate'] ?? 0);
        $debts = (float) ($money['debts'] ?? 0);
        $bequest = (float) ($money['bequest'] ?? 0);
        $funeral = (float) ($money['funeral'] ?? 0);

        foreach (['estate' => $estate, 'debts' => $debts, 'bequest' => $bequest, 'funeral' => $funeral] as $key => $value) {
            if ($value < 0) {
                return $this->notValid('error', "Nilai '$key' tidak boleh negatif.");
            }
        }

        if ($estate === 0.0) {
            return $this->notValid('error', 'Nilai harta peninggalan masih kosong.');
        }

        $bequestBase = max(0.0, $estate - $debts - $funeral);
        $bequestLimit = $bequestBase / 3.0;

        if ($bequest > $bequestLimit + 0.0000001) {
            return $this->notValid(
                'error',
                'Wasiat melebihi batas sepertiga (1/3). Maksimum untuk komposisi ini: Rp '.number_format($bequestLimit, 2, ',', '.').'. Kewajiban pendekatan berdasarkan harta bersih yang dapat dibagi.'
            );
        }

        $netEstate = max(0.0, $bequestBase - $bequest);

        $result = $this->engine->calculate($heirs);

        if (! $result->isValid()) {
            return new EstateDistribution(
                inputs: compact('estate', 'debts', 'bequest', 'funeral'),
                netEstate: $netEstate,
                status: $result->status,
                method: null,
                methodLabel: null,
                engineVersion: FaraidEngine::VERSION,
                shares: [],
                blocked: [],
                trace: $result->trace,
                notes: $result->notes,
                disputed: false,
                rounding: ['method' => 'none', 'floor_per_share' => false, 'remainder_assigned_to' => null, 'remainder_cents' => 0],
                totalCents: 0,
            );
        }

        try {
            $shares = $this->toMoney($result, $netEstate);
        } catch (ConsistencyException $e) {
            return $this->notValid('error', $e->getMessage());
        }

        return new EstateDistribution(
            inputs: compact('estate', 'debts', 'bequest', 'funeral'),
            netEstate: $netEstate,
            status: 'valid',
            method: $result->method,
            methodLabel: $this->methodLabel($result->method),
            engineVersion: FaraidEngine::VERSION,
            shares: $shares['list'],
            blocked: $result->blocked,
            trace: $result->trace,
            notes: array_merge($result->notes, $shares['notes']),
            disputed: $result->isDisputed(),
            rounding: $shares['rounding'],
            totalCents: $shares['total'],
        );
    }

    /**
     * Konversi pecahan → rupiah (integer sen) dengan pembulatan konsisten:
     * - tiap bagian dibulatkan ke bawah ke sen terdekat,
     * - sisa sen dibagikan sekali ke penerima dengan bagian terbesar,
     * - total nominal WAJIB sama dengan netCents — bila tidak, distribusi
     *   ditandai error (dipanggil sebagai notValid dari caller).
     *
     * @return array{list: list<array<string, mixed>>, notes: list<string>, rounding: array{method: string, floor_per_share: bool, remainder_assigned_to: string|null, remainder_cents: int}, total: int}
     */
    private function toMoney(FaraidResult $result, float $netEstate): array
    {
        $netCents = (int) round($netEstate * 100);

        $entries = [];
        $notes = [];

        foreach ($result->shares as $share) {
            $fraction = RationalNumber::of($share['n'], $share['d']);

            // Sen eksak per grup = netCents × n / d (pembulatan ke bawah).
            $exact = $netCents * $fraction->numerator / $fraction->denominator;
            $floorCents = (int) floor($exact);

            $percent = $fraction->toFloat() * 100.0;

            $entries[] = $share + [
                'fraction' => $fraction,
                'total_cents' => $floorCents,
                'per_person_cents' => $share['count'] > 0
                    ? (int) floor($floorCents / $share['count'])
                    : 0,
                'percent' => $percent,
                'total_rp' => $floorCents / 100.0,
                'per_person_rp' => $share['count'] > 0
                    ? ((int) floor($floorCents / $share['count'])) / 100.0
                    : 0.0,
            ];
        }

        // Distribusi sisa sen ke bagian terbesar.
        $allocated = array_sum(array_column($entries, 'total_cents'));
        $remainderCents = $netCents - $allocated;
        $remainderTarget = null;

        if ($remainderCents !== 0 && $entries !== []) {
            usort($entries, fn ($a, $b) => $b['total_cents'] <=> $a['total_cents']);

            $entries[0]['total_cents'] += $remainderCents;
            $entries[0]['per_person_cents'] = $entries[0]['count'] > 0
                ? (int) floor($entries[0]['total_cents'] / $entries[0]['count'])
                : 0;
            $entries[0]['total_rp'] = $entries[0]['total_cents'] / 100.0;
            $entries[0]['per_person_rp'] = $entries[0]['per_person_cents'] / 100.0;
            $remainderTarget = $entries[0]['code'];

            $notes[] = 'Pembulatan: setiap bagian dibulatkan ke bawah ke sen terdekat; selisih pembulatan ('.$remainderCents.' sen) diberikan kepada penerima bagian terbesar ('.$remainderTarget.') agar total tetap konsisten.';
        }

        $total = array_sum(array_column($entries, 'total_cents'));

        if ($total !== $netCents) {
            // JANGAN mengeluarkan nominal yang tidak konsisten — hentikan hasil final.
            throw new ConsistencyException(
                'Verifikasi total gagal: hasil nominal tidak konsisten dengan harta bersih. Hasil tidak disajikan.'
            );
        }

        return [
            'list' => $this->serializeShares($entries),
            'notes' => $notes,
            'rounding' => [
                'method' => 'floor_ke_sen + sisa ke bagian terbesar',
                'floor_per_share' => true,
                'remainder_assigned_to' => $remainderTarget,
                'remainder_cents' => $remainderCents,
            ],
            'total' => $total,
        ];
    }

    /** @param  list<array<string, mixed>>  $entries */
    private function serializeShares(array $entries): array
    {
        return array_map(fn (array $e) => [
            'code' => $e['code'],
            'name' => $e['name'],
            'count' => $e['count'],
            'n' => $e['n'],
            'd' => $e['d'],
            'text' => $e['text'],
            'per_person_text' => $e['per_person_text'],
            'disputed' => $e['disputed'],
            'percent' => $e['percent'],
            'total_cents' => $e['total_cents'],
            'per_person_cents' => $e['per_person_cents'],
            'total_rp' => $e['total_rp'],
            'per_person_rp' => $e['per_person_rp'],
        ], $entries);
    }

    private function methodLabel(?string $method): ?string
    {
        return match ($method) {
            'amal_ummi' => 'Amal Ummi (bagian pas)',
            'aul' => "'Aul (asal masalah dinaikkan)",
            'radd' => 'Radd (sisa dikembalikan proporsional)',
            'gharawain' => 'Gharawain (+ radd, kasus beda pendapat)',
            default => null,
        };
    }

    private function notValid(string $status, string $message): EstateDistribution
    {
        return new EstateDistribution(
            // Input nol: untuk status non-valid, nominal belum dipakai dan
            // tidak ditampilkan di UI (hanya pesan status).
            inputs: ['estate' => 0.0, 'debts' => 0.0, 'bequest' => 0.0, 'funeral' => 0.0],
            netEstate: 0.0,
            status: $status,
            method: null,
            methodLabel: null,
            engineVersion: FaraidEngine::VERSION,
            shares: [],
            blocked: [],
            trace: [],
            notes: [$message],
            disputed: false,
            rounding: ['method' => 'none', 'floor_per_share' => false, 'remainder_assigned_to' => null, 'remainder_cents' => 0],
            totalCents: 0,
        );
    }
}
