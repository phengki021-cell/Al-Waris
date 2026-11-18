@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
{{-- =========================================================
     HEADER GRADIENT (gaya Nusuk): logo + nama + tema,
     shortcut ikon horizontal, area informasi faraid.
     ========================================================= --}}
<section class="relative -mx-4 overflow-hidden rounded-b-[2rem] text-ivory md:-mx-8 md:rounded-b-[3rem]"
         style="background:linear-gradient(135deg, #09271F 0%, #123D32 46%, #147F70 100%)"
         aria-label="Beranda Al-Waris">
    {{-- Tekstur geometris Islam sangat halus --}}
    <div class="pointer-events-none absolute inset-0 opacity-[0.07]" aria-hidden="true"
         style="background-image:url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2248%22 height=%2248%22 viewBox=%220 0 48 48%22><g fill=%22none%22 stroke=%22%23D8B66A%22 stroke-width=%221%22><rect x=%2214%22 y=%2214%22 width=%2220%22 height=%2220%22/><rect x=%2214%22 y=%2214%22 width=%2220%22 height=%2220%22 transform=%22rotate(45 24 24)%22/></g></svg>'); background-size:48px 48px;"></div>
    <div class="pointer-events-none absolute -top-24 -right-16 h-64 w-64 rounded-full opacity-40 blur-[70px]" aria-hidden="true"
         style="background:radial-gradient(closest-side, rgba(216,182,106,0.5), transparent)"></div>
    {{-- Partikel emas mengambang — sangat halus, nonaktif pada reduced motion --}}
    <div class="u-float pointer-events-none absolute left-[12%] top-[38%] h-1.5 w-1.5 rounded-full bg-gold/70 blur-[1px]" style="animation-duration:9s" aria-hidden="true"></div>
    <div class="u-float pointer-events-none absolute left-[28%] top-[62%] h-1 w-1 rounded-full bg-gold/50 blur-[1px]" style="animation-duration:12s;animation-delay:-4s" aria-hidden="true"></div>
    <div class="u-float pointer-events-none absolute right-[18%] top-[24%] h-1 w-1 rounded-full bg-[#2FBF8F]/60 blur-[1px]" style="animation-duration:10s;animation-delay:-7s" aria-hidden="true"></div>
    <div class="u-float pointer-events-none absolute right-[8%] top-[52%] h-1.5 w-1.5 rounded-full bg-gold/60 blur-[1px]" style="animation-duration:11s;animation-delay:-2s" aria-hidden="true"></div>
    <div class="u-float pointer-events-none absolute left-[6%] top-[16%] h-1 w-1 rounded-full bg-gold/40 blur-[1px]" style="animation-duration:13s;animation-delay:-9s" aria-hidden="true"></div>

    <div class="relative px-5 pb-6 pt-6 sm:px-8 md:px-10 md:pt-8">
        {{-- Baris atas: logo + nama + tema --}}
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-white/10 ring-1 ring-gold/50">
                    @include('partials.logo', ['class' => 'h-7 w-7'])
                </span>
                <span>
                    <span class="block text-base font-bold uppercase tracking-[0.26em] md:text-lg">Al&nbsp;Waris</span>
                    <span class="mt-0.5 block text-[0.55rem] uppercase tracking-[0.3em] text-gold/90">Warisan Islam, Untuk Masa Depan</span>
                </span>
            </div>

            <button type="button"
                    x-data="themeToggle()"
                    x-on:click="cycle()"
                    :aria-pressed="mode !== 'light'"
                    aria-label="Ubah tema: terang, gelap, atau ikut sistem"
                    :title="'Tema: ' + label()"
                    class="grid h-10 w-10 place-items-center rounded-full border border-gold/40 text-ivory transition hover:border-gold hover:bg-white/10">
                <svg x-show="mode === 'light'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-[1.1rem] w-[1.1rem]" aria-hidden="true"><circle cx="12" cy="12" r="4.25"/><path stroke-linecap="round" d="M12 2.75v2m0 14.5v2m9.25-9.25h-2m-14.5 0h-2m15.8-6.55-1.42 1.42M6.36 17.64l-1.42 1.42m14.12 0-1.42-1.42M6.36 6.36 4.94 4.94"/></svg>
                <svg x-show="mode === 'dark'" x-cloak xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-[1.1rem] w-[1.1rem]" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13.5A8.5 8.5 0 0 1 10.5 4a8.5 8.5 0 1 0 9.5 9.5Z"/></svg>
                <svg x-show="mode === 'system'" x-cloak xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-[1.1rem] w-[1.1rem]" aria-hidden="true"><rect x="3" y="4.5" width="18" height="12" rx="2"/><path stroke-linecap="round" d="M9 20h6M12 16.5V20"/></svg>
            </button>
        </div>

        {{-- Shortcut ikon horizontal (digeser bila layar sempit) --}}
        <nav class="mt-7 flex gap-6 overflow-x-auto pb-1 md:gap-8 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden"
             aria-label="Menu cepat">
            @foreach ([
                ['route' => 'heirs', 'label' => 'Ahli Waris', 'icon' => '<circle cx="12" cy="8" r="3.25"/><path stroke-linecap="round" stroke-linejoin="round" d="M5 19.5c.6-3.2 3.4-5.25 7-5.25s6.4 2.05 7 5.25"/>'],
                ['route' => 'shares', 'label' => 'Bagian Warisan', 'icon' => '<circle cx="12" cy="12" r="8.5"/><path stroke-linecap="round" d="M12 3.5V12l6 3.5"/>'],
                ['route' => 'calculate', 'label' => 'Kalkulator', 'icon' => '<rect x="5" y="3" width="14" height="18" rx="2.5"/><path stroke-linecap="round" d="M9 7h6m-6 4h.01M12 11h.01M15 11h.01M9 14h.01M12 14h.01M15 14h.01M9 17h6"/>'],
                ['route' => 'how-to', 'label' => 'Cara Menghitung', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 5.5A2.5 2.5 0 0 1 6.5 3H12v18H6.5A2.5 2.5 0 0 1 4 18.5Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M20 5.5A2.5 2.5 0 0 0 17.5 3H12v18h5.5a2.5 2.5 0 0 0 2.5-2.5Z"/>'],
            ] as $shortcut)
                @php $active = request()->routeIs($shortcut['route']); @endphp
                <a href="{{ route($shortcut['route']) }}"
                   class="group flex w-[4.5rem] shrink-0 flex-col items-center gap-2 sm:w-20"
                   @if ($active) aria-current="page" @endif>
                    <span class="relative grid h-14 w-14 place-items-center rounded-2xl ring-1 transition-all duration-300 group-hover:-translate-y-0.5 group-active:scale-95 {{ $active ? 'ring-gold/60' : 'ring-white/15 group-hover:ring-gold/40' }}"
                          style="background:linear-gradient(140deg, rgba(18,61,50,0.55) 0%, rgba(20,127,112,0.45) 100%); box-shadow:0 6px 18px -8px rgba(9,39,31,0.55), inset 0 1px 0 rgba(255,255,255,0.10);">
                        <span class="pointer-events-none absolute inset-x-3 top-0 h-px bg-gradient-to-r from-transparent via-gold/50 to-transparent" aria-hidden="true"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#D8B66A" stroke-width="1.6" class="h-6 w-6 transition-transform duration-300 group-hover:scale-110" aria-hidden="true">{!! $shortcut['icon'] !!}</svg>
                    </span>
                    <span class="text-center text-[0.62rem] font-semibold leading-tight text-ivory/90 transition-colors group-hover:text-gold">{{ $shortcut['label'] }}</span>
                </a>
            @endforeach
        </nav>

        {{-- Area informasi: gambar utama timbangan keadilan + mushaf --}}
        <div class="relative mt-7 flex flex-col items-center gap-5 overflow-hidden rounded-3xl bg-white/8 p-4 ring-1 ring-white/10 sm:flex-row sm:gap-6 md:mt-8 md:p-6">
            {{-- Kedalaman: glow emerald-toska ganda --}}
            <div class="pointer-events-none absolute -bottom-16 -left-16 h-48 w-48 rounded-full opacity-35 blur-[60px]" aria-hidden="true"
                 style="background:radial-gradient(closest-side, rgba(20,127,112,0.55), transparent)"></div>
            <div class="pointer-events-none absolute -top-20 right-0 h-44 w-44 rounded-full opacity-30 blur-[56px]" aria-hidden="true"
                 style="background:radial-gradient(closest-side, rgba(47,191,143,0.45), transparent)"></div>

            {{-- Emblem orbital astrolab — dekoratif di belakang ilustrasi, logo statis di pusat --}}
            @include('partials.emblem-orbital')

            <img src="/images/hero-faraid.svg" alt="" width="520" height="380" loading="eager"
                 class="relative w-full max-w-[16rem] shrink-0 rounded-2xl shadow-lg shadow-[#09271F]/50 ring-1 ring-gold/20 sm:max-w-[17rem] md:max-w-xs"
                 aria-hidden="true">
            <div class="relative min-w-0 text-center sm:text-left">
                <h1 class="text-xl font-bold leading-snug md:text-2xl">Ilmu Faraid dan Keadilan</h1>
                <p class="mt-2 text-sm leading-relaxed text-ivory/80 md:text-sm">Pembagian warisan berdasarkan ketentuan hukum Islam.</p>
                <a href="{{ route('calculate') }}" class="mt-4 inline-flex items-center gap-2 rounded-full bg-gold px-6 py-3 text-sm font-bold text-[#09271F] shadow-lg shadow-[#09271F]/40 transition hover:-translate-y-0.5 hover:bg-gold/90 active:scale-95">
                    Hitung Warisan
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/></svg>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- =========================================================
     MENU LAYANAN UTAMA — kartu putih gaya Nusuk
     (halaman yang belum tampil di shortcut header)
     ========================================================= --}}
<section class="mt-7 md:mt-9" aria-label="Menu layanan">
    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 md:max-w-3xl">
        @foreach ([
            ['route' => 'history', 'label' => 'Riwayat', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.5 5v3.5H7"/><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 8.5A8.5 8.5 0 1 1 3.9 12"/><path stroke-linecap="round" d="M12 8v4.5l3 2"/>'],
            ['route' => 'sources', 'label' => 'Referensi', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M5 4h6a3 3 0 0 1 3 3v13H8a3 3 0 0 1-3-3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M14 7a3 3 0 0 1 3-3h2v13h-2a3 3 0 0 0-3 3"/><path stroke-linecap="round" d="M8 8h3M8 11h3"/>'],
            ['route' => 'about', 'label' => 'Tentang', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 13.5a3.75 3.75 0 1 0 0-7.5 3.75 3.75 0 0 0 0 7.5Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 8.75a7.5 7.5 0 1 1 15 7.5 7.5 7.5 0 0 1-15-.75"/><path stroke-linecap="round" d="M10 20.5 12 18l2 2.5"/>'],
            ['route' => 'install', 'label' => 'Pasang', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3v11"/><path stroke-linecap="round" stroke-linejoin="round" d="m8 10.5 4 4 4-4"/><path stroke-linecap="round" d="M4.5 20.5h15"/>'],
        ] as $service)
            <a data-reveal href="{{ route($service['route']) }}"
               class="group flex flex-col items-center gap-2 rounded-2xl bg-paper p-3.5 text-center ring-1 ring-black/5 shadow-smooth transition hover:-translate-y-0.5 hover:ring-gold/40 active:scale-[0.97]"
               @if (request()->routeIs($service['route'])) aria-current="page" @endif>
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-emerald-100 text-emerald-900 ring-1 ring-emerald-200 transition group-hover:bg-emerald-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-5 w-5" aria-hidden="true">{!! $service['icon'] !!}</svg>
                </span>
                <span class="text-[0.66rem] font-semibold leading-tight text-charcoal/80">{{ $service['label'] }}</span>
            </a>
        @endforeach
    </div>
</section>

{{-- =========================================================
     BANNER EDUKASI — "Mengenal Ilmu Faraid"
     ========================================================= --}}
<section class="mt-7 md:mt-9" aria-label="Banner edukasi">
    <a href="{{ route('how-to') }}"
       class="group relative flex items-center gap-4 overflow-hidden rounded-3xl bg-gradient-to-r from-gold-soft via-paper to-emerald-soft p-5 ring-1 ring-gold/35 shadow-smooth transition hover:-translate-y-0.5 hover:ring-gold/60 md:gap-5 md:p-6">
        <div class="pointer-events-none absolute -right-8 -top-10 h-36 w-36 rounded-full opacity-25 blur-[40px]" aria-hidden="true"
             style="background:radial-gradient(closest-side, rgba(216,182,106,0.9), transparent)"></div>
        <div class="pointer-events-none absolute -bottom-12 left-1/3 h-28 w-28 rounded-full opacity-20 blur-[36px]" aria-hidden="true"
             style="background:radial-gradient(closest-side, rgba(20,127,112,0.8), transparent)"></div>
        <img src="/images/banner-faraid.svg" alt="" width="260" height="160" loading="lazy"
             class="relative h-16 w-auto shrink-0 transition-transform duration-300 group-hover:scale-[1.04] md:h-20"
             aria-hidden="true">
        <div class="min-w-0 flex-1">
            <p class="text-[0.6rem] font-bold uppercase tracking-[0.25em] text-gold-deep">Edukasi</p>
            <h2 class="mt-0.5 text-base font-bold text-deep md:text-lg">Mengenal Ilmu Faraid</h2>
            <p class="mt-1 text-xs leading-relaxed text-charcoal/65 md:text-[0.8rem]">Pelajari ketentuan pembagian harta warisan berdasarkan Al-Qur'an dan hukum Islam.</p>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5 shrink-0 text-accent transition-transform group-hover:translate-x-1" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m9 6 6 6-6 6"/></svg>
    </a>
</section>

{{-- =========================================================
     KARTU KONTEN — informasi dari data yang benar-benar ada
     ========================================================= --}}
<section class="mt-7 md:mt-9" aria-label="Informasi aplikasi">
    <div class="grid gap-3 md:grid-cols-2">
        {{-- Referensi hukum --}}
        <a data-reveal href="{{ route('sources') }}" class="u-lift group rounded-3xl bg-paper p-5 ring-1 ring-black/5 shadow-smooth transition hover:ring-gold/40 md:p-6">
            <div class="flex items-center gap-3">
                <span class="grid h-10 w-10 place-items-center rounded-xl bg-[#123D32]/8 text-[#123D32] ring-1 ring-[#123D32]/10">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-5 w-5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 19V6a2 2 0 0 1 2-2h14v13H6a2 2 0 0 0-2 2Zm0 0a2 2 0 0 0 2 2h14"/><path stroke-linecap="round" d="M9 8h7"/></svg>
                </span>
                <h3 class="font-semibold text-deep">Referensi Hukum</h3>
            </div>
            <p class="mt-3 text-sm leading-relaxed text-charcoal/60">
                {{ $sourcesCount }} sumber rujukan terpasang: Al-Qur'an (QS. An-Nisaa 4:11–12, 4:176),
                Kompilasi Hukum Islam (Inpres 1/1991), dan kitab Fathul-Qarib.
            </p>
            <span class="mt-3 inline-flex items-center gap-2 text-sm font-medium text-accent">
                Lihat semua referensi
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4 transition group-hover:translate-x-1" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/></svg>
            </span>
        </a>

        {{-- Materi ilmu faraid --}}
        <a data-reveal href="{{ route('heirs') }}" class="u-lift group rounded-3xl bg-paper p-5 ring-1 ring-black/5 shadow-smooth transition hover:ring-gold/40 md:p-6">
            <div class="flex items-center gap-3">
                <span class="grid h-10 w-10 place-items-center rounded-xl bg-[#123D32]/8 text-[#123D32] ring-1 ring-[#123D32]/10">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-5 w-5" aria-hidden="true"><circle cx="12" cy="8" r="3.25"/><path stroke-linecap="round" stroke-linejoin="round" d="M5 19.5c.6-3.2 3.4-5.25 7-5.25s6.4 2.05 7 5.25"/></svg>
                </span>
                <h3 class="font-semibold text-deep">Materi Ilmu Faraid</h3>
            </div>
            <p class="mt-3 text-sm leading-relaxed text-charcoal/60">
                {{ $heirsCount }} golongan ahli waris dan {{ $sharesCount }} aturan bagian pasti —
                dzawil furudh, ashabah, hingga kaidah hijab dan ahli waris pengganti.
            </p>
            <span class="mt-3 inline-flex items-center gap-2 text-sm font-medium text-accent">
                Pelajari ahli waris
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4 transition group-hover:translate-x-1" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/></svg>
            </span>
        </a>

        {{-- Riwayat perhitungan (jujur: kosong bila belum ada) --}}
        <a data-reveal href="{{ route('history') }}" class="u-lift group rounded-3xl bg-paper p-5 ring-1 ring-black/5 shadow-smooth transition hover:ring-gold/40 md:p-6">
            <div class="flex items-center gap-3">
                <span class="grid h-10 w-10 place-items-center rounded-xl bg-[#123D32]/8 text-[#123D32] ring-1 ring-[#123D32]/10">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-5 w-5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.5 5v3.5H7"/><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 8.5A8.5 8.5 0 1 1 3.9 12"/><path stroke-linecap="round" d="M12 8v4.5l3 2"/></svg>
                </span>
                <h3 class="font-semibold text-deep">Riwayat Perhitungan</h3>
            </div>
            <p class="mt-3 text-sm leading-relaxed text-charcoal/60">
                @if ($demoCount > 0)
                    Ada {{ $demoCount }} perhitungan tersimpan di perangkat ini — buka untuk meninjau kembali hasilnya.
                @else
                    Belum ada riwayat. Setelah menghitung, hasilnya tersimpan di sini untuk ditinjau kembali.
                @endif
            </p>
            <span class="mt-3 inline-flex items-center gap-2 text-sm font-medium text-accent">
                Buka riwayat
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4 transition group-hover:translate-x-1" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/></svg>
            </span>
        </a>

        {{-- Informasi aplikasi --}}
        <a data-reveal href="{{ route('about') }}" class="u-lift group rounded-3xl bg-paper p-5 ring-1 ring-black/5 shadow-smooth transition hover:ring-gold/40 md:p-6">
            <div class="flex items-center gap-3">
                <span class="grid h-10 w-10 place-items-center rounded-xl bg-[#123D32]/8 text-[#123D32] ring-1 ring-[#123D32]/10">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-5 w-5" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M12 8h.01M11 12h1v5h1"/></svg>
                </span>
                <h3 class="font-semibold text-deep">Informasi Aplikasi</h3>
            </div>
            <p class="mt-3 text-sm leading-relaxed text-charcoal/60">
                Al-Waris adalah alat bantu edukasi dan simulasi faraid — bukan pengganti ahli faraid.
                Dikembangkan oleh Hengki Pranata.
            </p>
            <span class="mt-3 inline-flex items-center gap-2 text-sm font-medium text-accent">
                Tentang aplikasi
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4 transition group-hover:translate-x-1" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/></svg>
            </span>
        </a>
    </div>
</section>

{{-- Banner ajakan instalasi PWA — perilaku dikendalikan resources/js/app.js --}}
<div
    id="pwa-install-banner"
    hidden
    class="fixed inset-x-3 bottom-24 z-50 max-w-md rounded-2xl bg-deep p-4 text-ivory shadow-2xl ring-1 ring-gold/40 sm:left-auto sm:right-4 sm:w-96 md:bottom-4"
    style="margin-bottom: env(safe-area-inset-bottom);"
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
