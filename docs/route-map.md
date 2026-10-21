# Route Map — Al-Waris

> Status: Tahap 4. Halaman inti sudah interaktif penuh; PWA aktif.

## 1. Rute halaman (routes/web.php)

| Method | URI | Route name | Controller/Livewire | Status | Catatan |
|---|---|---|---|---|---|
| GET | `/` | `home` | `HomeController` | ✅ | Beranda: ringkasan materi + CTA ke kalkulator |
| GET | `/ahli-waris` | `heirs` | `HeirCatalogController` | ✅ | Katalog golongan ahli waris dari seeder |
| GET | `/bagian-warisan` | `shares` | `ShareCatalogController` | ✅ | Katalog bagian pasti (1/2, 1/4, 1/8, 1/3, 1/6) |
| GET | `/cara-menghitung` | `how-to` | `PageController` | ✅ | Langkah menghitung manual |
| GET | `/hitung-warisan` | `calculate` | `Livewire\Calculator` | ✅ | Kalkulator penuh (Tahap 4: ringkasan pra-hitung, ubah input, mulai baru); **network-only di SW** |
| GET | `/riwayat` | `history` | `Livewire\CalculationHistory` | ✅ | Riwayat anonim per sesi (detail expandable + hapus); **network-only di SW** |
| GET | `/tentang` | `about` | `PageController` | ✅ | Profil + lini masa + disclaimer |
| GET | `/referensi` | `sources` | `SourceController` | ✅ | Daftar sumber referensi |
| GET | `/referensi/{source}` | `sources.show` | `SourceController@show` | ✅ | Detail satu sumber |
| GET | `/kebijakan-privasi` | `privacy` | `PageController` | ✅ | Batas penyimpanan riwayat, cache SW, security |
| GET | `/instalasi` | `install` | view `pages.install` | ✅ | Panduan pasang PWA (Android Chrome/iOS desktop); **bukan APK** |
| GET | `/offline` | `offline` | view `pages.offline` | ✅ | Fallback offline service worker |
| GET | `/manifest.json` | `manifest` | `PwaController@manifest` | ✅ | Web app manifest (JSON, durasi cache 1 jam) |
| GET | `/sw.js` | `sw` | `PwaController@serviceWorker` | ✅ | Service worker (no-cache, `Service-Worker-Allowed: /`) |

## 2. Rute mutasi (Livewire, bukan HTTP GET)

- `POST /livewire/update` — endpoint pembaruan Livewire (semua `wire:submit` / `wire:click`):
  `Calculator::calculate`, `Calculator::saveToHistory`, `CalculationHistory::delete`, dsb.
  Dilindungi middleware `web` (CSRF) + `throttle:livewire` (maks 60 request/menit per pengguna/IP)—
  dipasang lewat `Livewire::setUpdateRoute()` pada `AppServiceProvider`.

## 3. Tidak ada rute login/admin

Proyek tidak memiliki akun pengguna, jadi tidak ada halaman login/register/admin yang dibuat —
sesuai prinsip "jangan buat login bila tidak dibutuhkan".

## 4. Konvensi

- URI pakai kebab-case; route name pendek lowercase.
- Livewire component namespace `App\Livewire\`; layout via attribute `#[Layout('layouts.app')]`.
- Kode referensi perhitungan berformat `ALW-YYYY-NNNNNN`.

## 5. HTTP Status & Error

- **404** — halaman informatif (`resources/views/errors/404.blade.php`) dengan tautan
  Beranda / Hitung Warisan / Ahli Waris / Referensi.
- **419** (CSRF kedaluwarsa) — pesan "Halaman kedaluwarsa, silakan muat ulang."
- **429** (rate limit) — pesan "Terlalu banyak percobaan, tunggu beberapa saat."
- Validasi kalkulator memakai pesan khusus Indonesia (`Calculator::MESSAGES`); input pengguna
  tidak hilang pada kesalahan yang bisa dipulihkan.
