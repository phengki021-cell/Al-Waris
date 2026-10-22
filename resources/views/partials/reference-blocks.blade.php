{{-- =========================================================
     Blok rujukan tambahan untuk modal edukasi:
     1) Hadis shahih  2) KUHPerdata (mode perdata)  3) Yurisprudensi MA RI
     4) Baca Jurnal Ilmiah Terkait (link eksternal tab baru)
     Variabel (semua boleh null/kosong): $hadits, $yurisprudensi, $jurnal, $kuhperdata
     ========================================================= --}}
@if (! empty($hadits) && ! empty($hadits['primary'] ?? null))
    <section>
        <h2 class="u-sec-title">Hadis Shahih Terkait Warisan</h2>
        @php $h = $hadits['primary']; @endphp
        <p class="u-chip mt-2">
            {{ $h['label'] ?? '' }}
        </p>
        <p dir="rtl" lang="ar" class="verse-arab u-verse-box mt-3 p-5 text-right text-emerald-950">
            {{ $h['arab'] ?? '' }}
        </p>
        <p class="mt-3 rounded-2xl border border-emerald-900/10 bg-surface p-4 text-sm italic text-ink/80">
            {{ $h['latin'] ?? '' }}
            <span class="mt-2 block text-[10px] not-italic text-ink/40">
                Nota transliterasi: tanda (in)/(un) adalah harakat akhir bacaan.
            </span>
        </p>
        <div class="u-verse-terjemah mt-3 rounded-r-xl bg-surface p-4 ring-1 ring-emerald-900/10">
            <p class="text-sm text-ink/80">{{ $h['terjemah'] ?? '' }}</p>
            <p class="mt-2 text-[10px] text-ink/40">{{ $h['sumber'] ?? '' }}</p>
            @if (! empty($h['kriteria'] ?? null))
                <p class="mt-2 inline-flex rounded-lg bg-gold-soft/70 px-2 py-1 text-xs font-semibold text-gold-deep ring-1 ring-gold/40">{{ $h['kriteria'] }}</p>
            @endif
        </div>
        @if (! empty($hadits['rumusan']))
            <h3 class="mt-3 text-sm font-bold text-emerald-900">Kaidah terkait</h3>
            <ul class="mt-1 list-inside list-disc space-y-1 text-sm text-ink/80">
                @foreach ($hadits['rumusan'] as $rumusan)
                    <li>{{ $rumusan }}</li>
                @endforeach
            </ul>
        @endif
    </section>
@endif

@if (! empty($kuhperdata) && ! empty($kuhperdata['pasal'] ?? null))
    <section>
        <h2 class="u-sec-title">
            Pasal KUHPerdata — Mode Hukum Waris Perdata / Non-Muslim
        </h2>
        <p class="mt-2 rounded-2xl border border-dashed border-black/10 bg-surface p-4 text-xs text-ink/70">
            {{ $kuhperdata['keterangan'] ?? '' }}
        </p>
        <ul class="mt-2 space-y-2">
            @foreach ($kuhperdata['pasal'] as $pasal)
                <li class="u-pasal-card p-4">
                    <p class="text-sm font-extrabold uppercase tracking-wide text-emerald-900">{{ $pasal['label'] }}</p>
                    <p class="mt-1 text-sm text-ink/80">{{ $pasal['teks'] }}</p>
                    @if (! empty($pasal['catatan']))
                        <p class="mt-2 text-xs italic text-ink/60">{{ $pasal['catatan'] }}</p>
                    @endif
                </li>
            @endforeach
        </ul>
        @if (! empty($kuhperdata['golongan']))
            <details class="mt-2 rounded-2xl border border-emerald-900/10 bg-surface p-4">
                <summary class="cursor-pointer text-xs font-bold text-emerald-900">Penggolongan ahli waris KUHPerdata (Golongan I–IV)</summary>
                <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-ink/80">
                    @foreach ($kuhperdata['golongan'] as $golongan)
                        <li>{{ $golongan }}</li>
                    @endforeach
                </ul>
            </details>
        @endif
        <p class="mt-2 inline-flex rounded-xl bg-gold-soft/70 px-3 py-1.5 text-xs font-medium text-amber-900 ring-1 ring-gold/40">
            Mode kalkulator Hukum Waris Perdata tersedia pada tab KUHPerdata di halaman Hitung Warisan — mode Faraidh Islam utama tidak berubah.
        </p>
    </section>
@endif

@if (! empty($yurisprudensi))
    <section>
        <h2 class="u-sec-title">Yurisprudensi Mahkamah Agung RI</h2>
        <ul class="mt-2 space-y-2">
            @foreach ($yurisprudensi as $y)
                <li class="u-pasal-card p-4">
                    <p class="text-sm font-extrabold uppercase tracking-wide text-emerald-900">{{ $y['label'] }}</p>
                    @if (! empty($y['pasal_tautan']))
                        <p class="mt-1 text-[10px] tracking-wide uppercase text-ink/40">{{ $y['pasal_tautan'] }}</p>
                    @endif
                    <p class="mt-1 text-sm text-ink/80">{{ $y['ringkas'] }}</p>
                    @if (! empty($y['link']))
                        <p class="mt-2.5 flex flex-wrap items-center gap-2">
                            <a href="{{ $y['link'] }}" target="_blank" rel="noopener noreferrer" class="u-ref-btn">
                                {{ $y['link_label'] ?? 'Buka sumber resmi MA' }}
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="inline h-3.5 w-3.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7m0 0h-6m6 0v6"/></svg>
                            </a>
                            <span class="text-[10px] text-ink/40">{{ $y['sumber'] ?? '' }} — tautan luar</span>
                        </p>
                    @endif
                </li>
            @endforeach
        </ul>
    </section>
@endif

@if (! empty($jurnal))
    <section>
        <h2 class="u-sec-title">Baca Jurnal Ilmiah Terkait</h2>
        <p class="mt-2 text-[10px] tracking-wide uppercase text-ink/40">
            Referensi pembelajaran akademik — jurnal terindeks SINTA, Google Scholar &amp; portal resmi Mahkamah Agung (tautan luar)
        </p>
        <ul class="mt-2 space-y-2">
            @foreach ($jurnal as $j)
                <li class="u-smart-card">
                    <a href="{{ $j['href'] }}" target="_blank" rel="noopener noreferrer"
                       class="flex items-start justify-between gap-3 p-4">
                        <span class="min-w-0">
                            <span class="block text-sm font-bold text-emerald-900">{{ $j['label'] }}</span>
                            <span class="mt-1 block text-xs text-ink/70">{{ $j['desc'] ?? '' }}</span>
                            <span class="mt-1 block text-[10px] text-ink/40">{{ $j['sumber'] ?? '' }}</span>
                        </span>
                        <span class="mt-0.5 flex shrink-0 flex-col items-end gap-1.5">
                            <span class="rounded bg-gold-soft/70 px-2 py-0.5 text-[10px] font-extrabold text-gold-deep ring-1 ring-gold/40">{{ $j['tag'] ?? 'Jurnal' }}</span>
                            <span class="u-ref-btn !px-2 !py-1">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-3.5 w-3.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7m0 0h-6m6 0v6"/></svg>
                            </span>
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>
        <p class="mt-2 text-center text-[10px] text-ink/40">
            Daftar tautan dapat bertambah — lihat juga halaman <a href="{{ route('sources') }}" class="font-semibold text-gold-deep underline transition hover:text-emerald-900">Referensi</a>.
        </p>
    </section>
@endif

{{-- Tombol rujukan resmi emerald-gold: membuka artikel ilmiah/putusan MA pertama di tab baru --}}
@php
    $refCtaHref = collect($jurnal ?? [])->pluck('href')->filter()->first()
        ?? collect($yurisprudensi ?? [])->pluck('link')->filter()->first();
@endphp
@if (! empty($refCtaHref))
    <div class="mt-5 flex justify-center">
        <a href="{{ $refCtaHref }}" target="_blank" rel="noopener noreferrer" class="u-btn-ref-cta px-5 py-3 text-[0.7rem]">
            Baca Jurnal &amp; Putusan MA Terkait
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7m0 0h-6m6 0v6"/></svg>
        </a>
    </div>
@endif
