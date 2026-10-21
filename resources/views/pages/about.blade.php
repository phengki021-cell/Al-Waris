@extends('layouts.app')

@section('title', 'Tentang')

@section('content')
<section class="max-w-3xl">
    <h1 class="text-2xl font-bold md:text-3xl">Tentang Warisku</h1>

    <div class="mt-5 space-y-4 text-sm leading-relaxed text-ink/90">
        <p><strong>Warisku</strong> adalah website edukasi dan kalkulator pembagian harta warisan Islam
        (ilmu <em>faraid</em>) berbahasa Indonesia. Proyek ini bertujuan membantu masyarakat mempelajari dasar-dasar
        faraid — ahli waris, bagian-bagian warisan, dan cara menghitungnya — dengan penyajian sumber
        yang jelas untuk setiap aturan.</p>

        <div class="rounded-xl border-l-4 border-accent bg-accent-soft p-4">
            <p class="font-semibold text-primary">Disclaimer penting</p>
            <p class="mt-1">Warisku adalah <strong>alat bantu edukasi dan simulasi</strong>, <strong>bukan</strong> pengganti
            penilaian ahli faraid, kiai/tokoh agama, atau nasihat hukum untuk sengketa nyata.
            Hasil di halaman ini berlabel DEMO dan masih memerlukan verifikasi pada Tahap pengembangan selanjutnya.</p>
        </div>

        <h2 class="mt-6 text-lg font-semibold text-primary">PWA (Progressive Web App)</h2>
        <p>Warisku dirancang agar dapat <strong>diinstal di layar beranda HP</strong> sebagai PWA.
        Perlu dicatat: PWA <strong>bukan aplikasi Android native (APK)</strong> — ia bekerja lewat browser
        (Chrome, Edge, Safari 16.4+), menyediakan ikon, mode layar penuh, dan opsi daring/luring terbatas.</p>

        <h2 class="mt-6 text-lg font-semibold text-primary">Lini masa proyek</h2>
        <ol class="list-decimal space-y-1 pl-5">
            <li><strong>Tahap 1 — Fondasi</strong> (saat ini): skeleton, arsitektur, docs, mesin pratinjau.</li>
            <li><strong>Tahap 2 — Mesin Faraid</strong>: 'aul, radd, gawalan, kasus kompleks.</li>
            <li><strong>Tahap 3 — Riwayat, Akun &amp; Keamanan</strong>.</li>
            <li><strong>Tahap 4 — Konten Edukasi &amp; PWA</strong>.</li>
            <li><strong>Tahap 5 — UAT &amp; Rilis</strong>.</li>
        </ol>

        <h2 class="mt-6 text-lg font-semibold text-primary">Kontak &amp; umpan balik</h2>
        <p>Untuk saran, koreksi teori, atau laporan bug, silakan hubungi pemilik produk
        (detail pengenal akan disediakan setelah produk disetujui publik).</p>
    </div>
</section>
@endsection
