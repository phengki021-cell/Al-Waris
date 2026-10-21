# Rencana Implementasi — Al-Waris

> Terakhir diperbarui: 21 Oktober 2026. Tahap 1 sedang berjalan.

## Tahap 1 — Audit & Fondasi (SAAT INI)

### 1.2 Checklist lingkungan — HASIL TAHEAP 1
- [x] Audit: konfirmasi tidak ada proyek existing; proyek baru di `C:\Users\LENOVO\Downloads\Al-Waris`.
- [x] Keputusan stack, database dev (SQLite), lokasi proyek — dikonfirmasi ke pemilik.
- [x] Instalasi PHP 8.4.25, Composer 2.10.3 (phar), Node 24.20.0, Git 2.55.0 via winget/unduhan.
- [x] `composer create-project laravel/laravel` sukses → Laravel 13.38.0.
- [x] `composer require livewire/livewire (4.4.7), pestphp/pest (4.7.8), laravel/pint (1.32), laravel/boost (2.10)`.
- [x] `npm install && npm run build` sukses (Tailwind 4 + Vite).
- [x] `php artisan migrate:fresh --seed` sukses (SQLite; 10 migrasi, 4 seeder DEMO).
- [x] 9 halaman + `/kebijakan-privasi` merespons 200; `/tidakada` = 404 (server nyata).
- [x] Pest: 10 test, 18 assertions — hijau; Pint --test hijau.
- [x] Dokumen: requirements, architecture, route-map, database-design, assumptions-and-risks, implementation-plan, pwa-plan, README.

### 1.3 Acceptance criteria Tahap 1
Lihat `docs/requirements.md` §6 — terpenuhi.

## Tahap 2 — Mesin Faraid & Kalkulator Dasar
1. Domain `app/Domain/Faraid`:
   - `RationalNumber` (immutable, GCD, aritmetika, displat).
   - `HeirCatalog` (bind ke DB + cache; dapat konsisten seeding).
   - `FaraidEngine` (pipeline: filter wafat, deteksi blokir/hijab, aturan dzawil furudh, ashabah dgn ta'sib, deteksi aul/radd Kasus dasar; output: method + list_share + provenance list).
   - Kalkulator kasus: amal ummi + aul + radd (kasus dasar, family nucleus: suami/isteri, anak laki/perempuan, ayah, ibu).
2. Livewire `Calculator` v1: input total harta + banks + heirs (chest auto-selection); validasi server-side; result reloaded.
3. Form pencatatan riwayat: default DRAFT; tombol simpan -> calculation records.
4. Konten `bagian-warisan` mapping menuju kaidah & sumber (via shares master+sources).
5. Test unit deteksi metode pada 20+ kasus bangun standar (fathul qarib-style mini cases).
6. Validasi tiga kelas hasil dengan guru/ahli faraid (manual QA) sebelum dilanjutkan.

## Tahap 3 — Riwayat, Akun & Kebijakan
1. Multi-user (login, registrasi) atau tetap anonim-token+cleanup job.
2. Riwayat: daftar, detail (readonly), hapus (policy).
3. Rate limit: per IP (100/menit) untuk halaman publik; kalkulator激烈的 30/min.
4. `audit_records` (opsional, keputusan pemilik).
5. Schema prod: konversi n/a jika MySQL pilihan produksi: dokumentasi `.env.example.mysql`, transaksi + index analyzer.

## Tahap 4 — Edukasi Konten & PWA
1. Konten penuh: Ahli Waris (per golongan, hijab-ta'sib tabel), Bagian-Bagian Warisan (masing-masing dengan sumber), Cara Menghitung (amal ummi + aul + radd + gawalan + dhul al-furudh), tentang methodology + disclaimers.
2. PWA production:
   - `manifest.json` + ikon (pre-computed 192/512/maskable, ≤500KB total).
   - `sw.js` versi'd & strategi caching disebut architecture §4.
   - HTTPS deployment checklist + LItern site-audit lighthouse PWA installable.
3. Aksesibilitas audit (mobile-first, kontras, hit-target).

## Tahap 5 — Polishing, Uji Konsumen, & Rilis
1. UAT scratch dgn ahli faraid/kiai (dalam uji 10–20 kasus nyata setelah Kel trabalhar).
2. Performance pass: asset, page speed demo.
3. Security pass: CSRF, rate limit, data-limit, info-leak check.
4. Rilis: tag `v1.0`, README final, checklist deployment, UI versioning/roadmap kesatu manusiana.

## Mitigasi Drift
- Setiap selesai tahap: perbarui checklist di atas + architecture.md Change Log + assumptions-and-risks.md.
- Semua dokumen disimpan hanya di `docs/` (sumber kebenaran unt好久 docs).
