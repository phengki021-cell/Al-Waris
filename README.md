---
title: Al-Waris Demo
emoji: 🕌
colorFrom: green
colorTo: indigo
sdk: docker
app_port: 8080
pinned: false
---

# Al-Waris — Demo

> Website edukasi & kalkulator pembagian harta warisan Islam (ilmu **faraid**) berbahasa Indonesia.
> Status: **Tahap 5 — audit final, deployment & petunjuk instalasi** (Tahap 1–4 selesai).

![status](https://img.shields.io/badge/status-Tahap%205--Audit%20Final-green)

## Tentang Proyek

Al-Waris membantu masyarakat:
1. **Belajar** ilmu faraid (ahli waris, bagian-bagian warisan, cara menghitung) — dengan sumber/referensi yang jelas.
2. **Simulasi** pembagian harta warisan secara otomatis dengan pecahan rasional eksak (tanpa floating point), dilengkapi jejak audit langkah perhitungan, cek konsistensi total, dan label versi mesin hitung.
3. **Riwayat** perhitungan anonim per sesi browser — detail, hapus per-baris, versi mesin tercatat.
4. **PWA**: situs dapat dipasang dari browser (Android Chrome, Edge desktop, iOS Safari) — **bukan aplikasi native, tidak ada file APK**.

> ⚠️ **Disclaimer:** Al-Waris adalah **alat bantu edukasi dan simulasi** (hasil berlabel DEMO sampai
> validasi ahli faraid selesai), bukan pengganti penilaian ahli faraid, kiai/tokoh agama,
> atau nasihat hukum untuk sengketa nyata.

## Stack Teknis (terverifikasi)

| Komponen | Versi | Catatan |
|---|---|---|
| PHP | 8.4.25 | diinstal via winget (`PHP.PHP.8.4`) |
| Laravel | 13.38.0 | monolith + Livewire |
| Livewire | 4.4.7 | kalkulator & riwayat |
| Tailwind CSS | 4 (via Vite 8) | |
| Alpine.js | tersedia via Livewire | |
| Database dev | **SQLite** | zero-config; produksi MySQL/MariaDB via `.env` |
| Testing | Pest 4.7 | 73 test / 250 assertion lulus |
| Static analysis | PHPStan (Larastan) level 5 | 0 error |
| Code style | Laravel Pint 1.32 | bersih |
| Node.js | 24.x LTS | untuk Vite/Tailwind |

## Memulai (Windows 11)

### Prasyarat
PHP 8.3+ (ekstensi: `ctype, curl, dom, fileinfo, json, mbstring, openssl, pdo_sqlite, tokenizer, xml, intl`), Composer (`tools/composer.phar` disertakan), Node.js 20+.

### Instalasi
```powershell
cd warisku

# Dependencies PHP
php -d memory_limit=1G tools\composer.phar install

# Dependencies JS
npm.cmd install

# Salin environment bila belum ada .env, lalu generate APP_KEY
Copy-Item .env.example .env
php artisan key:generate

# Database (SQLite) + data demo berlabel DEMO
php artisan migrate:fresh --seed

# Aset frontend (build)
npm.cmd run build

# Jalankan dev server
php artisan serve            # http://127.0.0.1:8000
```

Perintah harian development (dua terminal):
- Terminal 1: `php artisan serve`
- Terminal 2: `npm.cmd run dev` (Tailwind/Vite watch)

### Test & QA
```powershell
php artisan test                       # Pest: unit + feature + regression mesin
vendor\bin\pint --test                 # Psikus code style
php vendor\bin\phpstan analyse         # static analysis (Larastan, level 5)
```

### Backup / restore database
```powershell
# Backup (SQLite: cukup salin file saat server tidak menulis)
Copy-Item database\database.sqlite backup\database-$(Get-Date -Format yyyyMMdd-HHmm).sqlite

# Restore: matikan dev server, salin berkas kembali, lalu jalankan
php artisan migrate
```
Untuk MySQL/PostgreSQL produksi: `mysqldump`/`pg_dump` per jadwal hosting — lihat [docs/deployment.md](docs/deployment.md).
Panduan domain gratis (Student Pack) & subdomain kampus `ui.ac.id`: [docs/deployment_domain_ui.md](docs/deployment_domain_ui.md).

## Struktur Direktori Utama

```
warisku/
├── app/
│   ├── Domain/Faraid/       # mesin hitung faraid, pure logic (tanpa Laravel)
│   ├── Livewire/            # kalkulator & riwayat
│   ├── Http/Controllers/    # halaman edukasi + PwaController
│   └── Services/            # orkestrasi hitung + penyimpanan riwayat
├── database/                # migration, factories, seeders (data DEMO)
├── docs/                    # SEMUA DOKUMEN PROYEK DI SINI (incl. deployment.md)
├── public/icons/            # ikon PWA 192/512, maskable, apple-touch, favicon
├── resources/pwa/sw.js      # service worker (disajikan via rute /sw.js)
├── resources/views/
├── routes/web.php
├── tools/                   # composer.phar + shim Windows
└── tests/
```

## Route Utama

| Route | Nama | Keterangan |
|---|---|---|
| `/` | `home` | Beranda |
| `/ahli-waris` | `heirs` | Katalog ahli waris |
| `/bagian-warisan` | `shares` | Bagian-bagian warisan |
| `/cara-menghitung` | `how-to` | Panduan menghitung |
| `/hitung-warisan` | `calculate` | Kalkulator (Livewire) |
| `/riwayat` | `history` | Riwayat perhitungan anonim |
| `/tentang` | `about` | Tentang + disclaimer |
| `/referensi` | `sources` | Sumber/Referensi |
| `/kebijakan-privasi` | `privacy` | Kebijakan privasi |
| `/instalasi` | `install` | Panduan pasang PWA |
| `/offline` | `offline` | Fallback offline service worker |
| `/manifest.json` | `manifest` | Web app manifest |
| `/sw.js` | `sw` | Service worker |

Detail lengkap di [docs/route-map.md](docs/route-map.md) • PWA & cache di [docs/pwa-and-cache.md](docs/pwa-and-cache.md) • deployment di [docs/deployment.md](docs/deployment.md) • domain kampus .ui.ac.id di [docs/deployment_domain_ui.md](docs/deployment_domain_ui.md).

## Peta Jalan

- ✅ **Tahap 1 — Fondasi**
- ✅ **Tahap 2 — Mesin Faraid & Kalkulator**
- ✅ **Tahap 3 — Riwayat & Keamanan**
- ✅ **Tahap 4 — PWA, alur, privasi, ganti nama Al-Waris**
- 🏁 **Tahap 5 — Audit final, deployment & petunjuk instalasi** ← saat ini
- ⏳ **Tahap 6 — UAT ahli faraid & rilis publik**

## Kontributing & Lisensi

Lihat [docs/implementation-plan.md](docs/implementation-plan.md). Lisensi TBD oleh pemilik proyek.
