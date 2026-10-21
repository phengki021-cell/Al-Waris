# Warisku

> Website edukasi & kalkulator pembagian harta warisan Islam (ilmu **faraid**) berbahasa Indonesia.
> Nama sementara: **Warisku** (dapat diubah kemudian).

![status](https://img.shields.io/badge/status-Tahap%201%20--%20Fondasi-blue)

## Tentang Proyek

Warisku membantu masyarakat:
1. **Belajar** ilmu faraid (ahli waris, bagian-bagian warisan, cara menghitung) — dengan sumber/referensi yang jelas.
2. **Simulasi** pembagian harta warisan secara otomatis dengan pecahan rasional eksak (tanpa floating point).
3. **Riwayat** perhitungan tersimpan per pengguna (Tahap berikutnya).

> ⚠️ **Disclaimer:** Warisku adalah **alat bantu edukasi dan simulasi**, bukan pengganti
> penilaian ahli faraid, kiai/tokoh agama, atau nasihat hukum untuk sengketa nyata.
>
> PWA yang bisa ditaruh di beranda HP **bukan APK native** — itu adalah aplikasi web
> yang dapat diinstal ke layar utama browser.

## Stack Teknis (terverifikasi)

| Komponen | Versi | Catatan |
|---|---|---|
| PHP | 8.4.25 | diinstal via winget (`PHP.PHP.8.4`) |
| Laravel | 13.38.0 | `composer create-project laravel/laravel` |
| Livewire | 3.x | `composer require livewire/livewire` |
| Tailwind CSS | 4.x | via Vite |
| Alpine.js | 3.x (bundling Livewire) | untuk UI kecil |
| Database dev | **SQLite** | zero-config; produksi dapat MySQL/MariaDB via `.env` |
| Testing | Pest | unit + feature |
| Code style | Laravel Pint | |
| Node.js | 24.20.0 LTS | untuk Vite/Tailwind |

## Memulai (Windows 11)

### Prasyarat
PHP 8.3+ (ekstensi: `ctype, curl, dom, fileinfo, json, mbstring, openssl, pdo_sqlite, tokenizer, xml, intl`), Composer (di `tools/composer.phar`), Node.js 20+.

> Catatan lingkungan ini: PHP winget dipasang di
> `%LOCALAPPDATA%\Microsoft\WinGet\Packages\PHP.PHP.8.4_...` dengan `php.ini` khusus
> (ekstensi telah diaktifkan). Setiap sesi shell baru perlu menambahkan folder itu
> ke `PATH` sampai shell di-restart.

### Instalasi
```powershell
cd warisku

# Dependencies PHP
php -d memory_limit=1G tools\composer.phar install

# Dependencies JS
npm.cmd install

# Environment sudah tersedia: .env (APP_KEY sudah digenerate saat create-project)

# Database (SQLite): file database/database.sqlite sudah berisi migrasi default
php artisan migrate:fresh --seed

# Aset frontend (build)
npm.cmd run build

# Jalankan dev server
php artisan serve            # default http://127.0.0.1:8000
```

Perintah harian untuk development (dua terminal):
- Terminal 1: `php artisan serve`
- Terminal 2: `npm.cmd run dev` (Tailwind/Vite watch)

### Menjalankan test & QA
```powershell
php artisan test             # Pest
vendor\bin\pint.bat --test   # code style check
```

## Struktur Direktori Utama

```
warisku/
├── app/
│   ├── Domain/Faraid/       # mesin hitung faraid, pure logic (Tahap 2)
│   ├── Livewire/            # komponen interaktif
│   └── Http/Controllers/    # controller render
├── database/                # migration, factories, seeders (data DEMO)
├── docs/                    # SEMUA DOKUMEN PROYEK DI SINI
│   ├── requirements.md
│   ├── architecture.md
│   ├── route-map.md
│   ├── database-design.md
│   ├── assumptions-and-risks.md
│   └── implementation-plan.md
├── public/                  # manifest.json (PWA, Tahap 4)
├── resources/views/
├── routes/web.php
├── tools/                   # composer.phar + shim Windows
└── tests/
```

## Route Utama

| Route | Nama | Keterangan |
|---|---|---|
| `/` | `home` | Beranda |
| `/ahli-waris` | `heirs` | Daftar ahli waris |
| `/bagian-warisan` | `shares` | Bagian-bagian warisan |
| `/cara-menghitung` | `how-to` | Cara menghitung |
| `/hitung-warisan` | `calculate` | Kalkulator (Livewire) |
| `/riwayat` | `history` | Riwayat perhitungan (Tahap 2) |
| `/tentang` | `about` | Tentang + disclaimer |
| `/referensi` | `sources` | Sumber/Referensi |
| `/kebijakan-privasi` | `privacy` | Kebijakan privasi |

Detail lengkap di [docs/route-map.md](docs/route-map.md).

## Dokumentasi

| Dokumen | Isi |
|---|---|
| [docs/requirements.md](docs/requirements.md) | Kebutuhan fungsional & non-fungsional |
| [docs/architecture.md](docs/architecture.md) | Keputusan arsitektur & change log |
| [docs/route-map.md](docs/route-map.md) | Daftar route & naming convention |
| [docs/database-design.md](docs/database-design.md) | Rancangan tabel & tipe data |
| [docs/assumptions-and-risks.md](docs/assumptions-and-risks.md) | Asumsi, risiko, dependensi |
| [docs/implementation-plan.md](docs/implementation-plan.md) | Rencana per tahap |

## Peta Jalan

1. **Tahap 1 — Fondasi** ← saat ini
2. **Tahap 2 — Mesin Faraid & Kalkulator**
3. **Tahap 3 — Riwayat & Akun**
4. **Tahap 4 — Konten Edukasi & PWA**
5. **Tahap 5 — UAT & Rilis**

## Kontributing & Lisensi

Lihat [docs/implementation-plan.md](docs/implementation-plan.md). Lisensi TBD oleh pemilik proyek.
