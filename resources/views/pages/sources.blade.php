@extends('layouts.app')

@section('title', 'Sumber dan Referensi')

@section('content')
@include('partials.back-button')
<section class="mt-3">
    <p class="u-kicker text-gold-deep">Referensi</p>
    <h1 class="u-display mt-1.5 text-2xl text-ink md:text-3xl">Sumber / Referensi</h1>
    <p class="mt-2 text-sm text-ink/70 max-w-3xl">
        Daftar sumber yang menjadi basis aturan faraid di Al-Waris. Setiap bagian warisan dapat ditautkan ke
        sumbernya di halaman Bagian. Detail penentuan pendapat klasik akan dikategorisasi di Tahap 2.
    </p>
</section>

<section class="mx-auto mt-6 max-w-5xl">
    @foreach ($sources as $source)
        <article class="mb-3 flex items-start justify-between gap-4 rounded-3xl bg-paper p-5 shadow-smooth ring-1 ring-black/5 transition hover:ring-gold/40">
            <div class="flex min-w-0 items-start gap-3.5">
                <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-emerald-100 text-emerald-900 ring-1 ring-emerald-200" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 4h6a3 3 0 0 1 3 3v13H8a3 3 0 0 1-3-3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M14 7a3 3 0 0 1 3-3h2v13h-2a3 3 0 0 0-3 3"/><path stroke-linecap="round" d="M8 8h3M8 11h3"/></svg>
                </span>
                <div class="min-w-0">
                    <h2 class="font-bold text-deep">
                        {{ $source->title }}
                        <span class="ml-1 inline-block align-middle rounded-full bg-gold-soft px-2 py-0.5 text-[0.62rem] font-bold text-gold-deep ring-1 ring-gold/30">{{ ucfirst($source->type) }}</span>
                        @if ($source->is_primary)
                            <span class="ml-0.5 inline-block align-middle rounded-full bg-emerald-100 px-2 py-0.5 text-[0.62rem] font-bold text-emerald-900 ring-1 ring-emerald-200">Sumber utama</span>
                        @endif
                    </h2>
                    @if ($source->reference)
                        <p class="text-xs text-ink/60">{{ $source->reference }}</p>
                    @endif
                    @if ($source->excerpt)
                        <p class="mt-2 text-sm text-ink/80">{{ $source->excerpt }}</p>
                    @endif
                    @if ($source->url)
                        <a href="{{ $source->url }}" target="_blank" rel="noopener noreferrer" class="mt-1 inline-block text-xs text-accent underline">
                            Lihat sumber asal
                        </a>
                    @endif
                </div>
            </div>
        </article>
    @endforeach
</section>

{{-- ===== Rujukan digital: yurisprudensi MA RI + jurnal ilmiah (tautan luar) ===== --}}
<section class="mt-10">
    <h2 class="text-xs font-bold uppercase tracking-widest text-ink/50">Rujukan Digital — Yurisprudensi &amp; Jurnal Ilmiah</h2>
    <p class="mt-2 text-xs text-ink/60">
        Putusan dan SEMA Mahkamah Agung RI serta artikel jurnal terindeks (SINTA, Google Scholar) yang dirujuk
        pada modal "Lihat Detail" halaman Ahli Waris &amp; Bagian Warisan, tab Kontemporer kalkulator, dan
        halaman Cara Menghitung. Tautan membuka tab baru.
    </p>

    <div class="mt-4 grid gap-4 md:grid-cols-2">
        <div class="rounded-xl bg-paper p-5 shadow-sm ring-1 ring-black/5">
            <h3 class="text-sm font-semibold text-primary">Yurisprudensi Mahkamah Agung RI</h3>
            <ul class="mt-3 space-y-2">
                @foreach ($yurisprudensis as $y)
                    <li class="rounded-lg bg-surface p-3">
                        <p class="text-sm font-semibold">{{ $y['label'] }}</p>
                        <p class="mt-1 text-xs text-ink/70">{{ $y['ringkas'] }}</p>
                        <a href="{{ $y['link'] }}" target="_blank" rel="noopener noreferrer" class="mt-2 inline-block text-xs text-accent underline">
                            {{ $y['link_label'] ?? 'Buka sumber resmi MA' }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="rounded-xl bg-paper p-5 shadow-sm ring-1 ring-black/5">
            <h3 class="text-sm font-semibold text-primary">Baca Jurnal Ilmiah Terkait</h3>
            <ul class="mt-3 space-y-2">
                @foreach ($jurnals as $j)
                    <li class="rounded-lg bg-surface p-3">
                        <p class="text-sm font-semibold">{{ $j['label'] }}</p>
                        <p class="mt-1 text-xs text-ink/70">{{ $j['desc'] ?? '' }}</p>
                        <p class="mt-0.5 text-[10px] text-ink/70">{{ $j['sumber'] ?? '' }}</p>
                        <a href="{{ $j['href'] }}" target="_blank" rel="noopener noreferrer" class="mt-2 inline-block text-xs text-accent underline">
                            Buka tautan jurnal
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>
@endsection
