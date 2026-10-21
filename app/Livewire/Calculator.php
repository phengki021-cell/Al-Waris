<?php

namespace App\Livewire;

use App\Domain\Faraid\EstateDistribution;
use App\Services\EstateCalculationService;
use App\Services\SaveCalculationService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
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
        'brothers' => ['integer', 'min:0', 'max:30'],
        'sisters' => ['integer', 'min:0', 'max:30'],
    ];

    // --- Nominal ---
    public string $estate = '';

    public string $debts = '';

    public string $bequest = '';

    public string $funeral = '';

    // --- Ahli waris ---
    public string $spouse = '';            // '' | 'husband' | 'wife'

    public int $wifeCount = 1;

    public int $sons = 0;

    public int $daughters = 0;

    public bool $father = false;

    public bool $mother = false;

    public bool $maternalGrandmother = false;

    public int $brothers = 0;

    public int $sisters = 0;

    public ?array $result = null;

    /** Tentukan apakah panel hasil ditampilkan (alur: input → ringkasan → hasil). */
    public bool $showResult = false;

    public function resetAll(): void
    {
        $this->reset();
    }

    /** Tombol "Kembali mengubah input": sembunyikan hasil, input tidak dihapus. */
    public function backToInput(): void
    {
        $this->showResult = false;
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

    public function render()
    {
        return view('livewire.calculator');
    }
}
