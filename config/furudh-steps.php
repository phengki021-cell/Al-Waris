<?php

/**
 * Tahapan panduan perhitungan warisan (halaman /cara-menghitung).
 * Setiap tahap dapat diklik (accordion) memuat: rumus, landasan fikih/kaidah,
 * dan contoh studi kasus rupiah. Angka contoh dihitung eksak (pecahan rasional);
 * aplikasi DEMO edukasi — bukan nasihat hukum.
 */

return [

    'judul_halaman' => 'Cara Menghitung Warisan',

    'steps' => [

        'penentuan-ahli-waris' => [
            'judul' => 'Tahap 1 — Penentuan Ahli Waris',
            'ringkasan' => 'Identifikasi pewaris, harta peninggalan bersih, dan daftar ahli waris yang sah pada saat kematian.',
            'rumus' => [
                'Tirkah bersih = harta peninggalan − biaya pengurusan jenazah − utang − wasiat.',
                'Batas wasiat: paling banyak 1/3 dari harta bersih, dan tidak boleh untuk penerima waris yang sudah berhak.',
                'Ahli waris sah = orang yang berhubungan darah/perkawinan, beragama Islam, tidak terhalang (KHI Pasal 171).',
            ],
            'landasan' => [
                'QS. An-Nisaa: 11 & 12 — "setelah (dipenuhi) wasiat yang dibuatnya atau (dan dilunasi) utangnya": huk wasiat & utang mendahului faraid.',
                'KHI Pasal 171 — definisi ahli waris (hubungan darah/perkawinan, beragama Islam, tidak terhalang).',
                'KHI Pasal 173 — terhalangnya ahli waris (pembunuh, penganiaya berat, fitnah pidana).',
                'Kaidah fikih: kematian menjadi penyebab pemindahan harta milik pewaris (tirkah) kepada ahli warisnya.',
            ],
            'contoh' => [
                'judul' => 'Kasus: pewaris meninggalkan harta 480 juta',
                'harta_awal' => 'Rp 480.000.000',
                'partisipan' => 'Suami, ibu, 2 anak perempuan',
                'langkah' => [
                    'Harta peninggalan Rp 480.000.000.',
                    'Biaya pengurusan jenazah Rp 20.000.000 → sisa Rp 460.000.000.',
                    'Lunasi utang Rp 60.000.000 → sisa Rp 400.000.000.',
                    'Wasiat Rp 30.000.000 (di bawah batas 1/3) → tirkah bersih Rp 370.000.000.',
                    'Daftar ahli waris: suami, ibu, 2 anak perempuan → lanjut ke Tahap 2.',
                ],
                'hasil' => 'Tirkah bersih Rp 370.000.000 dibagi ke suami, ibu, 2 anak perempuan.',
                'catatan' => 'Angka DEMO untuk pembelajaran; besar pada kasus nyata ditentukan data keluarga.',
            ],
        ],

        'dzawil-furudh' => [
            'judul' => 'Tahap 2 — Pembagian Dzawil Furudh',
            'ringkasan' => 'Hitung bagian pasti (rasio QS & KHI): anak perempuan, ibu, ayah, saudara perempuan kalalah, dan pasangan.',
            'rumus' => [
                'Tentukan asl (angka dasar) = KPK penyebut seluruh bagian pasti.',
                'Ubah setiap jatah menjadi saham dengan penyebut asl (mis. 1/4 = 3/12).',
                'Jumlah seluruh saham → harta per saham = tirkah bersih ÷ jumlah saham.',
                'Jika total jatah = 1 tanpa sisa → hanya dzawil furudh; jika < 1 → asabah/radd; jika > 1 → a\'ul.',
            ],
            'landasan' => [
                'QS. An-Nisaa: 11 — anak perempuan 1/2 (seorang) atau 2/3 (dua lebih); kedua orang tua 1/6 (jika ada anak); ibu 1/3 (tanpa anak & saudara).',
                'QS. An-Nisaa: 12 — suami 1/2 atau 1/4; istri 1/4 atau 1/8; saudara seibu 1/6 / 1/3.',
                'KHI Pasal 176 s.d. 180 — besaran bagian tersebut di atas.',
            ],
            'contoh' => [
                'judul' => 'Dzawil furudh polos: 1 anak perempuan + ibu',
                'harta_awal' => 'Rp 72.000.000',
                'partisipan' => '1 anak perempuan (tanpa keturunan lain), 1 ibu',
                'langkah' => [
                    'Jatah pasti: anak perempuan 1/2, ibu 1/6 (QS. An-Nisaa: 11).',
                    'KPK penyebut 2 dan 6 = 6 → asl = 6 saham.',
                    'Saham: anak perempuan 3/6; ibu 1/6.',
                    'Nilai 1 saham = Rp 72.000.000 ÷ 6 = Rp 12.000.000.',
                    'Distribusi: anak perempuan Rp 36.000.000; ibu Rp 12.000.000; sisa saham 2/6.',
                ],
                'hasil' => 'Anak perempuan Rp 36.000.000; ibu Rp 12.000.000; sisa Rp 24.000.000 diproses pada Tahap 4 (radd).',
                'catatan' => 'Sisa saham tidak langsung diabaikan; ikuti tahap asabah/radd.',
            ],
        ],

        'asabah' => [
            'judul' => 'Tahap 3 — Ashabah (Menerima Sisa)',
            'ringkasan' => 'Ketika adanya asabah (anak laki-laki, saudara laki-laki) → sisa harta dibagi sesuai rasio 2:1 (laki-laki : perempuan).',
            'rumus' => [
                'Sisa harta untuk asabah = tirkah bersih − total jatah dzawil furudh.',
                'Rasio ta\'sib: setiap laki-laki 2 saham, setiap perempuan 1 saham (QS. An-Nisaa: 11; KHI Pasal 176).',
                'Jenis asabah: binafsih (menerima dengan dirinya sendiri), bil-ghair (menyebabkan ta\'sib bersama ahli waris lain, mis. ayah bersama anak perempuan).',
                'Nilai 1 saham = sisa harta ÷ jumlah saham ta\'sib.',
            ],
            'landasan' => [
                'QS. An-Nisaa: 11 — "bagian seorang anak laki-laki sama dengan bagian dua orang anak perempuan" (catatan 146 Kemenag).',
                'KHI Pasal 176 — "bagian anak laki-laki adalah dua berbanding satu dengan anak perempuan".',
                'KHI Pasal 182 — rasio 2:1 juga berlaku bagi saudara (kalalah).',
            ],
            'contoh' => [
                'judul' => 'Waris: suami + istri + 2 anak laki-laki & 2 anak perempuan',
                'harta_awal' => 'Rp 600.000.000',
                'partisipan' => 'Istri (1/8 — pewaris punya keturunan), 2 anak laki-laki, 2 anak perempuan',
                'langkah' => [
                    'Jatah istri: 1/8 × Rp 600.000.000 = Rp 75.000.000.',
                    'Sisa untuk anak (asabah 2:1): Rp 600.000.000 − Rp 75.000.000 = Rp 525.000.000.',
                    'Saham ta\'sib: 2 anak laki-laki × 2 saham = 4; 2 anak perempuan × 1 saham = 2; total 6 saham.',
                    'Nilai 1 saham = Rp 525.000.000 ÷ 6 = Rp 87.500.000.',
                    'Distribusi: setiap anak laki-laki Rp 175.000.000; setiap anak perempuan Rp 87.500.000.',
                ],
                'hasil' => 'Rp 75.000.000 (istri) + Rp 350.000.000 (L) + Rp 175.000.000 (P) = Rp 600.000.000.',
                'catatan' => 'Pecahan ta\'sib selalu habis pada bilangan bulat saham; bagian per orang eksak.',
            ],
        ],

        'awal-radd' => [
            'judul' => 'Tahap 4 — Awal (A\'ul) & Radd',
            'ringkasan' => 'Sisa harta melampaui atau tidak mencukupi 1 — diatur agar tetap adil: melampaui dinormalisasi (a\'ul), tidak habis dikembalikan (radd).',
            'rumus' => [
                'A\'ul (total jatah > 1): asl baru = jumlah seluruh pembilang; proporsi tiap ahli waris tetap.',
                'Radd (total jatah < 1, tanpa asabah/penerima): sisa dikembalikan kepada kelompok fard mengikuti rasio jatah masing-masing.',
                'Radd tidak diberikan kepada pasangan (suami/istri) menurut kebanyakan mazhab.',
            ],
            'landasan' => [
                'Kaidah fikih: a\'ul mengubah angka dasar (asl) tanpa mengubah proporsi asli antar-ahli waris.',
                'Kaidah fikih: radd mengembalikan sisa kepada kelompok fard menimbang rasio jatah masing-masing — pendekatan umum fiqh (termasuk Syafi\'i).',
                'KHI tidak mengatur pasal a\'ul/radd secara eksplisit; kalkulator DEMO memakai konvensi fikih yang dijelaskan.',
            ],
            'contoh_aul' => [
                'judul' => 'A\'ul: waris suami + ibu + 2 anak perempuan',
                'harta_awal' => 'Rp 520.000.000',
                'partisipan' => 'Suami (1/4), ibu (1/6), 2 anak perempuan (2/3)',
                'langkah' => [
                    'Asl awal: KPK penyebut 4, 6, 3 = 24 saham.',
                    'Saham: suami 6/24; ibu 4/24; anak perempuan 16/24.',
                    'Total saham = 26 > 24 → a\'ul: asl dinaikkan menjadi 26 saham.',
                    'Proporsi tetap: suami 6/26; ibu 4/26; anak perempuan 16/26.',
                    'Distribusi: 1 saham = Rp 20.000.000 → suami Rp 120.000.000; ibu Rp 80.000.000; 2 anak perempuan Rp 320.000.000 (Rp 160.000.000/orang).',
                ],
                'hasil' => 'Total Rp 520.000.000 terbagi penuh dengan proporsi 6:4:16.',
                'catatan' => 'A\'ul: setiap penerima melepas sedikit porsi bagian; tidak ada yang dihilangkan.',
            ],
            'contoh_radd' => [
                'judul' => 'Radd: lanjutan contoh Tahap 2 (1 anak perempuan + ibu)',
                'harta_awal' => 'Rp 72.000.000',
                'partisipan' => '1 anak perempuan (3/6), 1 ibu (1/6)',
                'langkah' => [
                    'Jatah pasti habis di 4/6; sisa saham 2/6 = Rp 24.000.000.',
                    'Tidak ada asabah (kaidah) mengaturnya; ditangani radd.',
                    'Rasio jatah 3:1 (36:12) → sisa dibagi 3:1 = Rp 18.000.000 dan Rp 6.000.000.',
                    'Anak perempuan: Rp 36.000.000 + Rp 18.000.000 = Rp 54.000.000.',
                    'Ibu: Rp 12.000.000 + Rp 6.000.000 = Rp 18.000.000.',
                ],
                'hasil' => 'Total Rp 72.000.000 habis terbagi: Rp 54.000.000 + Rp 18.000.000.',
                'catatan' => 'Radd mengikuti konvensi fikih (pendekatan umum); KHI tidak mengatur — konsultasikan ahli faraid.',
            ],
        ],

        'harta-bersama' => [
            'judul' => 'Tahap 5 — Pemisahan Harta Bersama',
            'ringkasan' => 'Kerangka batas: sebelum faraid, hak pasangan (hal-hal yang menyebut) atas harta bersama dipisah terlebih dahulu.',
            'rumus' => [
                'Harta bersama dibagi dua: separuh menjadi hak pasangan yang masih hidup; separuh menjadi harta pewaris.',
                'Tirkah faraid = (harta bersama ÷ 2) + harta tetap pribadi pewaris − utang − wasiat.',
                'Hibah/rincian yang sudah dikerjakan semasa hidup tidak masuk faraid.',
            ],
            'landasan' => [
                'KHI Pasal 311 — dasar regulasi harta bersama: dipisahkan terlebih dahulu sebelum pembagian waris.',
                'Kaidah fikih: tirkah adalah harta yang masih milik pewaris pada akhir hayatnya.',
            ],
            'contoh' => [
                'judul' => 'Kasus harta bersama suami-istri Rp 1,2 miliar',
                'harta_awal' => 'Rp 1.200.000.000 (harta bersama suami-istri)',
                'partisipan' => 'Istri (pasangan), 2 anak laki-laki, 2 anak perempuan',
                'langkah' => [
                    'Harta bersama Rp 1.200.000.000 → separuh (Rp 600.000.000) kembali kepada istri sebelum waris.',
                    'Bagian pewaris (suami): Rp 600.000.000 → dipotong utang Rp 40.000.000 & wasiat Rp 20.000.000.',
                    'Tirkah bersih faraid = Rp 540.000.000.',
                    'Jatah istri 1/8 (ada keturunan) = Rp 67.500.000.',
                    'Sisa Rp 472.500.000 bagi 6 saham anak (2:1) → 1 saham Rp 87.500.000: anak laki-laki Rp 175.000.000; anak perempuan Rp 87.500.000.',
                ],
                'hasil' => 'Istri total Rp 667.500.000 (600 juta hak bersama + 67,5 juta waris); tiap anak laki-laki Rp 175.000.000; tiap anak perempuan Rp 87.500.000.',
                'catatan' => 'Pemisahan harta bersama dilaksanakan sebelum faraid; sesuai dasar KHI Pasal 311.',
            ],
        ],
    ],
];
