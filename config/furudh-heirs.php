<?php

/**
 * Data edukasi "Ahli Waris" untuk modal detail pada halaman /ahli-waris.
 *
 * Rangkuman disusun dari pasal KHI (Inpres No. 1 Tahun 1991) yang dikutip
 * verbatim pada config/furudh-dalil.php serta kaidah fiqh mawaris umum
 * yang bermazhab-mazhab. Hal yang TIDAK diatur KHI ditandai secara jujur.
 * Aplikasi edukasi/simulasi (DEMO) — bukan nasihat hukum.
 */

return [

    'judul_modal' => 'Detail Ahli Waris',

    'heirs' => [

        'husband' => [
            'definisi' => 'Suami (duda) adalah laki-laki yang pada saat pewaris (istri) meninggal berstatus terikat perkawinan yang sah. Ia masuk kelompok inti ahli waris dzawil furudh menurut KHI Pasal 174 (anak, ayah, ibu, janda/duda). Haknya muncul dari hubungan perkawinan, bukan hubungan darah.',
            'syarat' => [
                'Perkawinan dengan pewaris sah dan masih terikat pada saat pewaris meninggal (bukan dalam iddah talak ba\'in).',
                'Beragama Islam sama-sama pewaris; berbeda agama tidak saling mewarisi.',
                'Tidak terhalang hukum (KHI Pasal 173): tidak terbukti membunuh/menganiaya berat pewaris atau memfitnahnya.',
            ],
            'menghalangi' => [
                'Suami tidak terhalang oleh ahli waris mana pun; jumlah bagian (1/2 atau 1/4) hanya ditentukan keberadaan anak/keturunan pewaris.',
                'Sebaliknya, terselesaikannya jatah suami memengaruhi sisa harta yang diterima asabah (mis. saudara-saudara pewaris).',
            ],
            'dihalangi' => [
                'Keberadaan keturunan pewaris (anak/cucu) menurunkan jatah suami dari 1/2 menjadi 1/4 (KHI Pasal 179; QS. An-Nisaa: 12).',
                'Penghalang umum: putusan syar\'i sebagaimana KHI Pasal 173.',
            ],
            'dalil' => [
                'verse' => 'QURAN-4-12',
                'pasals' => [179],
            ],
        ],

        'wife' => [
            'definisi' => 'Istri (janda) adalah perempuan yang pada saat pewaris (suami) meninggal berstatus terikat perkawinan yang sah. Ia juga kelompok inti dzawil furudh menurut KHI Pasal 174. Jika akan lebih dari satu istri, seluruhnya membagi rata satu jatah bersama.',
            'syarat' => [
                'Perkawinan dengan pewaris sah dan masih terikat pada saat pewaris meninggal.',
                'Beragama Islam seiring pewaris; beda agama tidak saling mewarisi.',
                'Tidak terhalang hukum (KHI Pasal 173).',
            ],
            'menghalangi' => [
                'Istri tidak menghalangi keturunan pewaris; anak tetap berhak lebih dahulu.',
                'Sebaliknya, jatah istri (1/4 atau 1/8) selalu dibayarkan terlebih dahulu; sisa harta baru dibagi kepada ahli waris lain.',
            ],
            'dihalangi' => [
                'Keberadaan keturunan pewaris menurunkan jatah istri dari 1/4 menjadi 1/8 (KHI Pasal 180; QS. An-Nisaa: 12).',
                'Penghalang umum: putusan syar\'i sebagaimana KHI Pasal 173.',
            ],
            'dalil' => [
                'verse' => 'QURAN-4-12',
                'pasals' => [180],
            ],
        ],

        'son' => [
            'definisi' => 'Anak laki-laki adalah laki-laki yang terhubung nasab kandung kepada pewaris. Dalam ilmu faraid ia asabah binafsih (menerima seluruh sisa harta) atau asabah bil-ghair (bersama anak perempuan, bagian 2 berbanding 1).',
            'syarat' => [
                'Nasab kandung kepada pewaris (bukan anak angkat; status anak angkat diatur di luar faraid klasik).',
                'Beragama Islam dan tidak terhalang hukum (KHI Pasal 173).',
                'Anak pewaris adalah golongan pertama ahli waris (KHI Pasal 174).',
            ],
            'menghalangi' => [
                'Anak laki-laki menghalangi saudara-saudara pewaris (kandung, seayah, seibu) dan kerabat asabah yang lebih jauh.',
                'Bersama anak perempuan, ia mengubah jatah 1/2 atau 2/3 menjadi sistem 2:1 (KHI Pasal 176; QS. An-Nisaa: 11).',
                'Ketika bersama anak perempuan, ia menerima sisa setelah jatah pasti anak perempuan dibagikan.',
            ],
            'dihalangi' => [
                'Tidak ada ahli waris yang menghalangi anak laki-laki; kedudukannya sebagai asabah binafsih paling dekat.',
            ],
            'dalil' => [
                'verse' => 'QURAN-4-11',
                'pasals' => [171, 176],
                'catatan' => 'KHI tidak memuat pasal khusus besaran bagian anak laki-laki; rasio 2:1 muncul pada KHI Pasal 176 dan QS. An-Nisaa: 11.',
            ],
        ],

        'daughter' => [
            'definisi' => 'Anak perempuan adalah perempuan yang terhubung nasab kandung kepada pewaris. Berdiri sendiri ia dzawil furudh: seorang 1/2; dua atau lebih 2/3 dibagi rata. Bersama anak laki-laki, hubungan bagian menjadi 2 berbanding 1.',
            'syarat' => [
                'Nasab kandung kepada pewaris.',
                'Beragama Islam dan tidak terhalang hukum (KHI Pasal 173).',
                'Anak pewaris adalah golongan pertama ahli waris (KHI Pasal 174).',
            ],
            'menghalangi' => [
                'Anak perempuan (langsung) menghalangi keturunan lebih jauh seperti cucu dari nasab perempuan.',
                'Keberadaan anak laki-laki mengubah statusnya menjadi pasangan rasio 2:1, bukan menghalanginya sepenuhnya.',
            ],
            'dihalangi' => [
                'Tidak terhalang oleh ahli waris mana pun; keberadaan anak laki-laki hanya menyesuaikan besaran bagian.',
            ],
            'dalil' => [
                'verse' => 'QURAN-4-11',
                'pasals' => [176],
            ],
        ],

        'father' => [
            'definisi' => 'Ayah adalah bapak kandung pewaris. Kedudukannya melipat ganda: dzawil furudh (1/6 bila ada anak; menurut teks KHI 1/3 bila tidak ada anak) dan asabah binafsih bila bersama anak laki-laki (menerima sisa).',
            'syarat' => [
                'Nasab kandung kepada pewaris (ayah angkat tidak masuk faraid).',
                'Beragama Islam dan tidak terhalang hukum (KHI Pasal 173).',
                'Ayah termasuk empat kelompok inti KHI Pasal 174.',
            ],
            'menghalangi' => [
                'Ayah menghalangi saudara-saudara pewaris (kandung, seayah, seibu) serta kerabat asabah yang lebih jauh.',
                'Bersama anak perempuan tanpa anak laki-laki, ayah menjadi asabah bil-ghair menerima sisa.',
            ],
            'dihalangi' => [
                'Tidak ada yang menghalangi ayah; menurut fiqh klasik, kakek dari ayah tidak dapat bagian selama ayah masih ada.',
            ],
            'dalil' => [
                'verse' => 'QURAN-4-11',
                'pasals' => [177],
                'catatan' => 'KHI Pasal 177 disertai catatan SEMA No. 2 Tahun 1994 (Mahkamah Agung): sepertiga ayah berlaku bila pewaris meninggalkan suami/istri dan ibu; bila ada anak, ayah mendapat seperenam.',
            ],
        ],

        'mother' => [
            'definisi' => 'Ibu adalah ibu kandung pewaris. Bagiannya: 1/6 bila pewaris memiliki anak atau dua saudara lebih; 1/3 bila tidak ada keduanya; bila bersama ayah, ibu memperoleh sepertiga dari sisa (fraksi KHI Pasal 178 ayat 2).',
            'syarat' => [
                'Nasab kandung kepada pewaris.',
                'Beragama Islam dan tidak terhalang hukum (KHI Pasal 173).',
                'Ibu termasuk empat kelompok inti KHI Pasal 174.',
            ],
            'menghalangi' => [
                'Keberadaan ibu mengurangi bagian ayah bila keduanya bersamaan: ayah mendapat 2/3 sisa dan ibu 1/3 sisa (umariyyatan).',
                'Ibu tidak menghalangi anak maupun pasangan pewaris.',
            ],
            'dihalangi' => [
                'Keturunan (anak/cucu) menurunkan bagian ibu menjadi 1/6.',
                'Dua saudara atau lebih (kandung/seayah/seibu) juga menurunkannya menjadi 1/6 (QS. An-Nisaa: 11; KHI Pasal 178).',
            ],
            'dalil' => [
                'verse' => 'QURAN-4-11',
                'pasals' => [178],
            ],
        ],

        'grandfather_father' => [
            'definisi' => 'Kakek dari ayah (jadd) adalah ayah dari ayah pewaris. Dalam fiqh klasik ia menggantikan kedudukan ayah ketika ayah pewaris telah lebih dahulu wafat, termasuk secara asabah bersama saudara pewaris.',
            'syarat' => [
                'Nasab lapik sesuai garis ayah pewaris.',
                'Dalam kaidah klasik: ayah pewaris wafat lebih dahulu.',
                'Tidak terhalang hukum (KHI Pasal 173).',
            ],
            'menghalangi' => [
                'Dalam fiqh klasik kakek menghalangi (menggantikan) saudara-saudara pewaris pada kedudukan asabah.',
            ],
            'dihalangi' => [
                'Ayah pewaris yang masih hidup: hanya satu yang diutamakan.',
                'Anak laki-laki pewaris (asabah binafsih lebih dekat).',
            ],
            'dalil' => [
                'verse' => null,
                'pasals' => [174],
                'catatan' => 'JUJUR: KHI hanya menautkan empat kelompok inti (anak, ayah, ibu, janda/duda) pada Pasal 174; tidak ada pasal khusus kakek. Aplikasi menampilkan mesin DEMO: konsultasikan ahli faraid/Pengadilan Agama.',
            ],
        ],

        'grandmother_mother' => [
            'definisi' => 'Nenek dari ibu (jaddah) adalah ibu dari ibu pewaris. Terdapat juga nenek dari ayah (jaddah abatin) — keduanya dalam fiqh klasik dibagikan 1/6 ketika jalur penghalang wafat lebih dulu.',
            'syarat' => [
                'Nasab kukuh sesuai garis ibu atau ayah pewaris.',
                'Kaidah klasik: orang tua pewaris di atasnya (ibu / ayah) wafat lebih dahulu.',
                'Tidak terhalang hukum (KHI Pasal 173).',
            ],
            'menghalangi' => [
                'Nenek tidak menghalangi kelompok fard inti; ia memperoleh jatah pelengkap setelah jatah utama dibayarkan.',
            ],
            'dihalangi' => [
                'Ibu pewaris yang masih hidup: menurut kaidah klasik nenek dari ibu tidak mendapat bagian.',
                'Ayah pewaris yang masih hidup: menurut kaidah klasik menutup jalur nenek dari ayah.',
            ],
            'dalil' => [
                'verse' => null,
                'pasals' => [174],
                'catatan' => 'JUJUR: KHI tidak memuat pasal khusus nenek; posisi mengikuti kaidah mazhab. Verifikasi ahli faraid.',
            ],
        ],

        'sibling_male_full' => [
            'definisi' => 'Saudara sedarah laki-laki (akh li-abawain) adalah saudara laki-laki yang berbagi ayah dan ibu dengan pewaris. Pada kasus kalalah (QS. An-Nisaa: 176) ia dzawil furudh; apabila tidak ada anak laki-laki dan ayah, ia asabah binafsih menerima sisa.',
            'syarat' => [
                'Berbagi ayah dan ibu (kandung) dengan pewaris.',
                'Pada status kalalah: tidak ada anak dan ayah pewaris.',
                'Tidak terhalang hukum (KHI Pasal 173).',
            ],
            'menghalangi' => [
                'Saudara sedarah laki-laki menghalangi saudara seayah, keponakan dari garis laki-laki, dan paman dari ayah.',
                'Keberadaannya menyetel rasio 2:1 bersama saudara sedarah perempuan (QS. An-Nisaa: 176; KHI Pasal 182).',
            ],
            'dihalangi' => [
                'Anak laki-laki pewaris (asabah lebih dahulu) dan ayah pewaris menghalanginya.',
            ],
            'dalil' => [
                'verse' => 'QURAN-4-176',
                'pasals' => [182],
            ],
        ],

        'sister_female_full' => [
            'definisi' => 'Saudara sedarah perempuan (ukht li-abawain) berbagi ayah dan ibu dengan pewaris. Pada kalalah ia dzawil furudh: seorang 1/2; dua lebih 2/3; bersama saudara laki-laki membagi 2 berbanding 1.',
            'syarat' => [
                'Berbagi ayah dan ibu (kandung) dengan pewaris.',
                'Pada status kalalah: tidak ada anak dan ayah pewaris.',
                'Tidak terhalang hukum (KHI Pasal 173).',
            ],
            'menghalangi' => [
                'Saudara sedarah perempuan menghalangi saudara seayah perempuan dan kerabat lebih jauh.',
                'Bersama saudara sedarah laki-laki, keduanya berbagi dengan rasio 2:1.',
            ],
            'dihalangi' => [
                'Anak laki-laki pewaris dan ayah pewaris menghalanginya pada status fard.',
            ],
            'dalil' => [
                'verse' => 'QURAN-4-176',
                'pasals' => [182],
            ],
        ],

        'daughter_of_son' => [
            'definisi' => 'Cucu perempuan (bint al-ibn) adalah anak perempuan dari anak laki-laki pewaris. Beberapa mazhab memberi perlakuan perwakilan (perubahan hereditas) ketika ayahnya (anak laki-laki pewaris) telah wafat.',
            'syarat' => [
                'Nasab sah dari anak laki-laki pewaris.',
                'Dalam kaidah klasik: ayahnya telah wafat (terjadi mekanisme perwakilan).',
                'Tidak terhalang hukum (KHI Pasal 173).',
            ],
            'menghalangi' => [
                'Cucu perempuan tidak menghalangi kelompok fard inti; ia mendapat bagian pelengkap pada sisa.',
            ],
            'dihalangi' => [
                'Anak langsung pewaris (anak laki-laki atau anak perempuan tertentu) menghalangi jalur perwakilan ini.',
                'Mekanisme terhadap saudara sedarah pewaris juga berlaku dalam kaidah klasik.',
            ],
            'dalil' => [
                'verse' => null,
                'pasals' => [174],
                'catatan' => 'JUJUR: KHI tidak memuat pasal khusus perwakilan/bagian cucu perempuan; ragam mazhab; aplikasi menampilkan kisaran DEMO. Rujuk ahli faraid.',
            ],
        ],

        'grandson' => [
            'definisi' => 'Cucu laki-laki (ibn al-ibn) adalah anak laki-laki dari anak laki-laki pewaris. Dalam fikih klasik ia asabah binafsih bila tidak ada anak laki-laki langsung pewaris; menerima sisa harta.',
            'syarat' => [
                'Nasab sah dari anak laki-laki pewaris.',
                'Kaidah klasik: anak laki-laki langsung pewaris tidak ada.',
                'Tidak terhalang hukum (KHI Pasal 173).',
            ],
            'menghalangi' => [
                'Cucu laki-laki menghalangi kerabat garis laki-laki yang lebih jauh: keponakan seayah dan paman seayah.',
            ],
            'dihalangi' => [
                'Anak laki-laki pewaris (langsung) menghalangi jalur asabah cucu.',
            ],
            'dalil' => [
                'verse' => null,
                'pasals' => [174],
                'catatan' => 'JUJUR: KHI tidak memuat pasal bagian cucu laki-laki; posisi mengikuti kaidah mazhab. Verifikasi ahli faraid.',
            ],
        ],

        'brother_paternal' => [
            'definisi' => 'Saudara seayah laki-laki (akh li-abi) berbagi ayah saja dengan pewaris. Berstatus asabah bil-ghair: menerima sisa setelah bagian pasti dibayarkan, bila tidak ada anak laki-laki maupun saudara sedarah laki-laki.',
            'syarat' => [
                'Nasab sah dari ayah pewaris.',
                'Tidak ada anak laki-laki maupun saudara sedarah laki-laki pewaris (kaidah klasik).',
                'Tidak terhalang hukum (KHI Pasal 173).',
            ],
            'menghalangi' => [
                'Saudara seayah laki-laki menghalangi keponakan seayah (anak dari saudara seayah) dan paman dari lapisan lebih atas.',
            ],
            'dihalangi' => [
                'Anak laki-laki pewaris, ayah pewaris, serta saudara sedarah laki-laki mengalihkan penerimaan asabah.',
            ],
            'dalil' => [
                'verse' => null,
                'pasals' => [174],
                'catatan' => 'JUJUR: KHI tidak memuat pasal bagian saudara seayah laki-laki; status asabah mengikuti kaidah mazhab.',
            ],
        ],

        'sister_paternal' => [
            'definisi' => 'Saudara seayah perempuan (ukht li-abi) berbagi ayah saja dengan pewaris. Pada kasus kalalah KHI Pasal 182 menyamakan perlakuan bagian dengan saudara sedarah perempuan: sendiri 1/2; dua lebih 2/3; bersama saudara laki-laki (kandung/seayah) 2:1.',
            'syarat' => [
                'Nasab sah dari ayah pewaris.',
                'Pada status kalalah: tidak ada anak dan ayah pewaris.',
                'Tidak terhalang hukum (KHI Pasal 173).',
            ],
            'menghalangi' => [
                'Saudara seayah perempuan menghalangi kerabat perempuan lebih jauh.',
                'Bersama saudara laki-laki kandung/seayah, ia berbagi dengan rasio 2:1.',
            ],
            'dihalangi' => [
                'Saudara sedarah perempuan menghalangi; juga anak laki-laki/puteri pewaris pada kondisi tertentu.',
                'Anak laki-laki pewaris, ayah pewaris, serta saudara sedarah perempuan (prioritas fard).',
            ],
            'dalil' => [
                'verse' => 'QURAN-4-176',
                'pasals' => [182],
            ],
        ],

        'uncle_paternal' => [
            'definisi' => 'Paman seayah (al-amm) adalah saudara laki-laki ayah pewaris. Asabah bil-ghair: menerima sisa harta bila tidak ada ahli waris asabah yang lebih utama (anak laki-laki, ayah, saudara sedarah/seayah laki-laki).',
            'syarat' => [
                'Nasab sah dari garis ayah pewaris.',
                'Asabah prioritas tidak ada (kaidah klasik).',
                'Tidak terhalang hukum (KHI Pasal 173).',
            ],
            'menghalangi' => [
                'Paman seayah menghalangi kerabat asabah yang lebih jauh (sepupu seusia jalur lebih bawah).',
            ],
            'dihalangi' => [
                'Seluruh kelompok fard inti KHI Pasal 174 (anak, ayah, ibu, janda/duda).',
                'Asabah prioritas: anak laki-laki, ayah, saudara sedarah laki-laki.',
            ],
            'dalil' => [
                'verse' => null,
                'pasals' => [174],
                'catatan' => 'JUJUR: KHI tidak memuat pasal bagian paman seayah; status asabah mengikuti kaidah mazhab. Verifikasi ahli faraid.',
            ],
        ],

        'cousin_paternal_son' => [
            'definisi' => 'Sepupu dari paman seayah (ibn al-amm) — anak laki-laki paman seayah pewaris. Asabah bil-ghair: menerima sisa bila jalur asabah lebih utama terputus.',
            'syarat' => [
                'Nasab sah dari paman seayah pewaris.',
                'Asabah yang lebih utama tidak ada (kaidah klasik).',
                'Tidak terhalang hukum (KHI Pasal 173).',
            ],
            'menghalangi' => [
                'Sepupu menghalangi kerabat asabah yang jaraknya lebih jauh (anak-anak saudara paman seayah).',
            ],
            'dihalangi' => [
                'Anak laki-laki pewaris, ayah, saudara sedarah/seayah laki-laki, dan paman seayah menghalangi penerimaan.',
            ],
            'dalil' => [
                'verse' => null,
                'pasals' => [174],
                'catatan' => 'JUJUR: KHI tidak memuat pasal bagian sepupu; posisi mengikuti kaidah ta\'sib mazhab. Verifikasi ahli faraid.',
            ],
        ],
    ],
];
