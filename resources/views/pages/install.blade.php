@extends('layouts.app')

@section('title', 'Pasang Al-Waris di Perangkat')

@section('content')
<div class="mx-auto max-w-3xl">
    <h1 class="text-2xl font-bold md:text-3xl">Pasang Al-Waris di Perangkat</h1>
    <p class="mt-3 text-ink/70">
        Al-Waris adalah <strong>PWA (Progressive Web App)</strong> — situs web yang dapat
        <strong>dipasang langsung dari browser</strong> ke layar beranda, tanpa perlu mengunduh
        berkas terpisah. <strong>Al-Waris bukan aplikasi native dan tidak menyediakan berkas
        APK</strong>; semua fitur berjalan di browser.
    </p>

    <div class="mt-6 rounded-xl bg-paper p-5 ring-1 ring-black/5">
        <h2 class="font-semibold text-primary">Android — Chrome / browser Chromium</h2>
        <ol class="mt-3 list-decimal space-y-1 pl-5 text-sm text-ink/80">
            <li>Buka <strong>al-waris</strong> di Chrome (alamat berakhiran HTTPS).</li>
            <li>
                Tekan ikon <strong>⊕ Instal</strong> di bilah alamat,
                <em>atau</em> menu ⋮ → <strong>“Instal aplikasi”</strong>.
            </li>
            <li>
                Beberapa browser memakai label lain: <em>“Tambahkan ke layar utama”</em>,
                <em>“Pasang”</em>, atau <em>“Install app”</em> — maknanya sama: pasang sebagai
                aplikasi web.
            </li>
            <li>Konfirmasi. Ikon Al-Waris muncul di layar utama dan membuka jendela sendiri.</li>
        </ol>
    </div>

    <div class="mt-4 rounded-xl bg-paper p-5 ring-1 ring-black/5">
        <h2 class="font-semibold text-primary">Komputer (Chrome / Edge)</h2>
        <ol class="mt-3 list-decimal space-y-1 pl-5 text-sm text-ink/80">
            <li>Klik ikon <strong>⊕ Instal</strong> di bilah alamat <em>atau</em> menu → “Pasang Al-Waris…”.</li>
            <li>Pasangan aplikasi muncul di daftar aplikasi / pintasan desktop.</li>
        </ol>
    </div>

    <div class="mt-4 rounded-xl bg-paper p-5 ring-1 ring-black/5">
        <h2 class="font-semibold text-primary">iOS — Safari</h2>
        <ol class="mt-3 list-decimal space-y-1 pl-5 text-sm text-ink/80">
            <li>Tekan tombol <strong>Bagikan</strong> (kotak dengan panah).</li>
            <li>Pilih <strong>“Tambah ke Layar Utama”</strong>.</li>
            <li>
                Catatan: di iOS, tampilan bisa tetap di dalam Safari untuk beberapa konten dan
                kemampuan offline berbeda — label &amp; perilaku dapat berbeda antar-browser.
            </li>
        </ol>
    </div>

    <div class="mt-6 rounded-lg border border-yellow-300 bg-yellow-50 p-4 text-sm text-yellow-900">
        <strong>Syarat penting:</strong>
        <ul class="mt-2 list-disc pl-5 space-y-1">
            <li>Instalasi PWA memerlukan koneksi <strong>HTTPS</strong> saat deployment.</li>
            <li>
                Saat pengembangan lokal, browser memberi pengecualian bagi
                <strong>http://localhost</strong> (dan 127.0.0.1) untuk menguji tanpa sertifikat.
            </li>
            <li>
                Jika tombol instal tidak muncul, pastikan browser mendukung PWA — penamaan tombol
                berbeda antar-browser (Chrome, Edge, Samsung Internet, dsb).
            </li>
        </ul>
    </div>

    <p class="mt-6 text-sm text-ink/70">
        Lihat juga
        <a href="{{ route('privacy') }}" class="text-primary underline">Kebijakan Privasi</a> untuk
        bagaimana data riwayat diperlakukan — termasuk alasan kalkulator &amp; riwayat tidak di-cache.
    </p>
</div>
@endsection
