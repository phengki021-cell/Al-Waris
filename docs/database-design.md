# Desain Database — Al-Waris

> Status: Tahap 1. Target dev: **SQLite**; produksi dapat(MySQL/MariaDB) tanpa perubahan kode (Eloquent/Schema Builder).
> Prinsip: hindari floating point sebagai dasar pecahan; pecahan = pasangan integer.

## 1. Ringkasan Entitas

```
users (opsional, Tahap 3)
heirs ──┐
shares ─┤  ← data referensi (master)
sources ┘
calculations ─── calculation_participants ─── calculation_results
        │
        └── calculation_source (pivot: hasil ↔ provenance rule ↔ source)
```

## 2. Tabel Detail

### 2.1 `users` (dipindahkan penuh ke Tahap 3)
Migration default Laravel dibiarkan; belum dipakai fitur manapun di Tahap 1–2 kecuali auth (opsional).

### 2.2 `heirs` — master ahli waris
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | BIGINT PK | |
| `code` | VARCHAR(32) **UNIQUE** | `husband`, `wife`, `son`, `daughter`, `father`, `mother`, ... |
| `name_id` | VARCHAR(64) | Nama ID: "Suami", "Isteri",... |
| `name_ar` | VARCHAR(64) NULL | Nama Arab/literasi |
| `slug` | VARCHAR(64) **UNIQUE** | URL |
| `gender` | CHAR(1) | `m`, `f` |
| `category` | VARCHAR(24) | `fard` (dzawil furudh), `asabah`, `arham`, `hajib` |
| `max_share_numerator/denominator` | INT NULL | Bagian terbesar mungkin, ekspress rasional (n/d) untuk bisa 2/3 |
| `detail` | TEXT NULL | Deskriptive penjelasan |
| `source_id` | FK nullable → sources | Provenance |
| `is_active` | BOOLEAN default true | |
| `timestamps` | | |

Index: `code`, `slug`, `(category, is_active)`.

### 2.3 `shares` — bagian (khusus dzawil furudh)
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | BIGINT PK | |
| `heir_id` | FK → heirs | |
| `numerator` | TINYINT/INT UNSIGNED | 1, 2, 4, 8 dst. |
| `denominator` | TINYINT/INT UNSIGNED | 2, 4, 6, 8 dst. |
| `condition` | TEXT | "Jika tidak ada anak laki-laki", dsb. |
| `note` | TEXT NULL | Catatan khusus |
| `source_id` | FK → sources | Provenance |
| `timestamps` | | |

Unique constraint: `(heir_id, numerator, denominator, condition_hash)`.

### 2.4 `calculations` — riwayat perhitungan
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | BIGINT PK | |
| `user_id` | FK nullable → users | Anonim = NULL |
| `session_token` | VARCHAR(64) NULL INDEX | Untuk anonim; kript-random; hapus expired job |
| `reference_code` | VARCHAR(16) **UNIQUE** | Contoh `WRS-2026-0001` |
| `estate_total` | DECIMAL(20,2) | Total harta (nominal rupiah) |
| `debts_total` | DECIMAL(20,2) default 0 | |
| `bequests_total` | DECIMal(20,2) default 0 | Wasiat (maks 1/3 — divalidasikan) |
| `funeral_expense` | DECIMAL(20,2) default 0 | |
| `net_estate` | DECIMAL(20,2) (generated/aplikasi) | estate-total-… |
| `method` | VARCHAR(16) | `amal_ummi`, `aul`, `radd`, `gawalan` (Tahap 2 dst) |
| `status` | VARCHAR(16) | `draft`, `valid`, `needs_attention`, `invalid` |
| `notes` | TEXT NULL | Permintaan/status "dewasa/tidak" dll |
| `created_at/updated_at` | TIMESTAMP | |

Index: `(user_id)`, `(session_token)`, `(created_at)`.

### 2.5 `calculation_participants` — ahli waris yang terpilih
| Kolom | Tipe |
|---|---|
| `id` PK | BIGINT |
| `calculation_id` FK → calculations (cascade delete) | |
| `heir_id` FK → heirs NULL | Bisa NULL jika participant custom |
| `code` | VARCHAR(32) (denormalisasi agar riwayat tetap julis bila master diubah) |
| `count` | SMALLINT UNSIGNED default 1 |
| `attributes` | JSON NULL | ex: `{"wafat_lebih_dulu": true}` |
| `sort_order` | SMALLINT | |

Index: `(calculation_id)`.

### 2.6 `calculation_results` — hasil per peserta
| Kolom | Tipe |
|---|---|
| `id` PK | BIGINT |
| `calculation_id` FK (cascade) | |
| `participant_id` FK (cascade) | |
| `numerator` / `denominator` | BIGINT — bagian rasional eksak |
| `share_text` | VARCHAR(24) — "1/8" (rekening untuk pembaca; jangan pakai untuk hitung) |
| `amount` | DECIMAL(20,2) — nominal uang cukup disimpan abstrak; pembulatan disimpan bersama method |
| `rounding_method` | VARCHAR(16) NULL | `floor`, `ceil`, `none` |
| `note` | TEXT NULL | Catatan keputusan, "melalui perwakilan", dsb. |

### 2.7 `calculation_source` — pivos provenance
| Kolom | Tipe |
|---|---|
| `id` PK | |
| `calculation_id` FK (cascade) | |
| `source_id` FK → sources | |
| `rule_code` | VARCHAR(48) NULL — ex. `amal-ummi`, `radd` |

Unique: `(calculation_id, source_id, rule_code)`.

### 2.8 `sources` — sumber/referensi
| Kolom | Tipe |
|---|---|
| `id` PK | |
| `code` VARCHAR(32) UNIQUE | `KHI`, `QURAN-4-11`, `KITAB-FATHUL-QARIB`, ... |
| `title` VARCHAR(200) N |
| `type` VARCHAR(16) | `quran`, `hadis`, `kitab`, `kompilasi`, `fatwa`, `lainnya` |
| `reference` VARCHAR(200) NULL | ex. "QS. An-Nisa: 11", "KHI Pasal 174" |
| `url` VARCHAR(500) NULL | sumber online |
| `excerpt` TEXT NULL | kutipan ringkas |
| `is_primary` BOOLEAN | beratologi provenance |

### 2.9 `audit_records` (dimulai Tahap 3, siapkan migration sekarang —opsional)
Ditunda; kolom `created_at/updated_at` di atas sudah cukup untuk Tahap 1–2. Kalau proye perlu audit, tambahkan tabel `audit_records(date, actor, action, subject, payload JSON)`.

## 3. Seed Data (Label DEMO)
Semua seeder menuliskan flag `DEMO` pada catatan. Contoh:
- `heirs`: 20+ peserta umum (suami, isteri, 2 d/p anak laki/perempuan, ayah, ibu, kakek, nenek, saudara, dll.).
- `shares`: bagian pasti dari QS An-Nisa 11–12 dgn sumber `QURAN-4-11` dsb.
- `sources`: KHI (Faq), QS An-Nisā', AL-Baqarah 180, Kitab Fathul-Qārib,现代化的 pendapat terakriel.
- `calculations` + `participants` + `results`: 2 sampel riwayat DEMO.

## 4. Migrasi Gelontarkan
- File: `database/migrations/2026_10_21_000001_create_reference_tables.php` dst.
- FK `cascadeOnDelete()` untuk data turunan calculation; `restrictOnDelete()` untuk master (heirs/shares/sources).
- FK SQLite diperlukan, jadi foreign key constraints enabled secara default pada config Laravel `foreign_key_constraints` (tetap untuk SQLite). Ini diset.
