@extends('layouts.app')

@section('title', 'Kebijakan Privasi')

@section('content')
<section class="max-w-3xl">
    <h1 class="text-2xl font-bold md:text-3xl">Kebijakan Privasi</h1>
    <p class="mt-2 text-xs text-ink/50">Terakhir diperbarui: 21 Oktober 2026 (Tahap 4).</p>

    <div class="mt-5 space-y-4 text-sm leading-relaxed text-ink/90">
        <div>
            <h2 class="text-lg font-semibold text-primary">1. Data apa yang dikumpulkan</h2>
            <ul class="mt-1 list-disc space-y-1 pl-5">
                <li><strong>Proses kalkulasi tidak wajib menyimpan apa pun</strong>: menghitung warisan tidak
                mengharuskan Anda menyerahkan data pribadi, tidak ada nama, tidak ada akun, tidak ada login.</li>
                <li><strong>Riwayat perhitungan = data minimal</strong>: hanya bila Anda mengklik
                <em>“Simpan ke Riwayat”</em>, Al-Waris menyimpan angka harta, daftar ahli waris, dan hasil bagi.
                Riwayat ditandai dengan <em>token sesi peramban</em> — anonim, tidak terhubung ke identitas siapa pun.
                Tidak menyimpan lebih banyak daripada yang dibutuhkan untuk menampilkan ulang riwayat.</li>
                <li><strong>Log teknis standar</strong>: alamat IP, user agent, waktu akses (untuk keamanan &amp;
                laju request terbatas).</li>
            </ul>
        </div>

        <div>
            <h2 class="text-lg font-semibold text-primary">2. Di mana riwayat disimpan (batas penyimpanan)</h2>
            <ul class="mt-1 list-disc space-y-1 pl-5">
                <li>Riwayat disimpan <strong>di server pengembangan terhadap token sesi browser Anda</strong>
                (anonim), <em>bukan</em> pada perangkat, dan <em>bukan</em> pada akun pengguna (tidak ada akun).</li>
                <li>Hasil kalkulasi yang <strong>belum disimpan tidak keluar dari browser</strong> — dan tidak
                pernah ditulis ke URL, log aplikasi, cache service worker, atau storage publik.</li>
                <li>Anda dapat <strong>menghapus setiap entri kapan saja</strong> dari halaman Riwayat.
                Menghapus sesi browser (cookie) juga memutus akses ke riwayat tersebut.</li>
                <li><strong>Belum ada sinkronisasi server/akun</strong>. Bila suatu saat fitur sinkron atau akun
                ditambahkan, Al-Waris akan <strong>minta persetujuan Anda terlebih dahulu</strong> sebelum data
                dikirim ke server — pendekatan ini dicegah secara desain hari ini.</li>
            </ul>
        </div>

        <div>
            <h2 class="text-lg font-semibold text-primary">3. Service worker, cache, dan PWA</h2>
            <ul class="mt-1 list-disc space-y-1 pl-5">
                <li>Service worker Al-Waris <strong>hanya meng-cache aset statis aman</strong>: berkas build
                (CSS/JS ber-hash), ikon, dan halaman offline.</li>
                <li>Halaman <strong>kalkulator dan tabel riwayat selalu diambil dari jaringan</strong> (network-only)
                — hasil kalkulasi <strong>tidak pernah tersimpan di cache service worker</strong>.</li>
                <li>Peramban mungkin menyimpan cookie Laravel standar (CSRF + session). Itu pula satu-satunya
                data di perangkat selain cache aset.</li>
                <li>Al-Waris adalah <strong>PWA</strong> — website yang dipasang dari browser, <strong>bukan
                aplikasi Android native dan bukan berkas APK</strong>.</li>
            </ul>
        </div>

        <div>
            <h2 class="text-lg font-semibold text-primary">4. Keamanan</h2>
            <ul class="mt-1 list-disc space-y-1 pl-5">
                <li>Seluruh input divalidasi di server. Endpoint mutasi dilindungi CSRF, kepala Livewire resmi, dan
                <strong>batas laju request</strong> (60/menit).</li>
                <li>Semua konten HTML-escaped; tidak ada API key/secret yang dikirim ke browser.</li>
                <li>Deployment PWA memerlukan <strong>HTTPS</strong> (pengecualian hanya untuk
                <code>localhost</code> saat pengembangan).</li>
            </ul>
        </div>

        <div>
            <h2 class="text-lg font-semibold text-primary">5. Hak Anda</h2>
            <p>Anda dapat menghapus riwayat sendiri kapan pun dari halaman Riwayat. Karena tidak ada data
            pribadi yang diminta, tidak ada profil yang dapat dilihat/hapus selain riwayat anonim sesi tersebut.</p>
        </div>

        <div>
            <h2 class="text-lg font-semibold text-primary">6. Perubahan kebijakan</h2>
            <p>Jika terjadi perubahan, versi dan tanggalnya dicatat di bagian atas halaman ini.</p>
        </div>
    </div>
</section>
@endsection
