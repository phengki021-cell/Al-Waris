<div class="mx-auto max-w-4xl">
    <section>
        <h1 class="text-2xl font-bold md:text-3xl">Hitung Warisan Otomatis</h1>
        <p class="mt-2 text-sm text-ink/70">
            <strong>Pratinjau Tahap 1:</strong> mesin ini hanya mendukung beberapa kasus dasar; hasil berlabel DEMO.
            Kasus 'aul, radd, dan gawalan datang pada Tahap 2.
        </p>
    </section>

    <form wire:submit="calculate" class="mt-6 rounded-xl bg-paper p-5 shadow-sm ring-1 ring-black/5">
        {{-- Harta --}}
        <fieldset>
            <legend class="text-sm font-semibold text-primary">1. Harta</legend>
            <div class="mt-3 grid gap-4 sm:grid-cols-3">
                <div>
                    <label for="estateTotal" class="block text-xs font-medium">Total harta (Rp)</label>
                    <input id="estateTotal" type="number" step="0.01" min="0" wire:model="estateTotal"
                           class="mt-1 w-full rounded-lg border border-ink/20 px-3 py-2 text-sm focus:border-accent focus:ring-2 focus:ring-accent/40"
                           placeholder="0.00">
                    @error('estateTotal') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="debtsTotal" class="block text-xs font-medium">Hutang (Rp)</label>
                    <input id="debtsTotal" type="number" step="0.01" min="0" wire:model="debtsTotal"
                           class="mt-1 w-full rounded-lg border border-ink/20 px-3 py-2 text-sm focus:border-accent focus:ring-2 focus:ring-accent/40"
                           placeholder="0.00">
                </div>
                <div>
                    <label for="bequestsTotal" class="block text-xs font-medium">Wasiat (Rp, maks 1/3 harta — divalidasi Tahap 2)</label>
                    <input id="bequestsTotal" type="number" step="0.01" min="0" wire:model="bequestsTotal"
                           class="mt-1 w-full rounded-lg border border-ink/20 px-3 py-2 text-sm focus:border-accent focus:ring-2 focus:ring-accent/40"
                           placeholder="0.00">
                </div>
            </div>
        </fieldset>

        {{-- Ahli waris --}}
        <fieldset class="mt-6">
            <legend class="text-sm font-semibold text-primary">2. Ahli waris</legend>
            <div class="mt-3 grid gap-3 sm:grid-cols-2 md:grid-cols-4">
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" wire:model="hasHusband" class="h-4 w-4 accent-primary">
                    Suami
                </label>
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" wire:model="hasWife" class="h-4 w-4 accent-primary">
                    Istri
                </label>
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" wire:model="hasFather" class="h-4 w-4 accent-primary">
                    Ayah
                </label>
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" wire:model="hasMother" class="h-4 w-4 accent-primary">
                    Ibu
                </label>
                <label class="flex items-center gap-2 text-sm">
                    Anak laki-laki
                    <input type="number" min="0" max="30" wire:model="sonsCount"
                           class="w-16 rounded-lg border border-ink/20 px-2 py-1 text-sm" aria-label="Jumlah anak laki-laki">
                </label>
                <label class="flex items-center gap-2 text-sm">
                    Anak perempuan
                    <input type="number" min="0" max="30" wire:model="daughtersCount"
                           class="w-16 rounded-lg border border-ink/20 px-2 py-1 text-sm" aria-label="Jumlah anak perempuan">
                </label>
            </div>
            @error('sonsCount')<p class="mt-2 text-xs text-red-600">Jumlah anak laki-laki tidak sah (0–30).</p>@enderror
            @error('daughtersCount')<p class="mt-2 text-xs text-red-600">Jumlah anak perempuan tidak sah (0–30).</p>@enderror
        </fieldset>

        <div class="mt-6 flex flex-wrap gap-3">
            <button type="submit" class="rounded-lg bg-primary px-6 py-3 text-sm font-semibold text-white shadow transition hover:bg-primary-700">
                Hitung Sekarang
            </button>
            <button type="button" wire:click="resetAll" class="rounded-lg border border-ink/20 px-6 py-3 text-sm font-medium transition hover:bg-surface">
                Reset
            </button>
        </div>
    </form>

    {{-- Hasil --}}
    <section class="mt-8" aria-live="polite">
        @if ($result)
            @if ($result['status'] === 'valid')
                <div class="rounded-xl bg-paper p-5 shadow ring-1 ring-black/5">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <h2 class="font-semibold text-primary">Hasil Pembagian <span class="text-xs rounded bg-accent-soft text-primary px-2 py-0.5">AMAL: {{ $result['method'] }}</span></h2>
                    </div>

                    <table class="mt-4 w-full text-sm">
                        <thead>
                            <tr class="border-b text-left text-xs uppercase text-ink/60">
                                <th class="py-2">Ahli waris</th>
                                <th class="py-2">Bagian</th>
                                <th class="py-2 text-right">Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($result['shares'] as $share)
                                <tr class="border-b last:border-0">
                                    <td class="py-2">{{ $share['name'] }}</td>
                                    <td class="py-2 font-mono">{{ $share['text'] }} <span class="text-[10px] text-ink/40">({{ $share['n'] }}/{{ $share['d'] }})</span></td>
                                    <td class="py-2 text-right">
                                        @if ($share['amount'] !== null)
                                            Rp {{ number_format($share['amount'], 2, ',', '.') }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <p class="mt-4 rounded-lg bg-accent-soft p-3 text-xs text-ink/80">
                        ⚠️ {{ $result['note'] }} Hasil ini hanya <strong>simulasi</strong> — bukan fatwa atau
                        keputusan hukum. Silakan konsultasikan dengan ahli faraid.
                    </p>
                </div>
            @else
                <div class="rounded-xl border border-yellow-300 bg-yellow-50 p-5 text-sm text-ink/80">
                    <p class="font-semibold">Kombinasi belum didukung.</p>
                    <p class="mt-1">{{ $result['note'] }}</p>
                </div>
            @endif
        @else
            <div class="rounded-xl bg-paper p-8 text-center text-sm text-ink/50 shadow-sm ring-1 ring-black/5">
                Isi form di atas lalu klik <strong>Hitung Sekarang</strong>. Hasil akan muncul di sini.
            </div>
        @endif
    </section>

    <p class="mt-6 text-xs text-ink/50">
        Pesan: hasil perhitungan ini <strong>tidak disimpan</strong> di Tahap 1. Riwayat penyimpanan tersedia setelah Tahap 2–3.
    </p>
</div>
