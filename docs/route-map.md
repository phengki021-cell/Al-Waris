# Route Map — Warisku

> Status: Tahap 1. Status halaman: ⏳ belum dibangun → ✅ siap pada Tahap 1 (skeleton / halaman placeholder konten) → 🔄 interaktif penuh (Tahap lanjut)

## 1. Rute halaman (web.php)

| Method | URI | Route name | Controller/Livewire | Status Tahap 1 | Catatan |
|---|---|---|---|---|---|
| GET | `/` | `home` | `HomeController` | ✅ | Beranda: ringkasan + CTA |
| GET | `/ahli-waris` | `heirs` | `HeirCatalogController` | ✅ | Tabel dyn golongan ahli waris dari seeder |
| GET | `/bagian-warisan` | `shares` | `ShareCatalogController` | ✅ | Tabel bagian pasti (from gift/ruks: 1/2, 1/3, 1/6, dst.) |
| GET | `/cara-menghitung` | `how-to` | PageController | ✅ | Kumpulan langkah kerja; konten static |
| GET | `/hitung-warisan` | `calculate` | `Livewire\Calculator` | ✅ | Kalkulator Livewire; Persussion store history pada Tahap 2; versi tahap 1: happy path bagian dasar |
| GET | `/riwayat` | `history` | `Livewire\CalculationHistory` | ✅ | Tahap 1: empty state; data datasesi user tersambung pada Tahap 2 |
| GET | `/tentang` | `about` | PageController | ✅ | Profil + disclaimer |
| GET | `/referensi` | `sources` | `SourceController` | ✅ | index source_references |
| GET | `/kebijakan-privasi` | `privacy` | PageController | ✅ | |
| GET | `/referensi/{source}` | `sources.show` | `SourceController@show` | ⏳ Tahap 2 | Detail satu sumber |

## 2. Rute perhitungan (Livewire methods, bukan HTTP)
- `Calculator::(ref id ayat, penanganan heirs schematic tasa input)`
- Perhitungan dibwha dan Dilontarkan langsung dalam komponen, hasil disimpan server-side via service (Tahap 2) → tabel `calculations`.

## 3. Rute not-public (Tahap 3+) — disiapkan
- `GET /masuk` (`login`), `GET /daftar` (`register`), `POST /keluar` (`logout`)

## 4. Aturan Naming
- Semua route pakai kebab-case dalam URI; route name `lowercase.kebab` yang diberi kelompok tidak perlu beberapa form mangler成熟的 DB.
- Livewire component `Namespace`: `App\Livewire\` (convention Livewire 3, undercut dengan ke формирования config).

## 5. HTTP Status & Error
- **404** (bila URL tidak ada) — pesan ramah bahasa Indonesia, tautan kembali `home`.
- **419** (CSRF expired) — pesan "Halaman kedaluwarsa, silakan muat ulang."
- **429** (rate limit) — saat "Terlalu banyak percobaan, tunggu beberapa saat".
- Kalkulator Livewire (Tahap 1) memakai validasi server-side (`#[Validate]`) hasil zimmer tags; error properti inline.
