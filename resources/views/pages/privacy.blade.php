@extends('layouts.app')

@section('title', 'Kebijakan Privasi')

@section('content')
<section class="max-w-3xl">
    <h1 class="text-2xl font-bold md:text-3xl">Kebijakan Privasi</h1>
    <p class="mt-2 text-xs text-ink/50">Terakhir diperbarui: 21 Oktober 2026 (Tahap 1).</p>

    <div class="mt-5 space-y-4 text-sm leading-relaxed text-ink/90">
        <div>
            <h2 class="text-lg font-semibold text-primary">1. Data apa yang dikumpulkan</h2>
            <ul class="mt-1 list-disc space-y-1 pl-5">
                <li><strong>Proyek tahap pengembangan</strong>: bila digunakan di server pengembangan, data yang diketik ke
                kalkulator akan dianonimkan dan tidak disimpan.</li>
                <li><strong>Riwayat perhitungan (Tahap 2–3)</strong>: bila Anda meminta riwayat disimpan, Warisku menyimpan
                jumlah harta (angka), daftar ahli waris, dan hasil bagi — tanpa data pribadi yang wajib.</li>
                <li><strong>Log teknis standar</strong>: alamat IP/VPN, user agent, waktu akses (untuk keamanan).</li>
            </ul>
        </div>

        <div>
            <h2 class="text-lg font-semibold text-primary">2. Untuk apa data digunakan</h2>
            <p>Menyimpan riwayat perhitungan bila Anda menginginkannya (Tahap 2/3), dan untuk keamanan platform.</p>
        </div>

        <div>
            <h2 class="text-lg font-semibold text-primary">3. Berapa lama data disimpan</h2>
            <p>Riwayat anonim dijadwalkan dihapus otomatis setelah periode tertentu (periode akan diumumkan saat tersedia).
            Anda dapat menghapus riwayat sendiri dari halaman Riwayat.</p>
        </div>

        <div>
            <h2 class="text-lg font-semibold text-primary">4. Hak Anda</h2>
            <p>Anda dapat mengajukan permintaan hapus riwayat maupun melihat ringkasan data yang disimpan tentang diri Anda.</p>
        </div>

        <div>
            <h2 class="text-lg font-semibold text-primary">5. Cookies, PWA, dan cache</h2>
            <p>Laravel menggunakan cookie CSRF dan session standard. <strong>Service worker PWA tidak menyimpan hasil
            perhitungan pribadi</strong> di cache offline — hasil disimpan hanya bila Anda secara eksplisit memintanya.</p>
        </div>

        <div>
            <h2 class="text-lg font-semibold text-primary">6. Perubahan kebijakan</h2>
            <p>Jika terjadi perubahan, versi dan tanggal perubahan dicatat di bagian atas halaman ini.</p>
        </div>
    </div>
</section>
@endsection
