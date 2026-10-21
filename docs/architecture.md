# Arsitektur — Warisku

> Status: Tahap 1. Terakhir diperbarui: 21 Oktober 2026.

## 1. Ringkasan Keputusan Teknis

| Aspek | Keputusan | Versi | Alasan |
|---|---|---|---|
| Bahasa server | PHP | 8.4.x | Didukung Laravel 12 / Livewire 3; tersedia di winget |
| Framework | Laravel | 13.38.0 (stabil saat ini) | Standar industri; ekosistem matang; terverifikasi via `create-project` |
| Layer interaktif | Livewire | 3.x | Tidak membutuhkan SPA; alami untuk Blade; mudah dipelajari penonton awam |
| Styling | Tailwind CSS | 4.x | Utility-based, mudah theming palet hijau #123D32 / emas #D8B66A |
| JS gidiswa | Alpine.js | 3.x (bundled Livewire) | Untuk UI kecil: modal, accordion |
| Database dev | SQLite | 3.x | Nol installasi, file, cukup untuk desain ini; mock/replay cepat |
| Database prod option | MySQL / MariaDB | →_any_ | Dukungan via `.env` + driver Laravel secara bawaan |
| Test | Pest | 3.x / 4.x | API test lebih ringkas; dapat menjalankan PHPUnit tests |
| Code style | Laravel Pint | latest | Frozen config, cepat |
| PWA | `manifest.json` + service worker + HTTPS saat deployment | — | rincian Tahap terakhir; TIDAK menjanjikan output APK |

## 2. Struktur Arsitektur (Layered)

```
┌──────────────────────────────────────┐
│  Presentation — Blade + Livewire     │  resources/views, app/Livewire
├──────────────────────────────────────┤
│  Application — Services & Actions    │  app/Services
├──────────────────────────────────────┤
│  Domain — Faraid Engine (pure logic) │  app/Domain/Faraid
│    - HeirCatalog                     │  enumerations + rules data
│    - DistributionService             │  ksjs hisab: amal ummi, radd, 'ul,
│    - Share Calculator                │    dll.
│    - RationalNumber                  │  bahasa/domain utilitas pecahan
├──────────────────────────────────────┤
│  Provenance — Rule ↔ Source Register │  app/Domain/Faraid/Provenance
├──────────────────────────────────────┤
│  Infrastructure / Persistence        │  Eloquent models, migrations
│  (Database, Storage, Cache)          │
└──────────────────────────────────────┘
```

### 2.1 Aturan utama inti
- **Tidak menaruh rumus waris di controller/Livewire component.** Semua logika faraid berada di `app/Domain/Faraid` — kelas `invokable/internos` yang dapat dipanggil oleh Livewire command, tinker/test, atau CLI.
- Mesin domain **tidak mengimpor** `Illuminate\` (murni OO + type-hint supaya bisa diuji tanpa bootstrap Laravel →`php artisan test` tetap puas karena dipanggil dari service layer).

## 3. Pola Data Angka (Kerangka Peluru)

| Kebutuhan | Tipe | Keterangan |
|---|---|---|
| Nominal uang (total harta) | `DECIMAL(20,2)` atau `UNSIGNED BIGINT` (sen/kopeck) | Hindari <Float>; disimpan integer rupiah untuk sifat cacah |
| Pecahan / bagian | **Rasional (numerator, denominator `BIGINT`)** | never float; Engine menproduksi `["n","d"]`; UI menampilkan hasil dengan perata/peraturan |
| Jumlah orang | `TINYINT UNSIGNED` / `SMALLINT UNSIGNED` | 1–99 |
| Status record | `TINYINT` + enum-mapping di model atau string bulletproof | jangan gunakan native `bool` dengene & karemai |
| Tanggal | `DATE` / `TIMESTAMP` (subset lokasi tahun hari) | Riwayat, compatibility |
| Metadata | `JSON` (MySQL & SQLite supported) | Heirs terpilih, dallah設定, result detail |

### 3.1 Seputar pengembangan Mesin
- `RationalNumber` (Immutable): operations + / − / × / ÷ + simplify (gcd) — no floats.
- Bagian dikeluarkan sebagai rasional, kemudian di-render ke UI sebagai **teks pecahan** dan **angka desimal** (dibulatkan 2 desimal, dengan label "≈" jika pembulatan terjadi).
- Uang utuh selalu **dibulatkan ke atas/nihil** berdasarkan aturan "wilayah terkecil kaliat id", dibebankan pada engine config.

## 4. Stack PWA (Rencana)
- `manifest.json` — name: Warisku, short_name: Warisku, theme_color `#123D32`, background_color `#F5F7F4`, display `standalone`, ikon 192×192 & 512×512 (PNG kecil).
- `service-worker.js`: strategi
  - **app shell** (CSS/JS/lulu) → cache-first, version-til path
  - **halaman konten edukasi** → stale-while-revalidate
  - **API/Livewire POST dan result perhitungan**: JANGAN pernah cache. Jika ada data pribadi, wajib disimpan di **IndexedDB di sisi klien** dengan pilihan user eksplisit — tidak disimpan di SW/caches.

## 5. Direktori Utama (Skeleton)

```
warisku/
├── app/
│   ├── Domain/Faraid/           # pure domain
│   ├── Livewire/                # kompoennt interaktif
│   ├── Services/                # application service (orchestration)
│   └── Http/Controllers/        # repos bukan komputasi. hanya render.
├── bootstrap/, config/, database/
│   ├── factories, migrations, seeders (data demo berlabel DEMO)
├── docs/                        # semua dokumen proyek
├── public/
│   ├── manifest.json, sw.js, icons/
├── resources/views/             # blade
├── routes/                      # web.php + api minimal
├── tests/Feature, tests/Unit
├── tools/                       # composer.phar (Lokal Windows host instructions)
└── README.md
```

### 5.1 Aturan dokumentasi
- Setiap keputusan yang mengubah cara kerja → catat ke `docs/architecture.md` pada bagian Change Log.
- Setiap aturan faraid yang jadi kode → diangkat ke `docs/faraid-rules.md` (Tahap 2) dan sumbernya diregistrasi di tabel `source_references`.

## 6. Change Log Keputusan (melampaui durasi proyek)

| Tanggal | Versi | Keputusan | Alasan |
|---|---|---|---|
| 2026-10-21 | v0.1 | Pakai Laravel 13.38 + Livewire 3 (versi terpasang dari composer) + Tailwind | Toolchain Windows tersedia via winget: PHP 8.4.25 |
| 2026-10-21 | v0.1 | SQLite untuk dev, MySQL tersedia via .env | Database MySQL tidak terinstal pada mesin dev; SQLite zero-config. Prod dapat pindah tanpa ubah kode karena Eloquent/Schema Builder |
| 2026-10-21 | v0.1 | Composer manual via composer.phar (di `tools/`) | Paket Composer tidak tersedia di winget source |
| 2026-10-21 | v0.1 | Domain engine di `app/Domain/Faraid`, tidak di controller | Uji tanpa UI, keelaboran keputusan faraid penuh yang harus `just-citecture-improve` |
