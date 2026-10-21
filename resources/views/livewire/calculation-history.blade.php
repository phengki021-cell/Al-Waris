<div class="mx-auto max-w-4xl">
    <h1 class="text-2xl font-bold md:text-3xl">Riwayat Perhitungan</h1>

    <div class="mt-6">
        @if ($calculations->isNotEmpty())
            {{-- Tahap 3: daftar Calculation --}}
        @else
            <div class="rounded-xl bg-paper p-10 text-center shadow-sm ring-1 ring-black/5">
                <p class="text-4xl">🗂️</p>
                <p class="mt-3 font-semibold">Belum ada riwayat yang tersimpan</p>
                <p class="mt-1 text-sm text-ink/60">
                    Fitur penyimpanan riwayat tersedia setelah Tahap 2. Untuk saat ini,
                    hasil perhitungan di kalkulator <strong>tidak disimpan</strong>.
                </p>
                <a href="{{ route('calculate') }}"
                   class="mt-5 inline-block rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white hover:bg-primary-700">
                    Buka Kalkulator
                </a>
            </div>
        @endif
    </div>
</div>
