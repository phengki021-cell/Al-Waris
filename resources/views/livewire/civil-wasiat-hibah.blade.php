<div class="mx-auto max-w-3xl space-y-6 pb-32">
    @include('partials.back-button')

    <section>
        <p class="u-kicker text-gold-deep">Hukum Waris Perdata</p>
        <h1 class="u-display mt-1.5 text-2xl text-ink md:text-3xl">Wasiat, Hibah, dan Legitime Portie</h1>
        <p class="mt-2 text-sm text-ink/70">
            Pencatatan data wasiat dan hibah semasa hidup. Analisis legitime portie diberikan sebagai informasi, bukan keputusan final.
        </p>
    </section>

    @if (session('status'))
        <div class="rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-900 ring-1 ring-emerald-200">{{ session('status') }}</div>
    @endif
    @if (session('error'))
        <div class="rounded-xl bg-red-50 px-4 py-3 text-sm text-red-900 ring-1 ring-red-200">{{ session('error') }}</div>
    @endif

    <div class="u-smart-card p-5 md:p-6 space-y-5">
        {{-- Wasiat --}}
        <div>
            <h2 class="text-lg font-bold text-ink">Wasiat (Testament)</h2>
            <div class="mt-3 flex items-center gap-2">
                <input type="checkbox" wire:model="hasWill" id="hasWill" class="h-4 w-4 accent-[#123D32]">
                <label for="hasWill" class="text-sm text-ink">Pewaris meninggalkan wasiat</label>
            </div>
            @if ($hasWill)
                <div class="mt-3 space-y-3">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-ink">Tanggal pembuatan wasiat</label>
                        <input type="date" wire:model="willDate" class="u-input py-2.5 px-3 text-sm">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-ink">Bentuk wasiat</label>
                        <select wire:model="willForm" class="u-input py-2.5 px-3 text-sm">
                            <option value="umum">Wasiat umum (notariil)</option>
                            <option value="olografis">Wasiat olografis</option>
                            <option value="tertutup">Wasiat tertutup/rahasia</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-ink">Catatan</label>
                        <textarea wire:model="willNotes" rows="2" class="u-input py-2.5 px-3 text-sm"></textarea>
                    </div>
                </div>
            @endif
        </div>

        {{-- Penerima wasiat --}}
        @if ($hasWill)
            <div class="border-t border-black/5 pt-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-bold text-ink">Penerima Wasiat</h3>
                    <button type="button" wire:click="tambahPenerima" class="u-btn-gold px-3 py-1.5 text-xs">+ Tambah</button>
                </div>
                <div class="mt-3 space-y-3">
                    @foreach ($beneficiaries as $i => $b)
                        <div class="rounded-xl border border-black/5 bg-surface/60 p-3 space-y-2">
                            <div class="flex items-center justify-between">
                                <p class="text-xs font-semibold text-ink">Penerima {{ $i + 1 }}</p>
                                <button type="button" wire:click="hapusPenerima({{ $i }})" class="text-xs text-red-700 hover:underline">Hapus</button>
                            </div>
                            <input type="text" wire:model="beneficiaries.{{ $i }}.name" placeholder="Nama" class="u-input py-2 px-3 text-sm">
                            <input type="text" wire:model="beneficiaries.{{ $i }}.relation" placeholder="Hubungan" class="u-input py-2 px-3 text-sm">
                            <select wire:model="beneficiaries.{{ $i }}.gift_type" class="u-input py-2 px-3 text-sm">
                                <option value="uang">Uang</option>
                                <option value="barang">Barang</option>
                                <option value="hak">Hak</option>
                            </select>
                            <input type="text" wire:model="beneficiaries.{{ $i }}.object" placeholder="Objek pemberian" class="u-input py-2 px-3 text-sm">
                            <input type="number" wire:model="beneficiaries.{{ $i }}.value" placeholder="Nilai (opsional)" class="u-input py-2 px-3 text-sm">
                            <textarea wire:model="beneficiaries.{{ $i }}.notes" rows="2" placeholder="Catatan" class="u-input py-2 px-3 text-sm"></textarea>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Hibah --}}
        <div class="border-t border-black/5 pt-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-ink">Hibah Semasa Hidup</h3>
                <button type="button" wire:click="tambahHibah" class="u-btn-gold px-3 py-1.5 text-xs">+ Tambah Hibah</button>
            </div>
            <p class="mt-1 text-xs text-ink/60">Catat hibah atau pemberian semasa hidup. Tidak semua hibah otomatis diperlakukan sama.</p>
            <div class="mt-3 space-y-3">
                @foreach ($bequests as $i => $b)
                    <div class="rounded-xl border border-black/5 bg-surface/60 p-3 space-y-2">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold text-ink">Hibah {{ $i + 1 }}</p>
                            <button type="button" wire:click="hapusHibah({{ $i }})" class="text-xs text-red-700 hover:underline">Hapus</button>
                        </div>
                        <input type="text" wire:model="bequests.{{ $i }}.donor" placeholder="Pemberi" class="u-input py-2 px-3 text-sm">
                        <input type="text" wire:model="bequests.{{ $i }}.recipient" placeholder="Penerima" class="u-input py-2 px-3 text-sm">
                        <input type="text" wire:model="bequests.{{ $i }}.relation" placeholder="Hubungan" class="u-input py-2 px-3 text-sm">
                        <input type="date" wire:model="bequests.{{ $i }}.given_at" class="u-input py-2 px-3 text-sm">
                        <input type="text" wire:model="bequests.{{ $i }}.object" placeholder="Objek hibah" class="u-input py-2 px-3 text-sm">
                        <input type="text" wire:model="bequests.{{ $i }}.asset_type" placeholder="Jenis aset" class="u-input py-2 px-3 text-sm">
                        <input type="number" wire:model="bequests.{{ $i }}.value" placeholder="Nilai (opsional)" class="u-input py-2 px-3 text-sm">
                        <textarea wire:model="bequests.{{ $i }}.notes" rows="2" placeholder="Catatan" class="u-input py-2 px-3 text-sm"></textarea>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Simpan --}}
        <div class="flex items-center justify-between gap-3 border-t border-black/5 pt-4">
            <button type="button" wire:click="simpan" class="u-btn-gold px-5 py-2.5 text-sm">Simpan</button>
        </div>
    </div>

    {{-- Analisis legitime portie --}}
    @if (! empty($legitimeResult))
        <div class="u-smart-card p-5 md:p-6">
            <h2 class="text-lg font-bold text-ink">Analisis Legitime Portie</h2>
            <p class="mt-1 text-sm text-ink/70">{{ $legitimeResult['catatan'] ?? '' }}</p>
            <div class="mt-2 rounded-xl border border-yellow-300 bg-yellow-50 p-3 text-xs text-yellow-900">
                Status: {{ $legitimeResult['status'] ?? 'NEEDS_REVIEW' }}. Aturan legitime portie belum diverifikasi; tidak ada angka final.
            </div>
        </div>
    @endif

    <p class="text-xs text-ink/50">
        Pencatatan wasiat dan hibah (Tahap 6). Belum ada perhitungan pembagian. Keabsahan wasiat/hibah bergantung pada pemenuhan syarat hukum yang berlaku.
    </p>
</div>
