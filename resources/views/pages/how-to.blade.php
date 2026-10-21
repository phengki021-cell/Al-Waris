@extends('layouts.app')

@section('title', 'Cara Menghitung')

@section('content')
<section>
    <h1 class="text-2xl font-bold md:text-3xl">Cara Menghitung Warisan</h1>
    <p class="mt-2 text-sm text-ink/70 max-w-3xl">
        Ilmu faraid disusun dalam tahapan yang rapi. Klik setiap tahapan untuk membuka
        detail penjelasan <span class="font-medium">rumus</span>,
        <span class="font-medium">landasan fikih/kaidah</span>, dan
        <span class="font-medium">contoh studi kasus</span> perhitungan dalam rupiah
        (angka eksak — pecahan rasional).
    </p>
</section>

<section class="mt-6 space-y-4">
    @foreach ($steps as $code => $step)
        <details
            class="group overflow-hidden rounded-xl bg-paper shadow-sm ring-1 ring-black/5 open:ring-2 open:ring-accent"
            id="tahap-{{ $code }}"
        >
            <summary class="flex cursor-pointer select-none items-start justify-between gap-3 p-4 md:p-5 focus:outline-none focus-visible:ring-2 focus-visible:ring-accent">
                <span class="min-w-0 flex-1">
                    <span class="block font-semibold text-primary">{{ $step['judul'] }}</span>
                    <span class="mt-1 block text-sm text-ink/80">{{ $step['ringkasan'] }}</span>
                </span>
                <span class="mt-1 flex shrink-0 items-center gap-2">
                    <span class="hidden rounded-lg border border-accent px-2 py-1 text-xs font-medium text-accent md:inline-flex">
                        Lihat Detail <span aria-hidden="true">&darr;</span>
                    </span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-ink/50 transition group-open:rotate-180" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7"/>
                    </svg>
                </span>
            </summary>

            <div class="space-y-5 border-t border-black/5 p-4 md:p-5">
                {{-- Rumus --}}
                <section>
                    <h3 class="text-xs font-bold tracking-widest text-ink/50 uppercase">Rumus</h3>
                    <ul class="mt-2 space-y-2">
                        @foreach ($step['rumus'] as $rumus)
                            <li class="rounded-lg bg-surface p-3 text-sm text-ink/80">{{ $rumus }}</li>
                        @endforeach
                    </ul>
                </section>

                {{-- Landasan fikih / kaidah --}}
                <section>
                    <h3 class="text-xs font-bold tracking-widest text-ink/50 uppercase">Landasan Fikih &amp; Kaidah</h3>
                    <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-ink/80">
                        @foreach ($step['landasan'] as $landasan)
                            <li>{{ $landasan }}</li>
                        @endforeach
                    </ul>
                </section>

                {{-- Contoh studi kasus --}}
                <section>
                    <h3 class="text-xs font-bold tracking-widest text-ink/50 uppercase">Contoh Studi Kasus</h3>
                    <div class="mt-2 space-y-4">
                        @foreach ([$step['contoh'] ?? null, $step['contoh_aul'] ?? null, $step['contoh_radd'] ?? null] as $kasus)
                            @if (is_array($kasus))
                                <div class="rounded-xl bg-surface p-4">
                                    <p class="text-sm font-semibold text-primary">{{ $kasus['judul'] }}</p>
                                    <p class="mt-1 text-xs text-ink/60">
                                        Harta: <span class="font-medium text-ink/80">{{ $kasus['harta_awal'] }}</span>
                                        · Waris: {{ $kasus['partisipan'] }}
                                    </p>
                                    <ol class="mt-3 list-inside list-decimal space-y-1 text-sm text-ink/80">
                                        @foreach ($kasus['langkah'] as $langkah)
                                            <li>{{ $langkah }}</li>
                                        @endforeach
                                    </ol>
                                    <p class="mt-3 rounded bg-accent-soft px-2 py-1 text-xs text-primary">{{ $kasus['hasil'] }}</p>
                                    <p class="mt-2 text-xs italic text-ink/60">{{ $kasus['catatan'] }}</p>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </section>
            </div>
        </details>
    @endforeach
</section>

<section class="mt-8 rounded-xl bg-primary p-6 text-white">
    <h2 class="text-lg font-bold">Belajar dengan kalkulator</h2>
    <p class="mt-2 text-sm text-white/85">
        Pahami tahapan di atas, lalu latih langsung di <a href="{{ route('calculate') }}" class="text-accent underline">Hitung Warisan</a>:
        kalkulator menghitung dengan pecahan eksak dan mencantumkan rujukan pasalnya.
    </p>
</section>
@endsection
