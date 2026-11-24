<?php

namespace App\Livewire;

use App\Models\Calculation;
use App\Models\CivilFamilyMember;
use App\Services\Civil\CivilInheritanceEngine;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

class CivilResult extends Component
{
    public array $result = [];
    public ?int $calculationId = null;

    public function analyze(): void
    {
        $token = session()->getId();
        $members = CivilFamilyMember::where('session_token', $token)->get();

        $data = [
            'pewaris' => $members->firstWhere('role', 'pewaris')?->toArray() ?? [],
            'pasangan' => $members->where('role', 'pasangan')->values()->toArray(),
            'anak' => $members->where('role', 'anak')->values()->toArray(),
            'ayah' => $members->firstWhere('role', 'ayah')?->toArray() ?? ['status' => 'tidak_diketahui'],
            'ibu' => $members->firstWhere('role', 'ibu')?->toArray() ?? ['status' => 'tidak_diketahui'],
            'saudara' => $members->where('role', 'saudara')->values()->toArray(),
        ];

        $engine = app(CivilInheritanceEngine::class);
        $this->result = $engine->analyze($data)->toArray();

        // Simpan riwayat (jika tabel tersedia)
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('calculations') && \Illuminate\Support\Facades\Schema::hasColumn('calculations', 'module')) {
                $calc = Calculation::create([
                    'session_token' => $token,
                    'reference_code' => 'CIV-' . now()->format('Y') . '-' . str_pad((string) (Calculation::where('module', 'civil')->count() + 1), 6, '0', STR_PAD_LEFT),
                    'estate_total' => 0,
                    'net_estate' => 0,
                    'method' => 'civil',
                    'status' => strtolower($this->result['status'] ?? 'draft'),
                    'module' => 'civil',
                ]);
                $this->calculationId = $calc->id;
            }
        } catch (\Throwable $e) {
            // ignore
        }
    }

    public function render(): View
    {
        return view('livewire.civil-result');
    }
}