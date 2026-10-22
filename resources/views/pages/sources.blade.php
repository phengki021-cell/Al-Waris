@extends('layouts.app')

@section('title', 'Sumber dan Referensi')

@section('content')
<section>
    <h1 class="text-2xl font-bold md:text-3xl">Sumber / Referensi</h1>
    <p class="mt-2 text-sm text-ink/70 max-w-3xl">
        Daftar sumber yang menjadi basis aturan faraid di Al-Waris. Setiap bagian warisan dapat ditautkan ke
        sumbernya di halaman Bagian. Detail penentuan pendapat klasik akan dikategorisasi di Tahap 2.
    </p>
</section>

<section class="mt-6">
    @foreach ($sources as $source)
        <article class="mb-3 rounded-xl bg-paper p-4 shadow-sm ring-1 ring-black/5">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="font-semibold">{{ $source->title }}</h2>
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
                <div class="shrink-0 text-right">
                    <span class="rounded bg-accent-soft px-2 py-0.5 text-xs text-primary">{{ ucfirst($source->type) }}</span>
                    @if ($source->is_primary)
                        <p class="mt-1 text-[10px] text-primary/70">Sumber utama</p>
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
                        <p class="mt-0.5 text-[10px] text-ink/40">{{ $j['sumber'] ?? '' }}</p>
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
