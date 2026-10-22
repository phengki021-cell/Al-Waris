<?php

namespace App\Livewire;

use App\Domain\Faraid\EstateDistribution;
use App\Services\CivilInheritanceService;
use App\Services\EstateCalculationService;
use App\Services\ReferenceService;
use App\Services\SaveCalculationService;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app', ['title' => 'Hitung Warisan'])]
class Calculator extends Component
{
    /** @var array<string, list<string>> */
    private const RULES = [
        'estate' => ['required', 'numeric', 'min:0'],
        'debts' => ['nullable', 'numeric', 'min:0'],
        'bequest' => ['nullable', 'numeric', 'min:0'],
        'funeral' => ['nullable', 'numeric', 'min:0'],
        'spouse' => ['nullable', 'in:husband,wife'],
        'wifeCount' => ['integer', 'min:1', 'max:4'],
        'sons' => ['integer', 'min:0', 'max:30'],
        'daughters' => ['integer', 'min:0', 'max:30'],
        'maternalGrandmother' => ['boolean'],
        'grandfather' => ['boolean'],
        'brothers' => ['integer', 'min:0', 'max:30'],
        'sisters' => ['integer', 'min:0', 'max:30'],
        'grandsons' => ['integer', 'min:0', 'max:30'],
        'granddaughters' => ['integer', 'min:0', 'max:30'],
    ];

    /** @var array<string, list<string>> */
    private const CIVIL_RULES = [
        'estate' => ['required', 'numeric', 'min:0'],
        'debts' => ['nullable', 'numeric', 'min:0'],
        'bequest' => ['nullable', 'numeric', 'min:0'],
        'funeral' => ['nullable', 'numeric', 'min:0'],
        'civilSpouse' => ['boolean'],
        'civilChildren' => ['integer', 'min:0', 'max:30'],
        'civilBranches' => ['integer', 'min:0', 'max:30'],
        'civilGrandchildren' => ['integer', 'min:0', 'max:99'],
    ];

    /** Tab aktif: faraid (utama, tak tersentuh) | civil (KUHPerdata edukasi) | kontemporer. */
    public string $tab = 'faraid';

    // --- Nominal ---
    public string $estate = '';

    public string $debts = '';

    public string $bequest = '';

    public string $funeral = '';

    // --- Ahli waris (mode Faraidh — alur asli) ---
    public string $spouse = '';            // '' | 'husband' | 'wife'

    public int $wifeCount = 1;

    public int $sons = 0;

    public int $daughters = 0;

    public bool $father = false;

    public bool $mother = false;

    public bool $maternalGrandmother = false;

    /** Ahli waris pengganti: kakek dari ayah (menggantikan ayah yang tiada). */
    public bool $grandfather = false;

    public int $brothers = 0;

    public int $sisters = 0;

    // --- Ahli waris pengganti (perwakilan): cucu dari anak laki-laki wafat ---
    public int $grandsons = 0;

    public int $granddaughters = 0;

    public ?array $result = null;

    /** Tentukan apakah panel hasil ditampilkan (alur: input → ringkasan → hasil). */
    public bool $showResult = false;

    // --- Ahli waris mode perdata KUHPerdata (opsional, terpisah dari faraid) ---
    public bool $civilSpouse = false;

    public int $civilChildren = 0;

    public int $civilBranches = 0;

    public int $civilGrandchildren = 0;

    public ?array $civilResult = null;

    public bool $showCivilResult = false;

    public function resetAll(): void
    {
        $this->reset();
    }

    /** Pindah tab kalkulator; setiap tab memelihara input masing-masing. */
    public function setTab(string $tab): void
    {
        if (in_array($tab, ['faraid', 'civil', 'kontemporer'], true)) {
            $this->tab = $tab;
        }
    }

    /** Tombol "Kembali mengubah input": sembunyikan hasil, input tidak dihapus. */
    public function backToInput(): void
    {
        $this->showResult = false;
    }

    /** Tombol "Mulai perhitungan baru" panel perdata: bersihkan data mode perdata saja (faraid tidak tersentuh). */
    public function startNewCivil(): void
    {
        $this->civilSpouse = false;
        $this->civilChildren = 0;
        $this->civilBranches = 0;
        $this->civilGrandchildren = 0;
        $this->civilResult = null;
        $this->showCivilResult = false;
    }

    /** Tombol "Mulai perhitungan baru": bersihkan semua input & hasil. */
    public function startNew(): void
    {
        $this->reset();
    }

    public function calculate(): void
    {
        // Validasi gagal tidak boleh menghapus input pengguna (kesalahan yang
        // dapat dipulihkan) — Livewire mempertahankan properti; panel hasil
        // ditutup agar tidak membingungkan dengan lama.
        $this->showResult = false;

        $this->validate([...self::RULES], self::MESSAGES);

        $this->result = $this->buildDistribution()->toDisplayArray();
        $this->showResult = true;
    }

    /** Pesan validasi spesifik berbahasa Indonesia agar mudah diperbaiki. */
    private const MESSAGES = [
        'estate.required' => 'Total harta peninggalan wajib diisi — masukkan nominal harta dalam rupiah.',
        'estate.numeric' => 'Total harta harus berupa angka tanpa tanda titik/koma pemisah, mis. 500000000.',
        'estate.min' => 'Total harta tidak boleh bernilai negatif.',
        'debts.numeric' => 'Hutang almarhum harus berupa angka dalam rupiah.',
        'debts.min' => 'Hutang almarhum tidak boleh bernilai negatif.',
        'bequest.numeric' => 'Wasiat harus berupa angka dalam rupiah.',
        'bequest.min' => 'Wasiat tidak boleh bernilai negatif. Batas maksimal wasiat 1/3 dari harta bersih dan divalidasi mesin.',
        'funeral.numeric' => 'Biaya pengurusan jenazah harus berupa angka dalam rupiah.',
        'funeral.min' => 'Biaya pengurusan jenazah tidak boleh bernilai negatif.',
        'spouse.in' => 'Pilihan pasangan tidak dikenal — pilih Suami, Istri, atau kosongkan.',
        'wifeCount.integer' => 'Jumlah istri harus berupa bilangan bulat.',
        'wifeCount.min' => 'Jumlah istri minimal 1.',
        'wifeCount.max' => 'Jumlah istri maksimal 4 sesuai syariat.',
        'sons.integer' => 'Anak laki-laki harus berupa bilangan bulat.',
        'sons.min' => 'Jumlah anak laki-laki tidak boleh negatif.',
        'sons.max' => 'Jumlah anak laki-laki maksimal 30.',
        'daughters.integer' => 'Anak perempuan harus berupa bilangan bulat.',
        'daughters.min' => 'Jumlah anak perempuan tidak boleh negatif.',
        'daughters.max' => 'Jumlah anak perempuan maksimal 30.',
        'brothers.integer' => 'Jumlah saudara sedarah laki-laki harus berupa bilangan bulat.',
        'brothers.min' => 'Jumlah saudara sedarah laki-laki tidak boleh negatif.',
        'brothers.max' => 'Jumlah saudara sedarah laki-laki maksimal 30.',
        'sisters.integer' => 'Jumlah saudara sedarah perempuan harus berupa bilangan bulat.',
        'sisters.min' => 'Jumlah saudara sedarah perempuan tidak boleh negatif.',
        'sisters.max' => 'Jumlah saudara sedarah perempuan maksimal 30.',
        'grandsons.integer' => 'Jumlah cucu laki-laki harus berupa bilangan bulat.',
        'grandsons.min' => 'Jumlah cucu laki-laki tidak boleh negatif.',
        'grandsons.max' => 'Jumlah cucu laki-laki maksimal 30.',
        'granddaughters.integer' => 'Jumlah cucu perempuan harus berupa bilangan bulat.',
        'granddaughters.min' => 'Jumlah cucu perempuan tidak boleh negatif.',
        'granddaughters.max' => 'Jumlah cucu perempuan maksimal 30.',
    ];

    /** Pesan validasi tab mode perdata (KUHPerdata). */
    private const CIVIL_MESSAGES = [
        'estate.required' => 'Total harta peninggalan wajib diisi — masukkan nominal harta dalam rupiah.',
        'estate.numeric' => 'Total harta harus berupa angka tanpa tanda titik/koma pemisah, mis. 500000000.',
        'estate.min' => 'Total harta tidak boleh bernilai negatif.',
        'debts.numeric' => 'Hutang almarhum harus berupa angka dalam rupiah.',
        'debts.min' => 'Hutang almarhum tidak boleh bernilai negatif.',
        'bequest.numeric' => 'Wasiat/hibah harus berupa angka dalam rupiah.',
        'bequest.min' => 'Wasiat/hibah tidak boleh bernilai negatif.',
        'funeral.numeric' => 'Biaya pengurusan jenazah harus berupa angka dalam rupiah.',
        'funeral.min' => 'Biaya pengurusan jenazah tidak boleh bernilai negatif.',
        'civilChildren.integer' => 'Jumlah anak harus berupa bilangan bulat.',
        'civilChildren.min' => 'Jumlah anak tidak boleh negatif.',
        'civilChildren.max' => 'Jumlah anak maksimal 30.',
        'civilBranches.integer' => 'Jumlah cabang anak wafat harus berupa bilangan bulat.',
        'civilBranches.min' => 'Jumlah cabang anak wafat tidak boleh negatif.',
        'civilBranches.max' => 'Jumlah cabang anak wafat maksimal 30.',
        'civilGrandchildren.integer' => 'Total cucu harus berupa bilangan bulat.',
        'civilGrandchildren.min' => 'Total cucu tidak boleh negatif.',
        'civilGrandchildren.max' => 'Total cucu maksimal 99.',
    ];

    public function saveToHistory(): void
    {
        if (! $this->result || ($this->result['status'] ?? '') !== 'valid') {
            return;
        }

        app(SaveCalculationService::class)->save(
            $this->buildDistribution(),
            session()->getId(),
        );

        session()->flash('message', 'Hasil disimpan ke Riwayat Perhitungan.');
    }

    /**
     * Hitung model edukasi Golongan I (KUHPerdata) — tab opsional, memakai
     * service yang berdiri sendiri; mesin faraid tidak tersentuh sama sekali.
     */
    public function calculateCivil(): void
    {
        $this->showCivilResult = false;

        $this->validate([...self::CIVIL_RULES], self::CIVIL_MESSAGES);

        $this->civilResult = app(CivilInheritanceService::class)->calculate([
            'estate' => $this->estate,
            'debts' => $this->debts,
            'funeral' => $this->funeral,
            'bequest' => $this->bequest,
            'spouse' => $this->civilSpouse,
            'children' => $this->civilChildren,
            'branches' => $this->civilBranches,
            'grandchildren' => $this->civilGrandchildren,
        ]);

        if (($this->civilResult['status'] ?? '') === 'valid') {
            $this->showCivilResult = true;
        }
    }

    /**
     * Input Livewire → service → EstateDistribution.
     * Pola tunggal dipakai baik untuk tampilan hasil maupun penyimpanan,
     * sehingga yang disimpan selalu sama dengan yang ditampilkan.
     */
    private function buildDistribution(): EstateDistribution
    {
        return app(EstateCalculationService::class)->calculate(
            heirs: [
                'husband' => $this->spouse === 'husband',
                'wife_count' => $this->spouse === 'wife' ? $this->wifeCount : 0,
                'sons' => $this->sons,
                'daughters' => $this->daughters,
                'father' => $this->father,
                'mother' => $this->mother,
                'maternal_grandmother' => $this->maternalGrandmother,
                'grandfather' => $this->grandfather,
                'grandsons' => $this->grandsons,
                'granddaughters' => $this->granddaughters,
                'brothers' => $this->brothers,
                'sisters' => $this->sisters,
            ],
            money: [
                'estate' => (float) ($this->estate ?: 0),
                'debts' => (float) ($this->debts ?: 0),
                'bequest' => (float) ($this->bequest ?: 0),
                'funeral' => (float) ($this->funeral ?: 0),
            ],
        );
    }

    public function render(): View
    {
        $reference = app(ReferenceService::class);

        /** @var list<array<string, mixed>> $kontemporerCards */
        $kontemporerCards = [];

        foreach ($reference->kontemporer() as $card) {
            $jurnalKeys = array_values(array_filter(
                is_array($card['jurnal'] ?? null) ? $card['jurnal'] : [],
                is_string(...),
            ));

            $kontemporerCards[] = [
                'jurnal_entries' => $reference->jurnal($jurnalKeys, false),
            ] + $card;
        }

        return view('livewire.calculator', [
            'kuhperdata' => $reference->kuhperdata(),
            'kontemporer' => $kontemporerCards,
        ]);
    }
}
