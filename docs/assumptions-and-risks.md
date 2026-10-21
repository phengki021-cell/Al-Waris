# Asumsi, Risiko, dan Dependensi — Al-Waris

> Status: Tahap 1. Terakhir diperbarui: 21 Oktober 2026.

## 1. Kondisi Awal (Terverifikasi)

| Item | Hasil |
|---|---|
| Direktori kerja awal | `C:\Users\LENOVO\Downloads` — BUKAN repo; tidak ada proyek Laravel; diputuskan proyek baru di `C:\Users\LENOVO\Downloads\Al-Waris` |
| PHP | ❌ tidak terinstal → ✅ diinstal via winget (PHP.PHP.8.4) |
| Composer | ❌ tidak tersedia di winget → dipasang manual via `composer.phar` resmi (getcomposer.org) |
| Node.js / npm | ❌ → ✅ diinstal via winget (OpenJS.NodeJS.LTS 24.x) |
| Git | ❌ → ✅ diinstal via winget (Git.Git 2.x) |
| MySQL/MariaDB | ❌ tidak terinstal → dipilih SQLite untuk dev (zero-config); MySQL tetap satu opsi produksi via `.env` |
| OS dev | Windows 11 (powershell default) |

## 2. Asumsi (perlu dikonfirmasi owner jika berbeda)

1. **Privasi riwayat anonim**: Tahap 1 kalkulator tidak menyimpan perhitungan secara permanen; hanya tabel tersedia untuk riwayat bagi user yang login (Tahap 2/3). Riwayat anonim via token sesi disimpan sementara dan dijadwalkan dihapus otomatis (retensi bawaan 30 hari, dapat diubah via `.env`).
2. **Sumber hukum yang dipakai**: aturan dasar bagian dari QS An-Nisāʾ/An-Nahl dan Kompilasi Hukum Islam (KHI) Indonesia; kitab d e rujukan klasik (Fathul-Qarīb, al-Ikhtishar, dsb.) sebagai pelengkap — sumber mana yang *diutamakan* saat ada beda pendapat dapat dikonfirmasi pada Tahap 2.
3. **Kalkulator Tahap 1** menghitung kasus dasar (amal ummi) divalidasikan di bawah pengawasca as suk mana. Kasus 'aul, radd, gawalan, sbah, himarih duplex → Tahap 2 dst; UI akan menolak (atau menampilkan "Belum didukung") secara jujur.
4. **Nama proyek** sementara: *Al-Waris*; rebranding di kemudian hari dianggap terpisah.
5. **PWA**: hasil dapat diinstal di HP via Chrome/Edge/Safari-16.4+; **bukan APK native** — hal ini diutamakan didokumentasikan di Tentang dan README agar tidak salah pengertia.

## 3. Risiko & Mitigasi

| # | Risiko | Dampak | Likelihood | Mitigasi |
|---|---|---|---|---|
| R1 | Kesalahan logika faraid memberikan risiko keagamaan/hukum | Tinggi | Sedang | Domain engine testable; sumber di setiap aturan (provenance); disclaimer di semua halaman; verifikasi oleh ahli faraid sebelum rilis publik |
| R2 | Floating point menghasilkan pembagian tidak akurat | Tinggi | Rendah (mitigasi sejak desain) | Rasional (n/d) bilangan bulat; uang DECIMAL(20,2); test kasus bangun klasik |
| R3 | Winget/Composer gagal di Windows dev | Sedang | Rendah–Sedang | Fallback: unduhan manual; Laragon diindikasikan; menjalankan di WSL opsional |
| R4 | PWA disalahbesarkan sebagai APK | Rendah | Rendah | Copywriting jelas di Tentang y "bukan APK" |
| R5 | Data riwayat mengandung informasi pribadi | Sedang | Sedang | Data disimpan minimal; riwayat anonim disertai expiry; SW tidak cache perhitungan |
| R6 | User salah memasukkan jumlah ahli waris | Sedang | Tinggi | Validasi server-side + dan UI input yang memanopine; konfirmasi sebelum hasil |
| R7 | Livewire 3 XY pada PHP 8.4 kompatibilitas versi-paket future | Rendah | Rendah | Lock versi composer; test otomatis; pin di composer.lock |
| R8 | Tabel master (heirs, shares) diubah di kemudian hari → riwayat rusak | Sedang | Rendah | Denormalisasi code/name utama pada `calculation_participants` |
| R9 | Host mahal/free (deployment) tidak समodit HTS | Sedang | Sedang | Dokumentasikan certbot/hosting SSL; HTTPS wajib sebelum rilis |
| R10 | Intl/mbstring ekstensi PHP tidak aktif | Sedang | Sedang | On-board cek: `php -m`; composer.json `require ext-*`; fail-fast saat install/check `php artisan about` |

## 4. Dependensi Eksternal / Integrasi (Masa Depan)

| Tier | Item | Status |
|---|---|---|
| Core (dalam proyek) | PHP 8.4, Composer, Node 24, SQLite, Laravel 12, Livewire 3, Tailwind 4 | Terverifikasi Tahap 1 |
| Opsional | Auth email (Breeze/Fortify atau custom di Tahap 3) | Usulan |
| Opsional | Ikon PWA otomatis (script node) | Usulan |
| TIDAK dalam scope saat ini | e-signature, integrasi antrean MA/SIWS, CETd impostata | Dikecuali "usulan yang memerlukan keputusan pemilik produk" |
