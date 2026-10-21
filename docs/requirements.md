# Dokumen Kebutuhan — Warisku

> **Status:** Tahap 1 (Fondasi) — terakhir diperbarui: 21 Oktober 2026
> Nama produk sementara: **Warisku** (dapat diubah kemudian).

## 1. Tujuan Proyek

Warisku adalah **website edukasi dan kalkulator pembagian harta warisan Islam (ilmu faraid)** berbahasa Indonesia yang:

1. Membantu masyarakat mempelajari ilmu faraid (hukum pembagian warisan dalam Islam).
2. Menyediakan simulasi perhitungan pembagian harta warisan secara otomatis.
3. Responsif di HP, tablet, dan desktop.
4. Dirancang agar dapat diinstal sebagai **PWA** (*Progressive Web App*) di HP.

### Batasan penting (disclaimer)
- Aplikasi adalah **alat bantu edukasi dan simulasi**, **bukan** pengganti penilaian ahli faraid, kiai/tokoh agama, atau nasihat hukum untuk sengketa nyata.
- PWA yang diinstal ke beranda HP **bukan file APK native**; itu adalah pintasan web dengan perilaku aplikasi. Dokumen ini dan README tidak boleh menjanjikan output APK.

## 2. Menu Utama yang Direncanakan

| Menu | Slug | Tujuan |
|---|---|---|
| Beranda | `/` | Ringkasan produk, CTA ke simulasi dan materi |
| Ahli Waris | `/ahli-waris` | Daftar & penjelasan golongan ahli waris (dzawil furudh, ashabah, dzawil arham) |
| Bagian-Bagian Warisan | `/bagian-warisan` | Daftar bagian pasti (1/2, 1/4, 1/8, 2/3, 1/3, 1/6) besertaserta-an |
| Cara Menghitung | `/cara-menghitung` | Materi langkah perhitungan (amal ummi, 'aul, radd, sbah, dsb.) |
| Hitung Warisan Otomatis | `/hitung-warisan` | Kalkulator interaktif (Livewire) + simpan riwayat |
| Riwayat Perhitungan | `/riwayat` | Daftar perhitungan yang disimpan (per pengguna atau sesi) |
| Tentang | `/tentang` | Profil, disclaimer, kontak |
| Sumber/Referensi | `/referensi` | Daftar sumber aturan & referensi (KHI, kitab, fatwa, dsb.) |
| Kebijakan Privasi | `/kebijakan-privasi` | Kebijakan data |

## 3. Kebutuhan Fungsional

### 3.1 Tahap 1 — Fondasi (saat ini)
- **FR1.1** Skeleton Laravel + Livewire 3 + Tailwind CSS berjalan lokal.
- **FR1.2** Route bernama konsisten untuk sembilan halaman di atas; tidak ada konflik route.
- **FR1.3** Layout dasar: header/navigation responsif, footer, palet hijau tua `#123D32`, aksen emas `#D8B66A`, latar `#F5F7F4`, teks `#263238`.
- **FR1.4** Migration awal + seeder data demonstrasi berlabel `DEMO`.
- **FR1.5** Test awal (route & fitur inti mesin bagian) berjalan hijau.
- **FR1.6** Konfigurasi PWA (manifest, ikon, service worker dasar) disiapkan/ direncanakan — perilaku penuh offline pada Tahap berikutnya.

### 3.2 Tahap berikutnya (ringkas)
- Majemuk heir chooser + mesin hitung penuh ('ul/radd/gawalan/hisab-masalah).
- Simpan & muat riwayat perhitungan (user login atau token sesi anonim).
- Konten edukasi lengkap + provenance tiap aturan (**setiap bagian warisan harus dapat menampilkan sumbernya**).
- PWA offline shell.

## 4. Kebutuhan Non-Fungsional

| Kategori | Kebutuhan |
|---|---|
| Bahasa | Antarmuka 100% Bahasa Indonesia (Istilah fiqh disertai transliterasi & istilah Arab jika relevan) |
| Responsivitas | Mobile-first; HP (≥320px), tablet, desktop |
| Aksesibilitas | Kontras teks AA, ukuran sentuh ≥44px, fokus keyboard yang terlihat |
| Kinerja | TTI < 3 detik pada 3G cepat; Tailwind purge; gambar ikon PWA ≤500KB total |
| Keamanan | Server-side validation, CSRF (default Laravel), rate limiting pada form publik, kebijakan untuk operasi data milik user, XSS-safe output |
| Akurasi angka | Tidak memakai float sebagai basis pembagian — pecahan direpresentasikan sebagai **rasional (numerator/denominator, bilangan bulat)** atau decimal milik peradilan |
| Privasi | Riwayat anonim dihapus otomatis setelah N hari (diatur env), tidak menjual data, kapan pun data terkumpul dijelaskan di `/kebijakan-privasi` |
| PWA | Manifest + ikon + service worker; HTTPS diajukan saat deployment; darurat: SW tidak boleh menyimpan hasil perhitungan pribadi/tanpa label sensitif |
| Pengujian | Pest untuk unit & feature; server/domain engine harus 100% terasa tanpa UI |
| Portabilitas | Dev pada Windows 11 (dengan SQLite), dapat dipindah ke MySQL/MariaDB via `.env` |

## 5. Keterbatasan dan "Bukankah?"

| Tier | Fitur |
|---|---|
| Terverifikasi (Tahap 1) | Fondasi Laravel+Livewire; route; migration SQLite; mesin bagian (faraid untuk kasus dasar) |
| USULAN / TAHAP BERIKUT | E-HISAB MATAN AMAL ummi penuh ('ul, radd, sbah, keputusan masalah remaja) |
| TAHAP BERIKUT | Akun login (email) & riwayat tersimpan server-side |
| TAHAP BERIKUT | Offline PWA penuh |
| BUTUH KEPUTUSAN | **Provenance sumber**: dialog mana yang diikuti (mengikuti murud klasikğin KHI vs. kontekstual)? Dibahas di `docs/assumptions-and-risks.md` |
| TIDAK ADA | E-signature, integrasi.assertNotah & kanak waris, integrasi SIW/MA (di luar jangcata kata) |

## 6. Kriteria Penerimaan Tahap 1

1. Semua toolchain terinstall dan terverifikasi versi.
2. `composer create-project` & dependensi sukses.
3. `php artisan migrate:fresh --seed` sukses pada SQLite.
4. `php artisan serve` menjalankan aplikasi; 9 route utama merespons **200**.
5. `npm install` & `npm run build` sukses (asset Tailwind).
6. `php artisan test` (Pest) — seluruh test hijau.
7. Dokumen kebutuhan, arsitektur, route map, desain database, asumsi & risiko, implementasi plan, README — semua tersedia.
8. Laporan akhir menyebut kondisi awal, versi teknologi, file yang berubah, perintah instalasi, hasil test aktual, kendala, dan pekerjaan Tahap 2.
