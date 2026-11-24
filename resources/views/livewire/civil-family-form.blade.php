<div class="mx-auto max-w-3xl space-y-6 pb-32">
    @include('partials.back-button')

    <section>
        <p class="u-kicker text-gold-deep">Hukum Waris Perdata</p>
        <h1 class="u-display mt-1.5 text-2xl text-ink md:text-3xl">Formulir Data Keluarga</h1>
        <p class="mt-2 text-sm text-ink/70">Langkah {{ $step }} dari {{ $totalSteps }}. Pengumpulan data saja, belum ada perhitungan.</p>
    </section>

    {{-- Progress --}}
    <div class="h-2 w-full overflow-hidden rounded-full bg-surface">
        <div class="h-full bg-[#123D32] transition-all" style="width: {{ ($step / $totalSteps) * 100 }}%"></div>
    </div>

    @if (session('status'))
        <div class="rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-900 ring-1 ring-emerald-200">{{ session('status') }}</div>
    @endif
    @if (session('error'))
        <div class="rounded-xl bg-red-50 px-4 py-3 text-sm text-red-900 ring-1 ring-red-200">{{ session('error') }}</div>
    @endif

    <div class="u-smart-card p-5 md:p-6">
        {{-- STEP 1: Pewaris --}}
        @if ($step === 1)
            <h2 class="text-lg font-bold text-ink">Data Pewaris</h2>
            <div class="mt-4 space-y-4">
                <div>
                    <label class="mb-1 block text-sm font-medium text-ink">Nama lengkap (wajib)</label>
                    <input type="text" wire:model="pewarisNama" class="u-input py-2.5 px-3 text-sm">
                    @error('pewarisNama') <p class="mt-1 text-xs text-red-800">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-ink">Jenis kelamin</label>
                    <select wire:model="pewarisGender" class="u-input py-2.5 px-3 text-sm">
                        <option value="laki-laki">Laki-laki</option>
                        <option value="perempuan">Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-ink">Tanggal meninggal dunia (opsional)</label>
                    <input type="date" wire:model="pewarisTglMeninggal" class="u-input py-2.5 px-3 text-sm">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-ink">Tempat meninggal dunia (opsional)</label>
                    <input type="text" wire:model="pewarisTempat" class="u-input py-2.5 px-3 text-sm">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-ink">Status perkawinan</label>
                    <select wire:model="pewarisStatusNikah" class="u-input py-2.5 px-3 text-sm">
                        <option value="belum_kawin">Belum kawin</option>
                        <option value="kawin">Kawin</option>
                        <option value="cerai">Cerai</option>
                        <option value="cerai_mati">Cerai mati</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-ink">Jumlah perkawinan (jika relevan)</label>
                    <input type="number" min="0" wire:model="pewarisJumlahNikah" class="u-input py-2.5 px-3 text-sm">
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" wire:model="pewarisWasiat" id="wasiat" class="h-4 w-4 accent-[#123D32]">
                    <label for="wasiat" class="text-sm text-ink">Meninggalkan wasiat</label>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" wire:model="pewarisHibah" id="hibah" class="h-4 w-4 accent-[#123D32]">
                    <label for="hibah" class="text-sm text-ink">Pernah memberikan hibah semasa hidup</label>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-ink">Informasi harta peninggalan (opsional)</label>
                    <textarea wire:model="pewarisHarta" rows="2" class="u-input py-2.5 px-3 text-sm"></textarea>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-ink">Informasi utang dan kewajiban (opsional)</label>
                    <textarea wire:model="pewarisUtang" rows="2" class="u-input py-2.5 px-3 text-sm"></textarea>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-ink">Catatan tambahan</label>
                    <textarea wire:model="pewarisCatatan" rows="2" class="u-input py-2.5 px-3 text-sm"></textarea>
                </div>
            </div>
        @elseif ($step === 2)
            <h2 class="text-lg font-bold text-ink">Data Pasangan</h2>
            <p class="mt-1 text-xs text-ink/60">Dukung lebih dari satu riwayat perkawinan.</p>
            <div class="mt-4 space-y-4">
                @foreach ($pasangan as $i => $p)
                    <div class="rounded-xl border border-black/5 bg-surface/60 p-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-semibold text-ink">Pasangan {{ $i + 1 }}</p>
                            <button type="button" wire:click="hapusPasangan({{ $i }})" class="text-xs text-red-700 hover:underline">Hapus</button>
                        </div>
                        <input type="text" wire:model="pasangan.{{ $i }}.nama" placeholder="Nama" class="u-input py-2.5 px-3 text-sm">
                        <select wire:model="pasangan.{{ $i }}.gender" class="u-input py-2.5 px-3 text-sm">
                            <option value="laki-laki">Laki-laki</option>
                            <option value="perempuan">Perempuan</option>
                        </select>
                        <select wire:model="pasangan.{{ $i }}.status" class="u-input py-2.5 px-3 text-sm">
                            <option value="hidup">Hidup</option>
                            <option value="meninggal">Meninggal</option>
                        </select>
                        <input type="date" wire:model="pasangan.{{ $i }}.tgl_nikah" class="u-input py-2.5 px-3 text-sm">
                        <textarea wire:model="pasangan.{{ $i }}.catatan" rows="2" placeholder="Catatan" class="u-input py-2.5 px-3 text-sm"></textarea>
                    </div>
                @endforeach
                <button type="button" wire:click="tambahPasangan" class="u-btn-gold px-4 py-2 text-sm">+ Tambah Pasangan</button>
            </div>
        @elseif ($step === 3)
            <h2 class="text-lg font-bold text-ink">Data Anak</h2>
            <div class="mt-4 space-y-4">
                @foreach ($anak as $i => $a)
                    <div class="rounded-xl border border-black/5 bg-surface/60 p-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-semibold text-ink">Anak {{ $i + 1 }}</p>
                            <button type="button" wire:click="hapusAnak({{ $i }})" class="text-xs text-red-700 hover:underline">Hapus</button>
                        </div>
                        <input type="text" wire:model="anak.{{ $i }}.nama" placeholder="Nama" class="u-input py-2.5 px-3 text-sm">
                        <select wire:model="anak.{{ $i }}.gender" class="u-input py-2.5 px-3 text-sm">
                            <option value="laki-laki">Laki-laki</option>
                            <option value="perempuan">Perempuan</option>
                        </select>
                        <input type="date" wire:model="anak.{{ $i }}.tgl_lahir" class="u-input py-2.5 px-3 text-sm">
                        <select wire:model="anak.{{ $i }}.status" class="u-input py-2.5 px-3 text-sm">
                            <option value="hidup">Hidup</option>
                            <option value="meninggal">Meninggal</option>
                        </select>
                        <select wire:model="anak.{{ $i }}.kategori" class="u-input py-2.5 px-3 text-sm">
                            <option value="sah">Anak sah</option>
                            <option value="luar_kawin">Anak luar kawin</option>
                            <option value="angkat">Anak angkat</option>
                        </select>
                        <input type="number" wire:model="anak.{{ $i }}.parent_id" placeholder="ID orang tua (penggantian tempat)" class="u-input py-2.5 px-3 text-sm">
                        <textarea wire:model="anak.{{ $i }}.catatan" rows="2" placeholder="Catatan" class="u-input py-2.5 px-3 text-sm"></textarea>
                    </div>
                @endforeach
                <button type="button" wire:click="tambahAnak" class="u-btn-gold px-4 py-2 text-sm">+ Tambah Anak</button>
            </div>
        @elseif ($step === 4)
            <h2 class="text-lg font-bold text-ink">Data Orang Tua</h2>
            <div class="mt-4 space-y-4">
                <div class="rounded-xl border border-black/5 bg-surface/60 p-4 space-y-3">
                    <p class="text-sm font-semibold text-ink">Ayah</p>
                    <input type="text" wire:model="ayah.nama" placeholder="Nama" class="u-input py-2.5 px-3 text-sm">
                    <select wire:model="ayah.status" class="u-input py-2.5 px-3 text-sm">
                        <option value="hidup">Hidup</option>
                        <option value="meninggal">Meninggal</option>
                        <option value="tidak_diketahui">Belum diketahui</option>
                    </select>
                    <input type="date" wire:model="ayah.tgl_meninggal" class="u-input py-2.5 px-3 text-sm">
                    <textarea wire:model="ayah.catatan" rows="2" placeholder="Catatan" class="u-input py-2.5 px-3 text-sm"></textarea>
                </div>
                <div class="rounded-xl border border-black/5 bg-surface/60 p-4 space-y-3">
                    <p class="text-sm font-semibold text-ink">Ibu</p>
                    <input type="text" wire:model="ibu.nama" placeholder="Nama" class="u-input py-2.5 px-3 text-sm">
                    <select wire:model="ibu.status" class="u-input py-2.5 px-3 text-sm">
                        <option value="hidup">Hidup</option>
                        <option value="meninggal">Meninggal</option>
                        <option value="tidak_diketahui">Belum diketahui</option>
                    </select>
                    <input type="date" wire:model="ibu.tgl_meninggal" class="u-input py-2.5 px-3 text-sm">
                    <textarea wire:model="ibu.catatan" rows="2" placeholder="Catatan" class="u-input py-2.5 px-3 text-sm"></textarea>
                </div>
            </div>
        @elseif ($step === 5)
            <h2 class="text-lg font-bold text-ink">Data Saudara</h2>
            <div class="mt-4 space-y-4">
                @foreach ($saudara as $i => $s)
                    <div class="rounded-xl border border-black/5 bg-surface/60 p-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-semibold text-ink">Saudara {{ $i + 1 }}</p>
                            <button type="button" wire:click="hapusSaudara({{ $i }})" class="text-xs text-red-700 hover:underline">Hapus</button>
                        </div>
                        <input type="text" wire:model="saudara.{{ $i }}.nama" placeholder="Nama" class="u-input py-2.5 px-3 text-sm">
                        <select wire:model="saudara.{{ $i }}.gender" class="u-input py-2.5 px-3 text-sm">
                            <option value="laki-laki">Laki-laki</option>
                            <option value="perempuan">Perempuan</option>
                        </select>
                        <select wire:model="saudara.{{ $i }}.kategori" class="u-input py-2.5 px-3 text-sm">
                            <option value="kandung">Saudara kandung</option>
                            <option value="seayah">Saudara seayah</option>
                            <option value="seibu">Saudara seibu</option>
                        </select>
                        <select wire:model="saudara.{{ $i }}.status" class="u-input py-2.5 px-3 text-sm">
                            <option value="hidup">Hidup</option>
                            <option value="meninggal">Meninggal</option>
                        </select>
                        <textarea wire:model="saudara.{{ $i }}.catatan" rows="2" placeholder="Catatan" class="u-input py-2.5 px-3 text-sm"></textarea>
                    </div>
                @endforeach
                <button type="button" wire:click="tambahSaudara" class="u-btn-gold px-4 py-2 text-sm">+ Tambah Saudara</button>
            </div>
        @elseif ($step === 6)
            <h2 class="text-lg font-bold text-ink">Penggantian Tempat (Plaatsvervulling)</h2>
            <p class="mt-1 text-xs text-ink/60">Tandai anggota keluarga yang telah meninggal lebih dahulu dan keturunannya (cucu) yang menggantikan kedudukannya.</p>
            <div class="mt-4 space-y-2 text-sm text-ink/80">
                <p>Anggota yang meninggal lebih dahulu:</p>
                <ul class="list-disc pl-5">
                    @foreach ($anak as $a)
                        @if (($a['status'] ?? '') === 'meninggal')
                            <li>{{ $a['nama'] ?: 'Anak' }} — ID penggantian: isi "ID orang tua" pada data cucu.</li>
                        @endif
                    @endforeach
                </ul>
                <p class="mt-2 text-xs text-ink/50">Kelayakan penggantian tempat diperiksa pada tahap berikutnya (mesin hukum belum tersedia).</p>
            </div>
        @elseif ($step === 7)
            <h2 class="text-lg font-bold text-ink">Ringkasan Keluarga</h2>
            <div class="mt-4 space-y-3 text-sm text-ink/80">
                <div class="rounded-xl bg-surface/60 p-3">
                    <p class="font-semibold text-ink">Pewaris</p>
                    <p>{{ $pewarisNama ?: '—' }} ({{ $pewarisGender }})</p>
                </div>
                <div class="rounded-xl bg-surface/60 p-3">
                    <p class="font-semibold text-ink">Pasangan ({{ count($pasangan) }})</p>
                    <ul class="list-disc pl-5">
                        @foreach ($pasangan as $p) <li>{{ $p['nama'] ?: '—' }} ({{ $p['status'] }})</li> @endforeach
                    </ul>
                </div>
                <div class="rounded-xl bg-surface/60 p-3">
                    <p class="font-semibold text-ink">Anak ({{ count($anak) }})</p>
                    <ul class="list-disc pl-5">
                        @foreach ($anak as $a) <li>{{ $a['nama'] ?: '—' }} ({{ $a['gender'] }}, {{ $a['status'] }})</li> @endforeach
                    </ul>
                </div>
                <div class="rounded-xl bg-surface/60 p-3">
                    <p class="font-semibold text-ink">Orang tua</p>
                    <p>Ayah: {{ $ayah['nama'] ?: '—' }} ({{ $ayah['status'] }})</p>
                    <p>Ibu: {{ $ibu['nama'] ?: '—' }} ({{ $ibu['status'] }})</p>
                </div>
                <div class="rounded-xl bg-surface/60 p-3">
                    <p class="font-semibold text-ink">Saudara ({{ count($saudara) }})</p>
                    <ul class="list-disc pl-5">
                        @foreach ($saudara as $s) <li>{{ $s['nama'] ?: '—' }} ({{ $s['kategori'] }}, {{ $s['status'] }})</li> @endforeach
                    </ul>
                </div>
            </div>
        @elseif ($step === 8)
            <h2 class="text-lg font-bold text-ink">Diagram Silsilah Keluarga</h2>
            <p class="mt-1 text-xs text-ink/60">Visualisasi hubungan keluarga. Penggantian tempat ditandai dengan garis putus-putus.</p>
            <div class="mt-4 overflow-x-auto rounded-xl border border-black/5 bg-surface/40 p-4">
                <div class="flex flex-col items-center gap-2 text-sm">
                    <div class="rounded-lg bg-[#123D32] px-3 py-1.5 text-ivory font-semibold">{{ $pewarisNama ?: 'Pewaris' }}</div>
                    @if (count($pasangan) > 0)
                        <div class="flex gap-2">
                            @foreach ($pasangan as $p)
                                <div class="rounded-lg bg-gold-soft px-2 py-1 text-xs text-gold-deep">{{ $p['nama'] ?: 'Pasangan' }}</div>
                            @endforeach
                        </div>
                    @endif
                    @if (count($anak) > 0)
                        <div class="flex flex-wrap justify-center gap-2">
                            @foreach ($anak as $a)
                                <div class="rounded-lg bg-emerald-100 px-2 py-1 text-xs text-emerald-900 {{ ($a['status'] ?? '') === 'meninggal' ? 'ring-2 ring-red-300' : '' }}">{{ $a['nama'] ?: 'Anak' }}</div>
                            @endforeach
                        </div>
                    @endif
                    <div class="flex gap-4">
                        <div class="rounded-lg bg-surface px-2 py-1 text-xs text-ink/70">Ayah: {{ $ayah['nama'] ?: '—' }}</div>
                        <div class="rounded-lg bg-surface px-2 py-1 text-xs text-ink/70">Ibu: {{ $ibu['nama'] ?: '—' }}</div>
                    </div>
                    @if (count($saudara) > 0)
                        <div class="flex flex-wrap justify-center gap-2">
                            @foreach ($saudara as $s)
                                <div class="rounded-lg bg-surface px-2 py-1 text-xs text-ink/70">{{ $s['nama'] ?: 'Saudara' }}</div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <p class="mt-3 text-xs text-ink/50">Jika diagram terlalu kompleks untuk layar HP, daftar hubungan di atas dapat digunakan sebagai alternatif.</p>
            </div>
        @endif
    </div>

    <div class="flex items-center justify-between gap-3">
        @if ($step > 1)
            <button type="button" wire:click="prevStep" class="rounded-xl border border-charcoal/15 px-4 py-2.5 text-sm font-medium text-charcoal transition hover:bg-gold-soft/40">Kembali</button>
        @else
            <span></span>
        @endif
        <div class="flex gap-2">
            <button type="button" wire:click="simpan" class="rounded-xl border border-[#123D32] px-4 py-2.5 text-sm font-medium text-[#123D32] transition hover:bg-emerald-50">Simpan</button>
            @if ($step < $totalSteps)
                <button type="button" wire:click="nextStep" class="u-btn-gold px-5 py-2.5 text-sm">Lanjut</button>
            @else
                <button type="button" wire:click="resetForm" class="rounded-xl border border-charcoal/15 px-4 py-2.5 text-sm font-medium text-charcoal transition hover:bg-gold-soft/40">Isi Ulang</button>
            @endif
        </div>
    </div>

    <p class="text-xs text-ink/50">
        Catatan: Formulir ini hanya mengumpulkan data keluarga (Tahap 4). Belum ada perhitungan waris.
    </p>
</div>
