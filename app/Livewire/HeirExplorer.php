<?php

namespace App\Livewire;

use App\Models\Heir;
use App\Services\ReferenceService;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * Halaman "Ahli Waris": kartu dapat diklik untuk membuka modal berisi
 * definisi, syarat, kondisi mahrum/hijab, serta dalil Al-Qur'an + KHI + hadis
 * shahih + yurisprudensi MA + jurnal ilmiah.
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
        /** @var \Illuminate\Support\Collection<string, \Illuminate\Support\Collection<int, Heir>> $groups */
        $groups = Heir::query()
            ->active()
            ->with(['shares', 'source'])
            ->orderBy('category')
            ->orderBy('name_id')
            ->get()
            ->groupBy('category');

        // Ahli waris berperan ganda: tampil di golongan asalnya DAN di golongan
        // kedua (mis. anak perempuan: dzawil furudh sekaligus ashabah bil ghair
        // bila bersama anak laki-laki). Klon tampil di golongan tambahan
        // disertai chip penjelas agar pembaca tidak mengira data ganda.
        $categories = [
            'fard' => 'Dzawil Furudh',
            'asabah' => 'Ashabah',
            'arham' => 'Dzawil Arham',
            'hajib' => 'Penghalang',
        ];

        $dualRoles = [
            'daughter' => ['asabah' => 'Ashabah bil ghair — ketika bersama anak laki-laki (nisbah 2 : 1)'],
            'daughter_of_son' => ['asabah' => 'Ashabah bil ghair — ketika bersama putu laki-laki (nisbah 2 : 1)'],
            'sister_female_full' => ['asabah' => 'Ashabah bil ghair — ketika bersama saudara sedarah laki-laki (nisbah 2 : 1)'],
            'sister_paternal' => ['asabah' => 'Ashabah bil ghair — ketika bersama saudara seayah laki-laki (nisbah 2 : 1)'],
            'father' => ['asabah' => 'Ashabah binafsih — ketika pewaris hanya menyisakan anak perempuan'],
            'grandfather_father' => ['asabah' => 'Pengganti ayah — menerima sisa bila pewaris hanya menyisakan anak perempuan'],
        ];

        $all = $groups->flatMap(fn ($g) => $g->values());

        foreach ($dualRoles as $code => $roles) {
            $heir = $all->firstWhere('code', $code);

            if ($heir === null) {
                continue;
            }

            foreach ($roles as $extraCategory => $note) {
                if ($extraCategory !== $heir->category) {
                    $heir->setAttribute('dual_home', 'Tampil juga di golongan '.$categories[$extraCategory]);
                    $clone = clone $heir;
                    $heir->offsetUnset('dual_home');
                    $clone->setAttribute('dual_note', $note);
                    $groups->put($extraCategory, ($groups->get($extraCategory) ?? new \Illuminate\Support\Collection())->push($clone));
                    $groups->get($extraCategory)->sortBy('name_id');
                }
            }
        }

        $fullCategories = [
            'fard' => 'Dzawil Furudh (bagian pasti)',
            'asabah' => 'Ashabah (menerima sisa)',
            'arham' => 'Dzawil Arham (kerabat)',
            'hajib' => 'Penghalang (hajib)',
        ];

        return view('livewire.heir-explorer', [
            'groups' => $groups,
            'categories' => $fullCategories,
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

        $references = app(ReferenceService::class);

        return [
            'definisi' => Arr::get($entry, 'definisi'),
            'syarat' => (array) Arr::get($entry, 'syarat', []),
            'menghalangi' => (array) Arr::get($entry, 'menghalangi', []),
            'dihalangi' => (array) Arr::get($entry, 'dihalangi', []),
            'verse' => $verse,
            'verse_code' => $verseCode,
            'pasals' => $pasals,
            'catatan_dalil' => Arr::get($dalil, 'catatan'),
            'yurisprudensi' => $references->yurisprudensi(
                array_values(array_filter((array) Arr::get($dalil, 'yurisprudensi', []), 'is_string')),
            ),
            'hadits' => $references->hadits(),
            'kuhperdata' => $references->kuhperdata(),
            'jurnal' => $references->jurnal(),
            'terkait_shares' => $heir->shares,
        ];
    }
}
