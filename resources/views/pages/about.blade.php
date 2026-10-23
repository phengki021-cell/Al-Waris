@extends('layouts.app')

@section('title', 'Tentang')

@section('content')
<section class="max-w-3xl">
    <h1 class="text-2xl font-bold md:text-3xl">Tentang Al-Waris</h1>

    {{-- Tujuan Al Waris — identitas misi --}}
    <div class="u-smart-card mt-6 p-6 md:p-8">
        <p class="u-kicker">Misi</p>
        <h2 class="mt-2 text-xl font-bold md:text-2xl">Warisan Islam, Untuk Masa Depan</h2>
        <div class="mt-4 space-y-3 text-sm leading-relaxed text-ink/90">
            <p><strong>Tujuan Al-Waris</strong> adalah menjadikan ilmu <em>faraid</em> (pembagian warisan Islam)
            mudah dipelajari oleh siapa pun: dari pemula yang baru mengenal istilah <em>asabah</em> dan <em>fard</em>,
            hingga yang ingin memverifikasi perhitungan dengan presisi pecahan syar'i (½, ⅓, ⅛, dst.).</p>
            <ul class="grid gap-2 sm:grid-cols-2">
                <li class="flex items-start gap-2"><span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-gold shadow-[0_0_8px_rgba(212,175,87,0.9)]" aria-hidden="true"></span><span><strong>Edukasi</strong> — kelas &amp; penjelasan bertahap dengan pijakan Al-Qur'an, hadis, dan kitab fiqh.</span></li>
                <li class="flex items-start gap-2"><span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-gold shadow-[0_0_8px_rgba(212,175,87,0.9)]" aria-hidden="true"></span><span><strong>Alat bantu</strong> — kalkulator faraid interaktif: hijab, 'aul, radd, dan pembulatan rupiah diterangkan dengan alasan.</span></li>
                <li class="flex items-start gap-2"><span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-gold shadow-[0_0_8px_rgba(212,175,87,0.9)]" aria-hidden="true"></span><span><strong>Transparansi</strong> — setiap aturan menyajikan sumber rujukan yang bisa dibuka.</span></li>
                <li class="flex items-start gap-2"><span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-gold shadow-[0_0_8px_rgba(212,175,87,0.9)]" aria-hidden="true"></span><span><strong>Aksesibel</strong> — gratis, tanpa akun, dapat dipasang sebagai PWA di HP.</span></li>
            </ul>
        </div>
    </div>

    {{-- Tentang Pengembang --}}
    <div class="u-smart-card mt-6 overflow-hidden">
        <div class="flex flex-wrap items-center justify-between gap-2 bg-gradient-to-r from-[#050D1D] via-[#08152F] to-[#24479E] px-6 py-3.5 md:px-8">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-ivory/90">Tentang Pengembang</p>
            <span class="rounded-full bg-[#08152F] px-2.5 py-0.5 text-[0.65rem] font-extrabold uppercase tracking-wider text-[#F0F4FF] ring-1 ring-[#08152F]">Pengembang Utama</span>
        </div>
        <div class="flex flex-col gap-6 p-6 sm:flex-row md:p-8">
            <div class="shrink-0 self-start">
                <span class="grid h-20 w-20 place-items-center rounded-2xl bg-gradient-to-br from-[#08152F] via-[#10254B] to-[#8058F5] ring-1 ring-gold/50 shadow-[0_10px_28px_-14px_rgba(8,21,47,0.65)]" aria-hidden="true">
                    <span class="text-2xl font-extrabold tracking-wide text-gold drop-shadow-[0_0_12px_rgba(212,175,87,0.55)]">HP</span>
                </span>
            </div>
            <div class="min-w-0">
                <p class="text-base font-semibold text-ink">Dikembangkan oleh <span class="text-burgundy">Hengki Pranata</span></p>
                <p class="mt-1 text-sm italic text-ink/60">Al Waris — Warisan Islam, Untuk Masa Depan</p>
                <p class="mt-3 text-sm leading-relaxed text-ink/90">Al-Waris dibangun dengan Laravel, Livewire, dan Tailwind CSS dengan
                perhatian khusus pada ketepatan hukum faraid (mesin perhitungan diuji ratusan kasus) serta pengalaman
                antarmuka yang elegan dan responsif — dari ponsel hingga laptop.</p>
                <dl class="mt-4 grid gap-x-6 gap-y-2 text-xs text-ink/70 sm:grid-cols-2">
                    <div class="flex gap-2"><dt class="font-semibold text-ink">Peran</dt><dd>Desain, pengembangan &amp; pengujian</dd></div>
                    <div class="flex gap-2"><dt class="font-semibold text-ink">Teknologi</dt><dd>Laravel · Livewire · Tailwind CSS · PWA</dd></div>
                </dl>
            </div>
        </div>
    </div>

    <div class="mt-6 space-y-4 text-sm leading-relaxed text-ink/90">
        <p><strong>Al-Waris</strong> adalah website edukasi dan kalkulator pembagian harta warisan Islam
        (ilmu <em>faraid</em>) berbahasa Indonesia. Proyek ini bertujuan membantu masyarakat mempelajari dasar-dasar
        faraid — ahli waris, bagian-bagian warisan, dan cara menghitungnya — dengan penyajian sumber
        yang jelas untuk setiap aturan.</p>

        <div class="rounded-xl border-l-4 border-accent bg-accent-soft p-4">
            <p class="font-semibold text-primary">Disclaimer penting</p>
            <p class="mt-1">Al-Waris adalah <strong>alat bantu edukasi dan simulasi</strong>, <strong>bukan</strong> pengganti
            penilaian ahli faraid, kiai/tokoh agama, atau nasihat hukum untuk sengketa nyata.
            Hasil di halaman ini berlabel DEMO dan masih memerlukan verifikasi pada Tahap pengembangan selanjutnya.</p>
        </div>

        <h2 class="mt-6 text-lg font-semibold text-primary">PWA (Progressive Web App)</h2>
        <p>Al-Waris dirancang agar dapat <strong>diinstal di layar beranda HP</strong> sebagai PWA.
        Perlu dicatat: PWA <strong>bukan aplikasi Android native (APK)</strong> — ia bekerja lewat browser
        (Chrome, Edge, Safari 16.4+), menyediakan ikon, mode layar penuh, dan opsi daring/luring terbatas.</p>

        <h2 class="mt-6 text-lg font-semibold text-primary">Lini masa proyek</h2>
        <ol class="list-decimal space-y-1 pl-5">
            <li><strong>Tahap 1 — Fondasi</strong>: skeleton, arsitektur, docs, mesin pratinjau.</li>
            <li><strong>Tahap 2 — Mesin Faraid</strong>: 'aul, radd, gawalan, kasus kompleks.</li>
            <li><strong>Tahap 3 — Riwayat, Akun &amp; Keamanan</strong>.</li>
            <li><strong>Tahap 4 — PWA, penyempurnaan alur &amp; privasi</strong> (saat ini): aplikasi dapat
            dipasang sebagai PWA, riwayat yang lebih informatif, dan penguatan keamanan.
            <a href="{{ route('install') }}" class="text-primary underline">Cara memasang di perangkat →</a></li>
            <li><strong>Tahap 5 — UAT &amp; Rilis</strong>.</li>
        </ol>

        <h2 class="mt-6 text-lg font-semibold text-primary">Kontak &amp; umpan balik</h2>
        <p>Untuk saran, koreksi teori, atau laporan bug, silakan hubungi pemilik produk
        (detail pengenal akan disediakan setelah produk disetujui publik).</p>
    </div>
</section>
@endsection
