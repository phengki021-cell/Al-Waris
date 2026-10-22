<?php

namespace App\Livewire;

use App\Models\Share;
use App\Services\ReferenceService;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * Halaman "Bagian-Bagian Warisan" (dzawil furudh): kartu dapat diklik untuk
 * membuka modal berisi dalil Al-Qur'an, rujukan KHI, syarat & ketentuan,
 * dan contoh ilustrasi hitungan (data statis di config/furudh-dalil.php).
 *
 * @property-read Share|null $selectedShare
 * @property-read array<string, mixed>|null $selectedDalil
 */
#[Layout('layouts.app', ['title' => 'Bagian-Bagian Warisan'])]
class ShareExplorer extends Component
{
    /** Id share yang modal-nya sedang terbuka. */
    public ?int $selectedId = null;

    public function openDetail(int $id): void
    {
        $this->selectedId = $id;
    }

    public function closeDetail(): void
    {
        $this->selectedId = null;
    }

    public function render(): View
    {
        return view('livewire.share-explorer', [
            'shares' => Share::with(['heir', 'source'])
                ->orderBy('heir_id')
                ->orderBy('denominator')
                ->get(),
        ]);
    }

    /** Share yang modal-nya sedang terbuka (tidak diserialisasi Livewire). */
    public function getSelectedShareProperty(): ?Share
    {
        return $this->selectedId === null
            ? null
            : Share::with(['heir', 'source'])->find($this->selectedId);
    }

    /**
     * Data dalil lengkap untuk modal; kunci peta "{kode}:p/te" harus
     * ada di config/furudh-dalil.php — dijamin oleh pengujian.
     *
     * @return array<string, mixed>|null
     */
    public function getSelectedDalilProperty(): ?array
    {
        $share = $this->selectedShare;

        if ($share === null || $share->heir === null) {
            return null;
        }

        $key = $share->heir->code.':'.$share->toText();
        $entry = data_get(config('furudh-dalil.map', []), $key);

        if (! is_array($entry)) {
            return null;
        }

        /** @var array<int, mixed> $pasalNumbers */
        $pasalNumbers = $entry['pasals'] ?? [];

        $pasals = [];
        /** @var array<int, array<string, string>> $khi */
        $khi = config('furudh-dalil.khi', []);

        foreach ($pasalNumbers as $nomor) {
            $pasals[] = ['nomor' => $nomor] + (array) $khi[(int) $nomor];
        }

        return [
            'verse' => data_get(config('furudh-dalil.verses', []), $entry['verse']),
            'verse_code' => $entry['verse'],
            'verse_kelompok' => $entry['verse_kelompok'] ?? null,
            'pasals' => $pasals,
            'khi_catatan' => $entry['khi_catatan'] ?? null,
            'syarat' => $entry['syarat'] ?? [],
            'syarat_umum' => config('furudh-dalil.syarat_umum', []),
            'contoh' => $entry['contoh'] ?? null,
            'yurisprudensi' => app(ReferenceService::class)->yurisprudensi(),
            'hadits' => app(ReferenceService::class)->hadits(),
            'kuhperdata' => app(ReferenceService::class)->kuhperdata(),
            'jurnal' => app(ReferenceService::class)->jurnal(),
        ];
    }
}
