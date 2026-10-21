<?php

namespace App\Services;

use App\Domain\Faraid\EstateDistribution;
use App\Models\Calculation;
use App\Models\CalculationParticipant;
use Illuminate\Support\Facades\DB;

/**
 * Menyimpan hasil perhitungan ke riwayat (anak tabel calculations).
 * Pengguna anonim: identitas = session token; riwayat bisa dihapus sendiri.
 */
final class SaveCalculationService
{
    /**
     * @return Calculation model yang tersimpan
     */
    public function save(EstateDistribution $distribution, string $sessionToken, ?string $notes = null): Calculation
    {
        if (! $distribution->isValid()) {
            throw new \InvalidArgumentException('Hanya hasil yang valid yang dapat disimpan.');
        }

        return DB::transaction(function () use ($distribution, $sessionToken, $notes) {
            $calculation = Calculation::create([
                'session_token' => $sessionToken,
                'reference_code' => Calculation::nextReferenceCode(),
                'estate_total' => $distribution->inputs['estate'] ?? 0,
                'debts_total' => $distribution->inputs['debts'] ?? 0,
                'bequests_total' => $distribution->inputs['bequest'] ?? 0,
                'funeral_expense' => $distribution->inputs['funeral'] ?? 0,
                'net_estate' => $distribution->netEstate,
                'method' => $distribution->method,
                'status' => $distribution->disputed ? 'needs_attention' : 'valid',
                'notes' => $notes,
            ]);

            foreach ($distribution->shares as $index => $share) {
                $participant = CalculationParticipant::create([
                    'calculation_id' => $calculation->id,
                    'code' => $share['code'],
                    'name_id' => $share['name'],
                    'count' => $share['count'],
                    'sort_order' => $index + 1,
                ]);

                $participant->results()->create([
                    'calculation_id' => $calculation->id,
                    'numerator' => $share['n'],
                    'denominator' => $share['d'],
                    'share_text' => $share['per_person_text'] ?? $share['text'],
                    'amount' => $share['total_rp'],
                    'rounding_method' => $distribution->rounding['remainder_cents'] !== 0 ? 'floor_ke_sen' : 'none',
                    'note' => $share['disputed'] ? 'Beda pendapat fikih.' : null,
                ]);
            }

            return $calculation->load('participants.results');
        });
    }
}
