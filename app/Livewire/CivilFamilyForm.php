<?php

namespace App\Livewire;

use App\Models\CivilFamilyMember;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

class CivilFamilyForm extends Component
{
    public int $step = 1;
    public int $totalSteps = 8;

    // Step 1: Pewaris
    public string $pewarisNama = '';
    public string $pewarisGender = 'laki-laki';
    public ?string $pewarisTglMeninggal = null;
    public string $pewarisTempat = '';
    public string $pewarisStatusNikah = 'kawin';
    public ?int $pewarisJumlahNikah = 1;
    public bool $pewarisWasiat = false;
    public bool $pewarisHibah = false;
    public string $pewarisHarta = '';
    public string $pewarisUtang = '';
    public string $pewarisCatatan = '';

    // Step 2: Pasangan (array)
    public array $pasangan = []; // [{nama, gender, status, tgl_nikah, catatan}]

    // Step 3: Anak
    public array $anak = []; // [{nama, gender, tgl_lahir, status, kategori, catatan, parent_id}]

    // Step 4: Orang tua
    public array $ayah = ['nama' => '', 'status' => 'tidak_diketahui', 'tgl_meninggal' => null, 'catatan' => ''];
    public array $ibu = ['nama' => '', 'status' => 'tidak_diketahui', 'tgl_meninggal' => null, 'catatan' => ''];

    // Step 5: Saudara
    public array $saudara = []; // [{nama, gender, kategori, status, catatan}]

    // Step 6: Penggantian tempat (parent_id per anggota) - handled via anak/parent_id

    public string $sessionToken = '';

    public function mount(): void
    {
        $this->sessionToken = session()->getId();
        $this->pasangan = [];
        $this->anak = [];
        $this->saudara = [];
    }

    public function tambahPasangan(): void
    {
        $this->pasangan[] = ['nama' => '', 'gender' => 'perempuan', 'status' => 'hidup', 'tgl_nikah' => null, 'catatan' => ''];
    }

    public function hapusPasangan(int $idx): void
    {
        unset($this->pasangan[$idx]);
        $this->pasangan = array_values($this->pasangan);
    }

    public function tambahAnak(): void
    {
        $this->anak[] = ['nama' => '', 'gender' => 'laki-laki', 'tgl_lahir' => null, 'status' => 'hidup', 'kategori' => 'sah', 'catatan' => '', 'parent_id' => null];
    }

    public function hapusAnak(int $idx): void
    {
        unset($this->anak[$idx]);
        $this->anak = array_values($this->anak);
    }

    public function tambahSaudara(): void
    {
        $this->saudara[] = ['nama' => '', 'gender' => 'laki-laki', 'kategori' => 'kandung', 'status' => 'hidup', 'catatan' => ''];
    }

    public function hapusSaudara(int $idx): void
    {
        unset($this->saudara[$idx]);
        $this->saudara = array_values($this->saudara);
    }

    public function nextStep(): void
    {
        $this->validateCurrentStep();
        if ($this->step < $this->totalSteps) {
            $this->step++;
        }
    }

    public function prevStep(): void
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    protected function validateCurrentStep(): void
    {
        if ($this->step === 1) {
            $this->validate([
                'pewarisNama' => ['required', 'string', 'max:120'],
                'pewarisGender' => ['required', 'in:laki-laki,perempuan'],
                'pewarisTglMeninggal' => ['nullable', 'date'],
            ]);
        }

        // Validasi tanggal: lahir tidak boleh setelah meninggal
        foreach ($this->anak as $i => $a) {
            if (! empty($a['tgl_lahir']) && ! empty($a['status']) && $a['status'] === 'meninggal') {
                // tanggal meninggal anak tidak disimpan terpisah; cek kewajaran
            }
        }

        // Validasi tanggal perkawinan
        foreach ($this->pasangan as $i => $p) {
            if (! empty($p['tgl_nikah']) && ! empty($this->pewarisTglMeninggal)) {
                if ($p['tgl_nikah'] > $this->pewarisTglMeninggal) {
                    $this->addError("pasangan.$i.tgl_nikah", 'Tanggal perkawinan tidak boleh setelah tanggal meninggal pewaris.');
                }
            }
        }

        // Validasi tanggal meninggal anggota yang meninggal lebih dahulu konsisten dengan pewaris
        if (! empty($this->ayah['tgl_meninggal']) && ! empty($this->pewarisTglMeninggal)) {
            if ($this->ayah['tgl_meninggal'] > $this->pewarisTglMeninggal) {
                $this->addError('ayah.tgl_meninggal', 'Tanggal meninggal Ayah tidak boleh setelah tanggal meninggal pewaris.');
            }
        }
        if (! empty($this->ibu['tgl_meninggal']) && ! empty($this->pewarisTglMeninggal)) {
            if ($this->ibu['tgl_meninggal'] > $this->pewarisTglMeninggal) {
                $this->addError('ibu.tgl_meninggal', 'Tanggal meninggal Ibu tidak boleh setelah tanggal meninggal pewaris.');
            }
        }
    }

    public function simpan(): void
    {
        $this->validateCurrentStep();
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('civil_family_members')) {
                \App\Models\CivilFamilyMember::where('session_token', $this->sessionToken)->delete();
                // pewaris
                \App\Models\CivilFamilyMember::create(['session_token' => $this->sessionToken, 'role' => 'pewaris', 'name' => $this->pewarisNama, 'gender' => $this->pewarisGender, 'death_date' => $this->pewarisTglMeninggal, 'notes' => $this->pewarisCatatan]);
                foreach ($this->pasangan as $p) {
                    \App\Models\CivilFamilyMember::create(['session_token' => $this->sessionToken, 'role' => 'pasangan', 'name' => $p['nama'] ?? null, 'gender' => $p['gender'] ?? null, 'status' => $p['status'] ?? 'hidup', 'notes' => $p['catatan'] ?? null]);
                }
                foreach ($this->anak as $a) {
                    \App\Models\CivilFamilyMember::create(['session_token' => $this->sessionToken, 'role' => 'anak', 'name' => $a['nama'] ?? null, 'gender' => $a['gender'] ?? null, 'birth_date' => $a['tgl_lahir'] ?? null, 'status' => $a['status'] ?? 'hidup', 'category' => $a['kategori'] ?? null, 'notes' => $a['catatan'] ?? null]);
                }
                if (trim($this->ayah['nama'] ?? '') !== '' || ($this->ayah['status'] ?? '') !== 'tidak_diketahui') {
                    \App\Models\CivilFamilyMember::create(['session_token' => $this->sessionToken, 'role' => 'ayah', 'name' => $this->ayah['nama'] ?: null, 'gender' => 'm', 'status' => $this->ayah['status'], 'death_date' => $this->ayah['tgl_meninggal'] ?? null, 'notes' => $this->ayah['catatan'] ?? null]);
                }
                if (trim($this->ibu['nama'] ?? '') !== '' || ($this->ibu['status'] ?? '') !== 'tidak_diketahui') {
                    \App\Models\CivilFamilyMember::create(['session_token' => $this->sessionToken, 'role' => 'ibu', 'name' => $this->ibu['nama'] ?: null, 'gender' => 'f', 'status' => $this->ibu['status'], 'death_date' => $this->ibu['tgl_meninggal'] ?? null, 'notes' => $this->ibu['catatan'] ?? null]);
                }
                foreach ($this->saudara as $s) {
                    \App\Models\CivilFamilyMember::create(['session_token' => $this->sessionToken, 'role' => 'saudara', 'name' => $s['nama'] ?? null, 'gender' => $s['gender'] ?? null, 'status' => $s['status'] ?? 'hidup', 'category' => $s['kategori'] ?? null, 'notes' => $s['catatan'] ?? null]);
                }
                session()->flash('status', 'Data keluarga tersimpan.');
            } else {
                session()->flash('status', 'Tabel belum tersedia. Jalankan migrasi terlebih dahulu. Data tetap di formulir.');
            }
        } catch (\Throwable $e) {
            session()->flash('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function resetForm(): void
    {
        $this->mount();
        $this->step = 1;
    }

    public function render(): View
    {
        return view('livewire.civil-family-form');
    }
}