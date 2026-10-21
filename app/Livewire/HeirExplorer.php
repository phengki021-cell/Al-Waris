<?php

namespace App\Livewire;

use App\Models\Heir;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * Halaman "Ahli Waris": kartu dapat diklik untuk membuka modal berisi
 * definisi, syarat, kondisi mahrum/hijab, serta dalil Al-Qur'an + KHI.
 * Data edukasi statis di config/furudh-heirs.php (berpasal & ditandai jujur).
 *
 * @property-read Heir|null $selectedHeir
 * @property-read array<string, mixed>|null $selectedProfile
 */
#[Layout('layouts.app', ['title' => 'Ahli Waris'])]
class HeirExplorer extends Component
{
    /** Id heir yang modal-nya sedang terbuka. */
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
        $groups = Heir::query()
            ->active()
            ->with(['shares', 'source'])
            ->orderBy('category')
            ->orderBy('name_id')
            ->get()
            ->groupBy('category');

        $categories = [
            'fard' => 'Dzawil Furudh (bagian pasti)',
            'asabah' => 'Ashabah (menerima sisa)',
            'arham' => 'Dzawil Arham (kerabat)',
            'hajib' => 'Penghalang (hajib)',
        ];

        return view('livewire.heir-explorer', [
            'groups' => $groups,
            'categories' => $categories,
        ]);
    }

    /** Heir yang modal-nya sedang terbuka (tidak diserialisasi Livewire). */
    public function getSelectedHeirProperty(): ?Heir
    {
        return $this->selectedId === null
            ? null
            : Heir::query()->with(['shares', 'source'])->find($this->selectedId);
    }

    /**
     * Profil lengkap ahli waris terpilih dari config/furudh-heirs.php;
     * dalil memakai data verbatim QS & pasal KHI dari config/furudh-dalil.php.
     *
     * @return array<string, mixed>|null
     */
    public function getSelectedProfileProperty(): ?array
    {
        $heir = $this->selectedHeir;

        if ($heir === null) {
            return null;
        }

        $entry = config('furudh-heirs.heirs.'.$heir->code);

        if (! is_array($entry)) {
            return null;
        }

        $dalil = (array) Arr::get($entry, 'dalil', []);
        $verseCode = Arr::get($dalil, 'verse');

        /** @var array<string, mixed>|null $verse */
        $verse = is_string($verseCode)
            ? Arr::get(config('furudh-dalil.verses', []), $verseCode)
            : null;

        /** @var array<int, mixed> $pasalNumbers */
        $pasalNumbers = Arr::get($dalil, 'pasals', []);

        $pasals = [];
        /** @var array<int, array<string, string>> $khi */
        $khi = config('furudh-dalil.khi', []);

        foreach ($pasalNumbers as $nomor) {
            $pasals[] = ['nomor' => $nomor] + (array) $khi[(int) $nomor];
        }

        return [
            'definisi' => Arr::get($entry, 'definisi'),
            'syarat' => (array) Arr::get($entry, 'syarat', []),
            'menghalangi' => (array) Arr::get($entry, 'menghalangi', []),
            'dihalangi' => (array) Arr::get($entry, 'dihalangi', []),
            'verse' => $verse,
            'verse_code' => $verseCode,
            'pasals' => $pasals,
            'catatan_dalil' => Arr::get($dalil, 'catatan'),
            'terkait_shares' => $heir->shares,
        ];
    }
}
