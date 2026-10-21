@extends('layouts.app')

@section('title', 'Beranda')

@php
    $canCalculate = $heirsCount > 0 && $sharesCount > 0;
@endphp

@section('content')
{{-- Hero --}}
<section class="rounded-2xl bg-primary p-6 text-white md:p-12">
    <p class="text-sm font-semibold tracking-widest text-accent uppercase">Saham warisan setiap orang</p>
    <h1 class="mt-3 text-3xl font-bold md:text-5xl">Al-Waris</h1>
    <p class="mt-4 max-w-2xl text-base text-white/85 md:text-lg">
        Belajar <em>faraid</em> — ilmu pembagian warisan Islam — dengan bahasa Indonesia yang mudah dipahami,
        bersumber dari Al-Qur'an, KHI, dan kitab klasik. Coba juga simulasi hitung warisan.
    </p>
    <div class="mt-6 flex flex-wrap gap-3">
        <a href="{{ route('calculate') }}" class="rounded-lg bg-accent px-6 py-3 font-semibold text-primary shadow transition hover:bg-accent-soft">
            Hitung Warisan Otomatis
        </a>
        <a href="{{ route('how-to') }}" class="rounded-lg border border-white/30 px-6 py-3 font-medium text-white transition hover:bg-primary-700">
            Pelajari Cara Menghitung
        </a>
    </div>
    <p class="mt-4 text-xs text-white/60">Edukasi & simulasi — bukan pengganti ahli faraid atau nasihat hukum.</p>
</section>

{{-- Kartu-fitur --}}
<section class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    @foreach ([
        ['route' => 'heirs', 'icon' => '👥', 'title' => 'Ahli Waris', 'desc' => $heirsCount.' ahli waris dalam katalog', 'note' => 'Dzawil Furudh, Ashabah, Dzawil Arham.'],
        ['route' => 'shares', 'icon' => '📐', 'title' => 'Bagian-Bagian', 'desc' => $sharesCount.' bagian pasti dipetakan', 'note' => 'Dengan sumber (QS., dll.).'],
        ['route' => 'calculate', 'icon' => '🧮', 'title' => 'Hitung Warisan', 'desc' => 'Simulasi pecahan eksak', 'note' => 'Tanpa kesalahan floating point.'],
        ['route' => 'how-to', 'icon' => '📖', 'title' => 'Cara Menghitung', 'desc' => 'Amal ummi, \'aul, radd, gawalan & lainnya.', 'note' => 'Tahap 2.'],
    ] as $card)
        <a href="{{ route($card['route']) }}" class="flex flex-col rounded-xl bg-paper p-5 shadow-sm ring-1 ring-black/5 transition hover:shadow-md">
            <span class="text-2xl">{{ $card['icon'] }}</span>
            <h3 class="mt-2 font-semibold">{{ $card['title'] }}</h3>
            <p class="mt-1 text-sm text-ink/70">{{ $card['desc'] }}</p>
            <p class="mt-2 text-xs text-accent">{{ $card['note'] }}</p>
        </a>
    @endforeach
</section>

{{-- Stat --}}
<section class="mt-8 rounded-xl bg-paper p-5 shadow-sm ring-1 ring-black/5">
    <h2 class="font-semibold">Status Proyek</h2>
    <ul class="mt-3 grid gap-2 text-sm sm:grid-cols-2 lg:grid-cols-4">
        <li class="rounded-lg bg-surface p-3"><strong>{{ $heirsCount }}</strong> ahli waris terdaftar</li>
        <li class="rounded-lg bg-surface p-3"><strong>{{ $sharesCount }}</strong> bagian pasti terdefinisi</li>
        <li class="rounded-lg bg-surface p-3"><strong>{{ $sourcesCount }}</strong> sumber terdaftar</li>
        <li class="rounded-lg bg-surface p-3"><strong>{{ $demoCount }}</strong> riwayat DEMO</li>
    </ul>
    <p class="mt-3 text-xs text-ink/60">Data dari database pengembangan (seeder DEMO).</p>
</section>

{{-- Banner ajakan instalasi PWA — perilaku dikendalikan resources/js/app.js --}}
<div
    id="pwa-install-banner"
    hidden
    class="fixed inset-x-3 bottom-3 z-50 max-w-md sm:left-auto sm:right-4 sm:w-96 rounded-2xl bg-primary p-4 text-white shadow-xl ring-1 ring-black/10 md:inset-x-auto"
    role="dialog"
    aria-labelledby="pwa-install-title"
    aria-describedby="pwa-install-desc"
>
    <div class="flex items-start gap-3">
        <img src="/icons/icon-192.png" alt="" class="h-10 w-10 rounded-lg shadow-sm" aria-hidden="true">
        <div class="min-w-0">
            <p id="pwa-install-title" class="text-sm font-bold">Instal Aplikasi Waris Syariah untuk Akses Cepat</p>
            <p id="pwa-install-desc" class="mt-1 text-xs text-white/75">
                Tambahkan Al-Waris ke layar utama HP/laptop agar lebih cepat diakses.
            </p>
        </div>
        <button
            type="button"
            id="pwa-install-close"
            class="shrink-0 rounded-lg p-1 text-white/70 transition hover:text-white"
            aria-label="Tutup"
        >&times;</button>
    </div>
    <div class="mt-3 flex gap-2">
        <button
            type="button"
            id="pwa-install-accept"
            class="flex-1 rounded-lg bg-accent px-4 py-2 text-sm font-semibold text-primary transition hover:bg-accent-soft"
        >Instal Sekarang</button>
        <button
            type="button"
            id="pwa-install-later"
            class="rounded-lg border border-white/30 px-4 py-2 text-sm text-white/90 transition hover:bg-white/10"
        >Nanti Saja</button>
    </div>
</div>
@endsection
