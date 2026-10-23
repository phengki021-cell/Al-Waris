@extends('layouts.app')

@section('title', 'Beranda')

@php
    $canCalculate = $heirsCount > 0 && $sharesCount > 0;
@endphp

@section('content')
{{-- ====== HERO ====== --}}
<section
    data-parallax-group
    class="u-lattice relative overflow-hidden rounded-[2rem] bg-deep text-ivory md:rounded-[2.75rem]"
    aria-labelledby="hero-title"
>
    {{-- cahaya emas bergerak perlahan --}}
    <div class="pointer-events-none absolute -top-32 right-[8%] h-[26rem] w-[26rem] rounded-full opacity-60 blur-[90px] u-drift" style="background: radial-gradient(closest-side, rgba(212,175,87,0.34), transparent)" aria-hidden="true"></div>
    <div class="pointer-events-none absolute -bottom-40 -left-24 h-[22rem] w-[22rem] rounded-full opacity-40 blur-[100px]" style="background: radial-gradient(closest-side, rgba(6,95,70,0.9), transparent)" aria-hidden="true"></div>

    <div class="relative grid gap-10 px-6 py-12 sm:px-10 md:px-14 md:py-16 lg:grid-cols-12 lg:items-center lg:gap-6 lg:py-20">
        {{-- Kolom naratif --}}
        <div class="lg:col-span-7">
            <p class="u-enter u-kicker text-gold" style="--d: 0ms">Ilmu Faraid · Indonesia</p>
            <h1 id="hero-title" class="u-enter u-display mt-5 text-4xl sm:text-5xl xl:text-[4.25rem]" style="--d: 90ms">
                Warisan Terjaga.
                <span class="block text-gold">Keadilan Terpelihara.</span>
            </h1>
            <p class="u-enter mt-6 max-w-xl text-base leading-relaxed text-ivory/75 md:text-lg" style="--d: 180ms">
                <em class="not-italic text-ivory">Faraid</em> — ilmu pembagian warisan Islam —
                disusun ulang bagi bahasa Indonesia: presisi pecahan eksak, dijiwai Al-Qur'an,
                KHI, dan kitab klasik, dipandu diagram yang terbaca.
            </p>

            <div class="u-enter mt-9 flex flex-wrap items-center gap-4" style="--d: 270ms">
                <a href="{{ route('calculate') }}" class="u-btn-gold px-7 py-3.5 text-sm">
                    <span>Mulai Hitung Warisan</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/></svg>
                </a>
                <a href="{{ route('how-to') }}" class="u-btn-ghost px-7 py-3.5 text-sm">
                    <span>Jelajahi Ilmu Faraid</span>
                </a>
            </div>

            <p class="u-enter mt-7 flex items-center gap-2 text-[0.7rem] tracking-wide text-ivory/45" style="--d: 360ms">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-3.5 w-3.5 text-gold/70" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v5m0 3h.01M12 3l9 17H3z" transform="scale(.9) translate(1,.4)"/></svg>
                Edukasi &amp; simulasi — bukan pengganti ahli faraid atau nasihat hukum.
            </p>
        </div>

        {{-- Komposisi astrolabe geometris --}}
        <div class="u-enter relative mx-auto w-full max-w-sm lg:col-span-5 xl:max-w-md" style="--d: 240ms" data-parallax="0.08">
            <svg viewBox="0 0 480 480" class="w-full" role="img" aria-label="Komposisi geometri Islam: lingkaran astrolabe dan bintang delapan titik">
                <defs>
                    <radialGradient id="awa-halo" cx="50%" cy="42%" r="55%">
                        <stop offset="0%" stop-color="#A6802F" stop-opacity="0.55"/>
                        <stop offset="60%" stop-color="#08152F" stop-opacity="0"/>
                        <stop offset="100%" stop-color="#08152F" stop-opacity="0"/>
                    </radialGradient>
                    <linearGradient id="awa-gold" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0%" stop-color="#FCD34D"/>
                        <stop offset="100%" stop-color="#B8913B"/>
                    </linearGradient>
                </defs>

                <circle cx="240" cy="240" r="239" fill="url(#awa-halo)"/>

                <g stroke="#D4AF57" fill="none">
                    <circle cx="240" cy="240" r="204" stroke-opacity="0.5" stroke-width="1"/>
                    <circle cx="240" cy="240" r="190" stroke-opacity="0.22" stroke-width="1" stroke-dasharray="2 7"/>
                    <circle cx="240" cy="240" r="128" stroke-opacity="0.35" stroke-width="1"/>
                    <circle cx="240" cy="240" r="64" stroke-opacity="0.6" stroke-width="1"/>
                </g>

                <g class="u-loop-rotate" style="animation-duration: 140s" stroke="#D4AF57" fill="none" opacity="0.5">
                    <g transform="rotate(15 240 240)">
                        <!-- tanda skala pada cincin luar -->
                        <g id="awa-ticks" stroke-width="1">
                            <line x1="240" y1="36" x2="240" y2="52"/>
                            <line x1="240" y1="36" x2="240" y2="52" transform="rotate(30 240 240)"/>
                            <line x1="240" y1="36" x2="240" y2="52" transform="rotate(60 240 240)"/>
                            <line x1="240" y1="36" x2="240" y2="52" transform="rotate(90 240 240)"/>
                            <line x1="240" y1="36" x2="240" y2="52" transform="rotate(120 240 240)"/>
                            <line x1="240" y1="36" x2="240" y2="52" transform="rotate(150 240 240)"/>
                            <line x1="240" y1="36" x2="240" y2="52" transform="rotate(180 240 240)"/>
                            <line x1="240" y1="36" x2="240" y2="52" transform="rotate(210 240 240)"/>
                            <line x1="240" y1="36" x2="240" y2="52" transform="rotate(240 240 240)"/>
                            <line x1="240" y1="36" x2="240" y2="52" transform="rotate(270 240 240)"/>
                            <line x1="240" y1="36" x2="240" y2="52" transform="rotate(300 240 240)"/>
                            <line x1="240" y1="36" x2="240" y2="52" transform="rotate(330 240 240)"/>
                        </g>
                    </g>
                </g>

                <g fill="none" stroke="url(#awa-gold)" stroke-width="1.4">
                    <polygon points="410,240 387.2,325 325,387.2 240,410 155,387.2 92.8,325 70,240 92.8,155 155,92.8 240,70 325,92.8 387.2,155" opacity="0.35" transform="rotate(15 240 240)" stroke-opacity="0.5"/>
                    <g>
                        <rect x="152" y="152" width="176" height="176"/>
                        <rect x="152" y="152" width="176" height="176" transform="rotate(45 240 240)"/>
                    </g>
                </g>

                <g fill="#D4AF57">
                    <circle cx="330" cy="132" r="3.5" class="u-float"/>
                    <circle cx="142" cy="348" r="3.5" class="u-float" style="animation-delay: -3s"/>
                    <circle cx="352" cy="352" r="2.5" opacity="0.7"/>
                    <circle cx="128" cy="128" r="2.5" opacity="0.7"/>
                </g>

                <g class="u-loop-rotate" style="animation-duration: 26s">
                    <circle cx="240" cy="240" r="38" fill="none" stroke="#D4AF57" stroke-opacity="0.55" stroke-width="1"/>
                    <circle cx="240" cy="202" r="4" fill="#FCD34D"/>
                </g>

                <text x="240" y="232" text-anchor="middle" font-size="11" letter-spacing="6" fill="#D4AF57" opacity="0.85">AL</text>
                <text x="240" y="262" text-anchor="middle" font-size="11" letter-spacing="6" fill="#F8FAFC" opacity="0.75">WARIS</text>
            </svg>
        </div>
    </div>
</section>

{{-- ====== Stat strip (angka nyata dari aplikasi) ====== --}}
<section data-reveal class="mt-10">
    <div class="grid grid-cols-2 gap-px overflow-hidden rounded-2xl bg-deep md:grid-cols-4">
        @foreach ([
            ['n' => $heirsCount, 'label' => 'ahli waris terdaftar'],
            ['n' => $sharesCount, 'label' => 'bagian pasti terdefinisi'],
            ['n' => $sourcesCount, 'label' => 'sumber rujukan'],
            ['n' => $demoCount, 'label' => 'riwayat simulasi DEMO'],
        ] as $stat)
            <div class="u-lattice bg-forest px-5 py-6 md:px-7" style="background:#0C1D3E">
                <p class="tnum text-3xl font-semibold text-gold md:text-4xl">
                    <span data-count="{{ $stat['n'] }}">{{ $stat['n'] }}</span>
                </p>
                <p class="mt-1.5 text-xs leading-snug tracking-wide text-ivory/65">{{ $stat['label'] }}</p>
            </div>
        @endforeach
    </div>
    <p class="mt-2 text-right text-[0.65rem] text-charcoal/40">Data dari database pengembangan (seeder DEMO).</p>
</section>

{{-- ====== Jalur eksplorasi (asimetris, tanpa kartu seragam) ====== --}}
<section class="mt-14 md:mt-20">
    <div data-reveal class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="u-kicker text-gold-deep">Jelajahi</p>
            <h2 class="u-display mt-3 text-3xl md:text-4xl">Mulai dari titiknya.</h2>
        </div>
        <p class="max-w-xs text-sm text-charcoal/60">
            Empat pintu satu sistem desain — dari materi hingga kalkulator.
        </p>
    </div>

    <div class="mt-8 grid gap-4 md:grid-cols-12">
        {{-- Panel besar: kalkulator --}}
        <a data-reveal href="{{ route('calculate') }}" class="u-lift u-lattice group relative col-span-12 flex flex-col justify-between overflow-hidden rounded-3xl bg-deep p-7 text-ivory md:col-span-7 md:p-9" style="background:#08152F">
            <div class="pointer-events-none absolute -right-20 -top-20 h-56 w-56 rounded-full opacity-50 blur-[70px] u-drift" style="background: radial-gradient(closest-side, rgba(212,175,87,0.3), transparent)" aria-hidden="true"></div>
            <div class="flex items-start justify-between">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#D4AF57" stroke-width="1.5" class="h-9 w-9" aria-hidden="true"><rect x="5" y="3" width="14" height="18" rx="2.5"/><path stroke-linecap="round" d="M9 7h6m-6 4h.01M12 11h.01M15 11h.01M9 14h.01M12 14h.01M15 14h.01M9 17h6" transform="translate(0,.5) scale(.98)"/></svg>
                <span class="rounded-full border border-gold/30 px-3 py-1 text-[0.65rem] uppercase tracking-[0.2em] text-gold">Eksak</span>
            </div>
            <div class="mt-10 md:mt-16">
                <h3 class="u-display text-2xl md:text-3xl">Hitung Warisan</h3>
                <p class="mt-2 max-w-md text-sm leading-relaxed text-ivory/70">
                    Simulasi pembagian dengan pecahan rasional — tanpa kesalahan floating point,
                    setiap hasil terpasang rujukan QS &amp; pasal KHI.
                </p>
                <span class="mt-5 inline-flex items-center gap-2 text-sm font-medium text-gold">
                    Buka kalkulator
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4 transition group-hover:translate-x-1" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/></svg>
                </span>
            </div>
        </a>

        {{-- Dua panel kecil bertumpuk --}}
        <div class="col-span-12 grid gap-4 md:col-span-5">
            <a data-reveal href="{{ route('heirs') }}" class="u-lift group flex items-center justify-between gap-4 rounded-3xl bg-paper p-6 ring-1 ring-black/5 md:col-start-1">
                <span>
                    <h3 class="font-semibold text-deep">Ahli Waris</h3>
                    <p class="mt-1 text-sm text-charcoal/60">{{ $heirsCount }} golongan — dzawil furudh, ashabah, dzawil arham.</p>
                </span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#B8913B" stroke-width="1.5" class="h-8 w-8 shrink-0" aria-hidden="true"><circle cx="6" cy="6" r="2.5"/><circle cx="18" cy="6" r="2.5"/><circle cx="12" cy="18" r="2.5"/><path d="M7.5 7.5 10.5 16M16.5 7.5 13.5 16M8.5 6h7"/></svg>
            </a>
            <a data-reveal href="{{ route('shares') }}" class="u-lift group flex items-center justify-between gap-4 rounded-3xl bg-paper p-6 ring-1 ring-black/5">
                <span>
                    <h3 class="font-semibold text-deep">Bagian Warisan</h3>
                    <p class="mt-1 text-sm text-charcoal/60">{{ $sharesCount }} bagian pasti — tiap jatah dilengkapi dalilnya.</p>
                </span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#B8913B" stroke-width="1.5" class="h-8 w-8 shrink-0" aria-hidden="true"><circle cx="7" cy="7" r="3.5"/><circle cx="15.5" cy="14" r="4.5"/><path d="m9.5 9.5 3.5 3"/></svg>
            </a>
        </div>

        {{-- Strip lebar: cara menghitung --}}
        <a data-reveal href="{{ route('how-to') }}" class="u-lift group col-span-12 flex flex-wrap items-center justify-between gap-4 rounded-3xl bg-charcoal p-6 text-ivory md:p-7" style="background:#0F172A">
            <span class="flex items-center gap-4">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#D4AF57" stroke-width="1.5" class="h-8 w-8" aria-hidden="true"><path d="M5 4h11a3 3 0 0 1 3 3v13H8a3 3 0 0 1-3-3z"/><path stroke-linecap="round" d="M9 8h6M9 12h6M9 16h3"/></svg>
                <span>
                    <h3 class="font-semibold">Cara Menghitung</h3>
                    <p class="mt-1 text-sm text-ivory/60">Lima tahap panduan — rumus, kaidah fikih, dan studi kasus rupiah.</p>
                </span>
            </span>
            <span class="inline-flex items-center gap-2 text-sm text-gold">
                Buka panduan
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4 transition group-hover:translate-x-1" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/></svg>
            </span>
        </a>
    </div>
</section>

{{-- Banner ajakan instalasi PWA — perilaku dikendalikan resources/js/app.js --}}
<div
    id="pwa-install-banner"
    hidden
    class="fixed inset-x-3 bottom-3 z-50 max-w-md rounded-2xl bg-deep p-4 text-ivory shadow-2xl ring-1 ring-gold/40 sm:left-auto sm:right-4 sm:w-96"
    role="dialog"
    aria-labelledby="pwa-install-title"
    aria-describedby="pwa-install-desc"
>
    <div class="absolute inset-x-0 top-0 h-0.5 bg-gradient-to-r from-transparent via-gold to-transparent" aria-hidden="true"></div>
    <div class="flex items-start gap-3">
        <img src="/icons/icon-192.png" alt="" class="h-10 w-10 rounded-lg ring-1 ring-gold/40" aria-hidden="true">
        <div class="min-w-0">
            <p id="pwa-install-title" class="text-sm font-bold">Instal Aplikasi Waris Syariah untuk Akses Cepat</p>
            <p id="pwa-install-desc" class="mt-1 text-xs text-ivory/75">
                Tambahkan Al-Waris ke layar utama HP/laptop agar lebih cepat diakses.
            </p>
        </div>
        <button
            type="button"
            id="pwa-install-close"
            class="shrink-0 rounded-lg p-1 text-ivory/70 transition hover:text-ivory"
            aria-label="Tutup"
        >&times;</button>
    </div>
    <div class="mt-3 flex gap-2">
        <button
            type="button"
            id="pwa-install-accept"
            class="u-btn-gold flex-1 justify-center px-4 py-2.5 text-sm"
        ><span>Instal Sekarang</span></button>
        <button
            type="button"
            id="pwa-install-later"
            class="rounded-full border border-ivory/25 px-4 py-2.5 text-sm text-ivory/90 transition hover:bg-white/10"
        >Nanti Saja</button>
    </div>
</div>
@endsection
