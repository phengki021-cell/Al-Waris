<div class="mx-auto max-w-4xl">
    <h1 class="text-2xl font-bold md:text-3xl">Hitung Warisan Otomatis</h1>
    <p class="mt-2 text-sm text-ink/70">
        Alat <strong>edukasi &amp; simulasi</strong> faraid — bukan pengganti ahli faraid atau nasihat hukum.
        Hasil berlabel <strong>DEMO</strong> sampai validasi ahli faraid selesai.
    </p>

    <form wire:submit="calculate" class="mt-6 rounded-xl bg-paper p-5 shadow-sm ring-1 ring-black/5">
        {{-- 1. Harta & kewajiban --}}
        <fieldset>
            <legend class="text-sm font-semibold text-primary">1. Harta &amp; kewajiban (Rp)</legend>
            <div class="mt-3 grid gap-4 sm:grid-cols-2 md:grid-cols-4">
                <div>
                    <label for="estate" class="block text-xs font-medium">Total harta peninggalan</label>
                    <input id="estate" type="number" step="0.01" min="0" wire:model="estate"
                        class="mt-1 w-full rounded-lg border border-ink/20 px-3 py-2 text-sm"
                        placeholder="mis. 500000000">
                    @error('estate')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="debts" class="block text-xs font-medium">Hutang almarhum</label>
                    <input id="debts" type="number" step="0.01" min="0" wire:model="debts"
                        class="mt-1 w-full rounded-lg border border-ink/20 px-3 py-2 text-sm" placeholder="0.00">
                    @error('debts')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="funeral" class="block text-xs font-medium">Biaya pengurusan jenazah</label>
                    <input id="funeral" type="number" step="0.01" min="0" wire:model="funeral"
                        class="mt-1 w-full rounded-lg border border-ink/20 px-3 py-2 text-sm" placeholder="0.00">
                    @error('funeral')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="bequest" class="block text-xs font-medium">Wasiat (maks 1/3 — divalidasi)</label>
                    <input id="bequest" type="number" step="0.01" min="0" wire:model="bequest"
                        class="mt-1 w-full rounded-lg border border-ink/20 px-3 py-2 text-sm" placeholder="0.00">
                    @error('bequest')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
            <p class="mt-2 text-xs text-ink/60">
                Urutan kewajiban: biaya jenazah → hutang → wasiat (≤ 1/3 dari sisa). Pastikan nominal
                dan pemisahan harta (harta bersama, milik pihak lain) sudah diperiksa sesuai kondisi hukum.
            </p>
        </fieldset>

        {{-- 2. Ahli waris --}}
        <fieldset class="mt-6">
            <legend class="text-sm font-semibold text-primary">2. Ahli waris yang masih hidup</legend>
            <div class="mt-3 grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="spouse" class="block text-xs font-medium">Pasangan</label>
                    <select id="spouse" wire:model.live="spouse"
                        class="mt-1 w-full rounded-lg border border-ink/20 px-3 py-2 text-sm">
                        <option value="">Tidak ada pasangan</option>
                        <option value="husband">Suami</option>
                        <option value="wife">Istri</option>
                    </select>
                </div>
                @if ($spouse === 'wife')
                    <div>
                        <label for="wifeCount" class="block text-xs font-medium">Jumlah istri (1–4)</label>
                        <input id="wifeCount" type="number" min="1" max="4" wire:model.live="wifeCount"
                            class="mt-1 w-24 rounded-lg border border-ink/20 px-3 py-2 text-sm">
                        @error('wifeCount')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                @endif
            </div>

            <div class="mt-4 grid gap-4 sm:grid-cols-3 md:grid-cols-4">
                <div>
                    <label for="sons" class="block text-xs font-medium">Anak laki-laki</label>
                    <input id="sons" type="number" min="0" max="30" wire:model="sons"
                        class="mt-1 w-20 rounded-lg border border-ink/20 px-2 py-2 text-sm">
                    @error('sons')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="daughters" class="block text-xs font-medium">Anak perempuan</label>
                    <input id="daughters" type="number" min="0" max="30" wire:model="daughters"
                        class="mt-1 w-20 rounded-lg border border-ink/20 px-2 py-2 text-sm">
                    @error('daughters')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="brothers" class="block text-xs font-medium">Saudara sedarah laki-laki</label>
                    <input id="brothers" type="number" min="0" max="30" wire:model="brothers"
                        class="mt-1 w-20 rounded-lg border border-ink/20 px-2 py-2 text-sm">
                    @error('brothers')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="sisters" class="block text-xs font-medium">Saudara sedarah perempuan</label>
                    <input id="sisters" type="number" min="0" max="30" wire:model="sisters"
                        class="mt-1 w-20 rounded-lg border border-ink/20 px-2 py-2 text-sm">
                    @error('sisters')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mt-4 flex flex-wrap gap-x-6 gap-y-2 text-sm">
                <label class="flex items-center gap-2">
                    <input type="checkbox" wire:model="father" class="h-4 w-4 accent-primary"> Ayah
                </label>
                <label class="flex items-center gap-2">
                    <input type="checkbox" wire:model="mother" class="h-4 w-4 accent-primary"> Ibu
                </label>
                <label class="flex items-center gap-2">
                    <input type="checkbox" wire:model="maternalGrandmother" class="h-4 w-4 accent-primary">
                    Nenek dari ibu
                </label>
            </div>
        </fieldset>

        <div class="mt-6 flex flex-wrap gap-3">
            <button type="submit" class="rounded-lg bg-primary px-6 py-3 text-sm font-semibold text-white shadow transition hover:bg-primary-700">
                Hitung Sekarang
            </button>
            <button type="button" wire:click="resetAll" wire:loading.attr="disabled"
                class="rounded-lg border border-ink/20 px-6 py-3 text-sm font-medium transition hover:bg-surface">
                Reset
            </button>
        </div>

        <div wire:loading wire:target="calculate" class="mt-3 text-sm text-ink/60">
            Menghitung…
        </div>
    </form>

    {{-- ===== Hasil ===== --}}
    <section class="mt-8" aria-live="polite">
        @if (session()->has('message'))
            <p class="mb-4 rounded-lg bg-green-50 px-4 py-2 text-sm text-green-800 ring-1 ring-green-200">
                {{ session('message') }}
            </p>
        @endif

        @if ($result)
            @php $netCents = (int) round($result['net_estate'] * 100); @endphp

            @if ($result['status'] === 'valid')
                <div class="rounded-xl bg-paper p-5 shadow ring-1 ring-black/5">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <h2 class="font-semibold text-primary">Hasil Pembagian</h2>
                        <span class="rounded bg-accent/20 px-2 py-0.5 text-xs font-medium text-primary">
                            DEMO — verifikasi ahli faraid
                        </span>
                    </div>
                    <p class="mt-1 text-xs text-ink/60">
                        Metode: <strong>{{ $result['method_label'] }}</strong>
                        · Versi mesin: v{{ $result['engine_version'] }}
                    </p>

                    {{-- Harta awal → pengurang → bersih --}}
                    <dl class="mt-4 grid gap-3 text-sm sm:grid-cols-2 lg:grid-cols-4">
                        <div class="rounded-lg bg-surface p-3 ring-1 ring-black/5">
                            <dt class="text-xs text-ink/60">Harta awal</dt>
                            <dd class="font-semibold">Rp {{ number_format($result['inputs']['estate'], 2, ',', '.') }}</dd>
                        </div>
                        <div class="rounded-lg bg-surface p-3 ring-1 ring-black/5">
                            <dt class="text-xs text-ink/60">Pengurang (jenazah + hutang + wasiat)</dt>
                            <dd class="font-semibold">
                                Rp {{ number_format($result['inputs']['funeral'] + $result['inputs']['debts'] + $result['inputs']['bequest'], 2, ',', '.') }}
                            </dd>
                        </div>
                        <div class="rounded-lg bg-surface p-3 ring-1 ring-black/5">
                            <dt class="text-xs text-ink/60">Harta bersih dibagi</dt>
                            <dd class="font-semibold">Rp {{ number_format($result['net_estate'], 2, ',', '.') }}</dd>
                        </div>
                        <div class="rounded-lg p-3 ring-1 {{ $result['total_cents'] === $netCents ? 'bg-green-50 ring-green-200' : 'bg-red-50 ring-red-200' }}">
                            <dt class="text-xs text-ink/60">Konsistensi total</dt>
                            <dd class="font-semibold {{ $result['total_cents'] === $netCents ? 'text-green-700' : 'text-red-700' }}">
                                {{ $result['total_cents'] === $netCents ? '✓ konsisten' : '✗ tidak konsisten' }}
                            </dd>
                        </div>
                    </dl>

                    {{-- Berhak --}}
                    <h3 class="mt-6 font-semibold">Ahli waris berhak</h3>
                    <div class="mt-2 overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b text-left text-xs uppercase text-ink/60">
                                    <th class="py-2">Ahli waris</th>
                                    <th class="py-2">Pecahan</th>
                                    <th class="py-2 text-right">%</th>
                                    <th class="py-2 text-right">Total (Rp)</th>
                                    <th class="py-2 text-right">Per orang (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($result['shares'] as $share)
                                    <tr class="border-b last:border-0 align-top">
                                        <td class="py-2">
                                            {{ $share['name'] }}
                                            @if ($share['count'] > 1)
                                                <span class="text-xs text-ink/60">(×{{ $share['count'] }})</span>
                                            @endif
                                            @if ($share['disputed'])
                                                <span class="ml-1 rounded bg-yellow-100 px-1.5 py-0.5 text-[10px] text-yellow-800">beda pendapat</span>
                                            @endif
                                        </td>
                                        <td class="py-2 font-mono">
                                            {{ $share['text'] }}
                                            @if ($share['per_person_text'] !== null)
                                                <span class="text-[10px] text-ink/40">· per orang {{ $share['per_person_text'] }}</span>
                                            @endif
                                        </td>
                                        <td class="py-2 text-right text-xs text-ink/70">
                                            {{ number_format($share['percent'], 4, ',', '.') }}%
                                        </td>
                                        <td class="py-2 text-right">Rp {{ number_format($share['total_cents'] / 100, 2, ',', '.') }}</td>
                                        <td class="py-2 text-right text-xs">Rp {{ number_format($share['per_person_cents'] / 100, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="border-t font-semibold">
                                    <td class="py-2" colspan="3">Total = harta bersih</td>
                                    <td class="py-2 text-right">Rp {{ number_format($result['total_cents'] / 100, 2, ',', '.') }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    {{-- Terhalang --}}
                    @if ($result['blocked'] !== [])
                        <h3 class="mt-6 font-semibold">Ahli waris terhalang (hijab)</h3>
                        <ul class="mt-2 space-y-2 text-sm">
                            @foreach ($result['blocked'] as $blocked)
                                <li class="rounded-lg bg-red-50 p-3 text-ink/80 ring-1 ring-red-100">
                                    <strong>{{ $blocked['name'] }}</strong> — {{ $blocked['reason'] }}
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    {{-- Langkah perhitungan --}}
                    <details class="mt-6">
                        <summary class="cursor-pointer font-semibold">Langkah perhitungan (jejak audit)</summary>
                        <ol class="mt-2 list-decimal space-y-1 pl-5 text-sm text-ink/80">
                            @foreach ($result['trace'] as $step)
                                <li class="pl-1">{{ $step }}</li>
                            @endforeach
                        </ol>
                    </details>

                    {{-- Catatan + pembulatan --}}
                    <div class="mt-4 space-y-2 text-xs text-ink/80">
                        @foreach ($result['notes'] as $note)
                            <p class="rounded-lg bg-accent-soft p-3">ℹ️ {{ $note }}</p>
                        @endforeach
                    </div>

                    @if ($result['disputed'])
                        <p class="mt-3 rounded-lg border border-yellow-300 bg-yellow-50 p-3 text-xs text-yellow-900">
                            ⚠️ Komposisi ini memuat <strong>beda pendapat fikih</strong>. Kelompok lain yang sah
                            dapat menghasilkan angka berbeda.
                        </p>
                    @endif

                    {{-- Simpan ke riwayat --}}
                    <div class="mt-5 border-t pt-4">
                        <button type="button" wire:click="saveToHistory" wire:loading.attr="disabled"
                            class="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow transition hover:bg-primary-700 disabled:opacity-50">
                            Simpan ke Riwayat
                        </button>
                        <p class="mt-2 text-xs text-ink/50">
                            Riwayat disimpan anonim menggunakan sesi peramban ini dan bisa Anda hapus di halaman Riwayat.
                        </p>
                    </div>
                </div>
            @else
                <div class="rounded-xl border border-yellow-300 bg-yellow-50 p-5 text-sm text-ink/80">
                    <p class="font-semibold">
                        {{ $result['status'] === 'not_supported' ? 'Kombinasi belum didukung.' : 'Perhitungan tidak dapat dilanjutkan.' }}
                    </p>
                    <ul class="mt-2 list-disc pl-5">
                        @foreach ($result['notes'] as $note)
                            <li>{{ $note }}</li>
                        @endforeach
                    </ul>
                    @if (count($result['trace']) > 0)
                        <details class="mt-3">
                            <summary class="cursor-pointer text-xs">Lihat jejak perhitungan</summary>
                            <ol class="mt-2 list-decimal space-y-1 pl-5 text-xs">
                                @foreach ($result['trace'] as $step)
                                    <li>{{ $step }}</li>
                                @endforeach
                            </ol>
                        </details>
                    @endif
                    <p class="mt-3 text-xs text-ink/60">
                        Versi mesin: v{{ $result['engine_version'] }}. Komposisi di luar cakupan ditolak secara sengaja
                        agar tidak menghasilkan angka yang menyesatkan — silakan lakukan hitung manual bersama ahli faraid.
                    </p>
                </div>
            @endif
        @else
            <div class="rounded-xl bg-paper p-8 text-center text-sm text-ink/50 shadow-sm ring-1 ring-black/5">
                Isi form di atas lalu klik <strong>Hitung Sekarang</strong>. Hasil akan muncul di sini.
            </div>
        @endif
    </section>
</div>
