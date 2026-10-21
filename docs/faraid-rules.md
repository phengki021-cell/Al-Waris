# Pendekatan Fikih & Perlakuan KHI — Warisku (Tahap 3)

> Status: v1.0 — 21 Oktober 2026. Dokumen ini WAJIB dibaca sebelum mengubah `FaraidEngine`.
> Prinsip: tidak mencampur pendapat berbeda tanpa penanda; tidak mengklaim akurasi hanya karena kode berjalan.

## 1. Pendekatan yang Dipakai

1. Dasar bagian pasti (furudh) mengikuti ayat-ayat waris yang diakui umum:
   QS. An-Nisāʾ (11 dan 12) untuk suami/istri/anak/orang tua/saudara, dan ayat
   kalālah (An-Nisāʾ: 176) untuk porsi saudara dalam keadaan tertentu.
   **Aplikasi TIDAK mengutip teks ayat verbatim** — yang ditampilkan hanya kaidah
   bagian (fraksi) dengan tautan ke referensi di halaman `/referensi`.
2. Kompilasi Hukum Islam (KHI) dipakai sebagai **kerangka pada konteks hukum
   Indonesia** (bagian ahli waris, urutan kewajiban, batas wasiat 1/3).
   Jika kaidah mesin dan KHI bertentangan, hasil diberi penanda dan belum
   diaktifkan sampai diperiksa.
3. Metode mekanis yang dipakai: **amal ummi** dengan penyebut umum (asal masalah),
   'aul (penaikan asal), radd (pembagian sisa proporsional, **tidak** kepada
   pasangan), dan kasus gharawain (penanda beda pendapat).
4. Metode radd alternatif dari sebagian kitab (berbasis pembilang asal 23 dsb.)
   **tidak** dipakai; keberadaannya dinyatakan di catatan hasil agar pengguna tahu
   hasil bisa sedikit berbeda antar metode yang sah.

## 2. Tabel Aturan yang Diaktifkan pada Mesin (versi `0.3.0`)

| # | Kondisi | Kode ahli waris | Bagian | Syarat/Efek hijab | Rasio/Aturan asabah | Rujukan yang bisa diperiksa |
|---|---|---|---|---|---|---|
| 1 | Suami, tidak ada keturunan | `husband` | 1/2 | — | — | QS. An-Nisāʾ: 12 |
| 2 | Suami, ada keturunan (anak/cucu melalui anak laki) | `husband` | 1/4 | — | — | QS. An-Nisāʾ: 12 |
| 3 | Istri (total), tidak ada keturunan | `wife` | 1/4 dibagi rata | — | — | QS. An-Nisāʾ: 12 |
| 4 | Istri (total), ada keturunan | `wife` | 1/8 dibagi rata | — | — | QS. An-Nisāʾ: 12 |
| 5 | Anak perempuan sendirian tanpa anak laki | `daughter` | 1/2 | — | — | QS. An-Nisāʾ: 11 |
| 6 | Anak perempuan ≥ 2 tanpa anak laki | `daughter` | total 2/3 | — | — | QS. An-Nisāʾ: 11 |
| 7 | Anak laki bersama anak perempuan | `son`,`daughter` | sisa kepada asabah | — | 2 : 1 | QS. An-Nisāʾ: 11 (bagian asabah) |
| 8 | Ayah tanpa anak laki, ada anak perempuan | `father` | fard 1/6 + seluruh sisa | menghalangi semua saudara (hijab hirman) | absorber penuh | QS. An-Nisāʾ: 11 |
| 9 | Ayah tanpa anak laki & tanpa anak perempuan | `father` | seluruh sisa | menghalangi semua saudara | absorber penuh | QS. An-Nisāʾ: 11 |
| 10 | Ayah + anak laki-laki | — | **NOT SUPPORTED** | butuh validasi ahli faraid | — | — |
| 11 | Ibu, ada keturunan atau ≥2 saudara | `mother` | 1/6 | — | — | QS. An-Nisāʾ: 11 |
| 12 | Ibu lainnya (tanpa keturunan, <2 saudara) | `mother` | 1/3 | — | — | QS. An-Nisāʾ: 11 |
| 13 | Nenek-ibu tanpa ibu | `maternal_grandmother` | 1/6 | terhalang bila ibu ada (lihat gharawain) | — | QS. An-Nisāʾ: 11 (kelas kewenangan fard ortu) |
| 14 | Ibu + nenek-ibu (gharawain), komposisi sederhana | `mother`,`maternal_grandmother` | 1/3 & 1/6, sisa diradd | **BEDA PENDAPAT: sebagian ulama menghalangi nenek bila ada ibu** | sisa radd proporsional 2:1 | QS. An-Nisāʾ: 11; kasus gharawain klasik |
| 15 | Saudara sedarah laki (tanpa ayah) | `brother` | asabah atas sisa, **setara dengan anak laki-laki** | terhalang oleh ayah (bukan oleh keturunan) | asabah bi-nafs | QS. An-Nisāʾ: 176 |
| 16 | Saudara perempuan tanpa saudara laki & tanpa keturunan & tanpa ayah | `sister` | 1/2 (1) / total 2/3 (≥2) | terhalang oleh ayah; **terhalang oleh anak laki-laki**; bila bersama anak *perempuan* ia menjadi asabah (ta'ṣīb bi'l-ghayr) | tetap fard / asabah | QS. An-Nisāʾ: 176 |
| 17 | Saudara perempuan bersama saudara laki | `sister` | asabah 1 satuan | tidak terhalang oleh keturunan bila saudara laki hadir | 1 : 2 terhadap saudara laki | QS. An-Nisāʾ: 176 |
| 18 | Sisa tanpa asabah | — | radd proporsional | pasangan tidak ikut radd | sebanding fraksi fard | kaidah radd klasik |
| 19 | Sisa tanpa sasaran radd (mis. hanya pasangan) | — | **NOT SUPPORTED** (dzawil arham/Baitulmal) | — | — | Tahap 3+ |

## 3. Batasan & Penanda yang Wajib Tampil di Hasil

- Setiap hasil memuat `engine_version`, tanggal, dan label **DEMO** hingga
  validasi manual oleh ahli faraid selesai.
- Komposisi di luar tabel → aplikasi **menolak menghitung pasti** dan
  menampilkan alasan (`not_supported`), bukan hasil penuh.
- Penanda beda pendapat: `disputed: true` pada share terkait + catatan pada
  `notes` hasil. Kasus aktif saat ini: gharawain.
- KHI belum dipetakan pasal-per-pasal ke tabel di atas (pekerjaan validasi
  berikutnya sebelum menghapus label DEMO).

## 4. Yang Belum Didukung (kosong oleh desain)

Saudara seayah/seibu, kakek/nenek selain garis ibu-gharawain, cucu melalui
jalur anak laki (siyah), dzawil arham, hibah janin/mafqud, hijab senilai
(anak perempuan menghalangi anak laki perubahan klasik tertentu), umariyyatayn.
Menu input tidak menawarkan opsi ini, sehingga pengguna tidak bisa memilihnya.
