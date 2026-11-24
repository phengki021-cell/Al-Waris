@extends('layouts.app')

@section('title', 'Materi Hukum Waris Perdata')

@section('content')
<div class="mx-auto max-w-5xl space-y-6 pb-32">
    @include('partials.back-button')

    <section>
        <p class="u-kicker text-gold-deep">Hukum Waris Perdata</p>
        <h1 class="u-display mt-1.5 text-2xl text-ink md:text-3xl">Materi Hukum Waris Perdata</h1>
        <p class="mt-2 text-sm text-ink/70">
            Materi pembelajaran berdasarkan KUHPerdata (BW). Semua rujukan pasal ditandai status verifikasi.
            Konten yang belum terverifikasi diberi label "perlu pemeriksaan lanjutan".
        </p>
    </section>

    {{-- DAFTAR ISI --}}
    <nav class="u-smart-card p-4 md:p-5">
        <p class="u-kicker text-gold-deep">Daftar Isi</p>
        <ol class="mt-3 grid gap-1 text-sm text-ink/80 md:grid-cols-2">
            <li><a href="#bab1" class="hover:text-[#123D32]">1. Pengantar Hukum Waris Perdata</a></li>
            <li><a href="#bab2" class="hover:text-[#123D32]">2. Dasar Hukum</a></li>
            <li><a href="#bab3" class="hover:text-[#123D32]">3. Golongan Ahli Waris</a></li>
            <li><a href="#bab4" class="hover:text-[#123D32]">4. Kedudukan Pasangan</a></li>
            <li><a href="#bab5" class="hover:text-[#123D32]">5. Penggantian Tempat (Plaatsvervulling)</a></li>
            <li><a href="#bab6" class="hover:text-[#123D32]">6. Anak dalam Hukum Waris Perdata</a></li>
            <li><a href="#bab7" class="hover:text-[#123D32]">7. Orang yang Tidak Patut Mewaris</a></li>
            <li><a href="#bab8" class="hover:text-[#123D32]">8. Wasiat (Testament)</a></li>
            <li><a href="#bab9" class="hover:text-[#123D32]">9. Legitime Portie</a></li>
            <li><a href="#bab10" class="hover:text-[#123D32]">10. Hibah dan Pemberian Semasa Hidup</a></li>
            <li><a href="#bab11" class="hover:text-[#123D32]">11. Penerimaan dan Penolakan Warisan</a></li>
            <li><a href="#bab12" class="hover:text-[#123D32]">12. Harta Peninggalan, Utang, Kewajiban</a></li>
            <li><a href="#bab13" class="hover:text-[#123D32]">13. Tahapan Penyelesaian Waris Perdata</a></li>
            <li><a href="#bab14" class="hover:text-[#123D32]">14. Contoh Kasus dan Pembahasan</a></li>
        </ol>
    </nav>

    {{-- BAB 1 --}}
    <section id="bab1" class="u-smart-card p-5 md:p-6">
        <p class="u-kicker text-gold-deep">Bab 1</p>
        <h2 class="u-display mt-1.5 text-xl text-ink md:text-2xl">Pengantar Hukum Waris Perdata</h2>

        <h3 class="mt-4 text-sm font-bold text-ink">Pengertian</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            Hukum waris perdata adalah bagian hukum perdata yang mengatur peralihan harta (boedel) seseorang
            yang telah meninggal dunia kepada orang-orang yang berhak (ahli waris), baik berdasarkan
            undang-undang (ab intestato) maupun berdasarkan wasiat (testamentair).
        </p>

        <h3 class="mt-4 text-sm font-bold text-ink">Istilah penting</h3>
        <ul class="mt-2 space-y-1 text-sm text-ink/80">
            <li><strong>Pewaris</strong>: orang yang meninggal dan meninggalkan harta.</li>
            <li><strong>Ahli waris</strong>: orang yang berhak menerima harta peninggalan.</li>
            <li><strong>Harta peninggalan (boedel)</strong>: seluruh harta dan kewajiban pewaris yang dapat dialihkan.</li>
            <li><strong>Pewarisan</strong>: proses peralihan harta dari pewaris ke ahli waris.</li>
            <li><strong>Warisan terbuka</strong>: terjadi pada saat kematian pewaris (Pasal 830 KUHPerdata — perlu verifikasi teks).</li>
        </ul>

        <h3 class="mt-4 text-sm font-bold text-ink">Pewarisan berdasarkan undang-undang vs wasiat</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            <strong>Ab intestato</strong>: jika pewaris tidak membuat wasiat, pembagian mengikuti golongan ahli waris
            menurut undang-undang. <strong>Testamentair</strong>: jika pewaris membuat wasiat yang sah, sebagian harta
            dapat diberikan sesuai kehendak pewaris, tetapi tetap dibatasi oleh legitime portie.
        </p>

        <h3 class="mt-4 text-sm font-bold text-ink">Perbedaan dengan Hukum Waris Islam</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            Hukum waris Islam (faraid) menentukan bagian pasti (dzawil furudh), asabah, radd, aul, gharrawain,
            dan hijab berdasarkan Al-Qur'an, Sunnah, dan KHI. Hukum waris perdata (KUHPerdata) menggunakan sistem
            empat golongan ahli waris ab intestato, wasiat, dan legitieme portie. Keduanya berbeda sistem dan tidak
            dapat dicampur dalam satu perhitungan.
        </p>

        <h3 class="mt-4 text-sm font-bold text-ink">Contoh sederhana</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            Budi meninggal meninggalkan istri dan dua anak. Jika Budi tidak membuat wasiat, harta peninggalannya
            dibagi menurut golongan ahli waris perdata. Jika Budi membuat wasiat, sebagian harta dapat diberikan
            kepada pihak lain, tetapi bagian anak-anak (legitime portie) tetap dilindungi.
        </p>

        <p class="mt-3 text-xs text-ink/50">
            Sumber: KUHPerdata (BW) Buku II (rentang Pasal 830–1033) — perlu verifikasi teks pasal terhadap naskah resmi.
        </p>
    </section>

    {{-- BAB 2 --}}
    <section id="bab2" class="u-smart-card p-5 md:p-6">
        <p class="u-kicker text-gold-deep">Bab 2</p>
        <h2 class="u-display mt-1.5 text-xl text-ink md:text-2xl">Dasar Hukum Waris Perdata</h2>

        <h3 class="mt-4 text-sm font-bold text-ink">Terbukanya warisan</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            Warisan terbuka karena kematian seseorang. Pada saat itu, harta pewaris beralih kepada ahli waris
            (ab intestato) atau kepada penerima wasiat (testamentair), tunduk pada ketentuan KUHPerdata.
        </p>

        <h3 class="mt-4 text-sm font-bold text-ink">Syarat menjadi ahli waris</h3>
        <ul class="mt-2 space-y-1 text-sm text-ink/80">
            <li>Mempunyai hubungan keluarga dengan pewaris sesuai ketentuan.</li>
            <li>Belum meninggal sebelum pewaris (atau digantikan melalui penggantian tempat).</li>
            <li>Tidak dinyatakan tidak patut mewaris (onwaardig).</li>
            <li>Tidak menolak warisan.</li>
        </ul>

        <h3 class="mt-4 text-sm font-bold text-ink">Orang yang tidak patut mewaris</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            KUHPerdata mengatur ketidakpatutan (onwaardigheid) dalam kondisi tertentu, misalnya pembunuhan terhadap
            pewaris, pencemaran nama baik yang berat, dan lain-lain (Pasal 840 KUHPerdata — perlu verifikasi teks).
            Akibatnya, orang tersebut dianggap tidak pernah menjadi ahli waris.
        </p>

        <h3 class="mt-4 text-sm font-bold text-ink">Golongan ahli waris</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            KUHPerdata mengatur empat golongan ahli waris ab intestato (Pasal 832 KUHPerdata — perlu verifikasi teks).
            Golongan yang lebih dekat mengecualikan golongan yang lebih jauh.
        </p>

        <h3 class="mt-4 text-sm font-bold text-ink">Penggantian tempat</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            Penggantian tempat (plaatsvervulling) memungkinkan keturunan menggantikan kedudukan orang tua yang
            telah meninggal lebih dahulu (Pasal 838 KUHPerdata — perlu verifikasi teks).
        </p>

        <h3 class="mt-4 text-sm font-bold text-ink">Wasiat</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            Pewaris dapat membuat wasiat untuk sebagian hartanya, tunduk pada batas legitime portie
            (Pasal 875–912 KUHPerdata — perlu verifikasi teks).
        </p>

        <h3 class="mt-4 text-sm font-bold text-ink">Legitime portie</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            Bagian mutlak (legitime portie) melindungi ahli waris tertentu agar tidak kehilangan seluruh bagiannya
            akibat wasiat (Pasal 913–928 KUHPerdata — perlu verifikasi teks).
        </p>

        <h3 class="mt-4 text-sm font-bold text-ink">Penolakan dan penerimaan warisan</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            Ahli waris dapat menerima warisan secara murni, menerima dengan hak istimewa (beneficiaire), atau
            menolak (Pasal 959–975 KUHPerdata — perlu verifikasi teks).
        </p>

        <p class="mt-3 text-xs text-ink/50">
            Sumber: KUHPerdata (BW) Buku II. Semua rujukan pasal ditandai "perlu verifikasi teks" karena belum
            diverifikasi terhadap naskah resmi.
        </p>
    </section>

    {{-- BAB 3 --}}
    <section id="bab3" class="u-smart-card p-5 md:p-6">
        <p class="u-kicker text-gold-deep">Bab 3</p>
        <h2 class="u-display mt-1.5 text-xl text-ink md:text-2xl">Golongan Ahli Waris</h2>

        <p class="mt-2 text-sm leading-relaxed text-ink/80">
            KUHPerdata mengatur empat golongan ahli waris ab intestato. Golongan yang lebih dekat
            mengecualikan golongan yang lebih jauh. Urutan: Golongan I → II → III → IV.
        </p>

        <h3 class="mt-4 text-sm font-bold text-ink">Golongan I</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            Terdiri dari: keturunan (anak, cucu, dst.) dan pasangan yang hidup terlama (suami/istri).
            Mereka adalah ahli waris utama. Jika ada keturunan, pasangan mendapat bagian tertentu
            dan sisa dibagi di antara keturunan.
        </p>

        <h3 class="mt-4 text-sm font-bold text-ink">Golongan II</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            Terdiri dari: orang tua, saudara (kandung/seayah/seibu), dan keturunan mereka.
            Berhak mewaris jika tidak ada keturunan (Golongan I) yang hidup.
        </p>

        <h3 class="mt-4 text-sm font-bold text-ink">Golongan III</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            Terdiri dari: kakek, nenek, dan keturunan dari mereka (paman/bibi, sepupu).
            Berhak mewaris jika tidak ada Golongan I dan II.
        </p>

        <h3 class="mt-4 text-sm font-bold text-ink">Golongan IV</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            Terdiri dari: sanak saudara yang lebih jauh (keluarga sedarah) dari pewaris.
            Berhak mewaris jika tidak ada Golongan I, II, dan III.
        </p>

        <h3 class="mt-4 text-sm font-bold text-ink">Prinsip penggolongan</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            Semakin dekat hubungan keluarga, semakin berhak mewaris. Golongan yang lebih dekat
            mengecualikan golongan yang lebih jauh. Dalam setiap golongan, perhitungan bagian
            mengikuti ketentuan KUHPerdata (perlu verifikasi pasal).
        </p>

        <p class="mt-3 text-xs text-ink/50">
            Sumber: KUHPerdata Pasal 832 (golongan ahli waris) — perlu verifikasi teks resmi.
        </p>
    </section>

    {{-- BAB 4 --}}
    <section id="bab4" class="u-smart-card p-5 md:p-6">
        <p class="u-kicker text-gold-deep">Bab 4</p>
        <h2 class="u-display mt-1.5 text-xl text-ink md:text-2xl">Kedudukan Pasangan yang Ditinggalkan</h2>

        <p class="mt-2 text-sm leading-relaxed text-ink/80">
            Pasangan yang hidup terlama (suami/istri) memiliki kedudukan penting dalam pewarisan.
            Kedudukannya berbeda tergantung apakah ada keturunan atau tidak.
        </p>

        <h3 class="mt-4 text-sm font-bold text-ink">Kedudukan pasangan</h3>
        <ul class="mt-2 space-y-1 text-sm text-ink/80">
            <li>Jika ada anak-anak: pasangan menerima bagian tertentu (mis. 1/4 atau 1/2 tergantung kondisi).</li>
            <li>Jika tidak ada anak: pasangan menerima bagian lebih besar (mis. 1/2 atau seluruhnya).</li>
            <li>Pasangan juga berhak atas harta bersama (gono-gini) yang harus dipisahkan terlebih dahulu.</li>
        </ul>

        <h3 class="mt-4 text-sm font-bold text-ink">Harta bersama dan harta pribadi</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            Sebelum menghitung warisan, harta bersama (gono-gini) harus dipisahkan: separuh milik pasangan,
            separuh milik pewaris. Harta pribadi (bawaan) pewaris seluruhnya menjadi harta peninggalan.
            Jangan menyamakan seluruh harta perkawinan dengan harta warisan.
        </p>

        <h3 class="mt-4 text-sm font-bold text-ink">Utang dan kewajiban</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            Utang pewaris dibayar dari harta peninggalan sebelum pembagian. Ahli waris tidak otomatis
            wajib membayar utang dari harta pribadinya (tergantung pilihan penerimaan warisan).
        </p>

        <p class="mt-3 text-xs text-ink/50">
            Sumber: KUHPerdata Pasal 833–834 (bagian pasangan) — perlu verifikasi teks resmi.
        </p>
    </section>

    {{-- BAB 5 --}}
    <section id="bab5" class="u-smart-card p-5 md:p-6">
        <p class="u-kicker text-gold-deep">Bab 5</p>
        <h2 class="u-display mt-1.5 text-xl text-ink md:text-2xl">Penggantian Tempat (Plaatsvervulling)</h2>

        <h3 class="mt-4 text-sm font-bold text-ink">Pengertian</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            Penggantian tempat (plaatsvervulling) adalah mekanisme di mana keturunan menggantikan kedudukan
            orang tuanya yang telah meninggal lebih dahulu, sehingga memperoleh bagian yang seharusnya
            diterima orang tuanya.
        </p>

        <h3 class="mt-4 text-sm font-bold text-ink">Kapan terjadi</h3>
        <ul class="mt-2 space-y-1 text-sm text-ink/80">
            <li>Anak pewaris telah meninggal sebelum pewaris, tetapi meninggalkan keturunan (cucu).</li>
            <li>Keturunan tersebut menggantikan kedudukan orang tuanya (pasal penggantian tempat).</li>
            <li>Penggantian tempat tidak sama dengan pembagian biasa — ia mengikuti aturan khusus.</li>
        </ul>

        <h3 class="mt-4 text-sm font-bold text-ink">Contoh kasus</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            Budi meninggal, meninggalkan seorang anak laki-laki (Andi) yang telah meninggal lebih dahulu
            tetapi memiliki dua anak (cucu Budi). Cucu-cucu tersebut menggantikan kedudukan Andi dalam
            pembagian warisan Budi, dengan bagian yang seharusnya diterima Andi.
        </p>

        <p class="mt-3 text-xs text-ink/50">
            Sumber: KUHPerdata Pasal 838 (plaatsvervulling) — perlu verifikasi teks resmi.
        </p>
    </section>

    {{-- BAB 6 --}}
    <section id="bab6" class="u-smart-card p-5 md:p-6">
        <p class="u-kicker text-gold-deep">Bab 6</p>
        <h2 class="u-display mt-1.5 text-xl text-ink md:text-2xl">Anak dalam Hukum Waris Perdata</h2>

        <h3 class="mt-4 text-sm font-bold text-ink">Anak sah</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            Anak yang lahir dari perkawinan yang sah. Kedudukannya penuh sebagai ahli waris.
        </p>

        <h3 class="mt-4 text-sm font-bold text-ink">Anak luar kawin</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            Kedudukan anak luar kawin dalam pewarisan telah mengalami perkembangan melalui putusan
            Mahkamah Konstitusi (MK) dan peraturan terkait. Tidak semua kategori anak mempunyai kedudukan
            hukum yang sama. Perlu pemeriksaan terhadap peraturan terbaru dan putusan pengadilan.
        </p>

        <h3 class="mt-4 text-sm font-bold text-ink">Pengakuan dan pengesahan</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            Anak luar kawin dapat memperoleh kedudukan tertentu melalui pengakuan atau pengesahan
            sesuai ketentuan yang berlaku. Kedudukannya dalam pewarisan bergantung pada status hukumnya.
        </p>

        <h3 class="mt-4 text-sm font-bold text-ink">Anak angkat</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            Kedudukan anak angkat dalam pewarisan perdata memiliki ketentuan khusus yang berbeda dari
            anak kandung. Perlu pemeriksaan terhadap peraturan terkait.
        </p>

        <p class="mt-3 text-xs text-ink/50">
            Sumber: KUHPerdata + perkembangan putusan MK dan peraturan terkait — perlu verifikasi terhadap
            naskah resmi dan putusan terbaru.
        </p>
    </section>

    {{-- BAB 7 --}}
    <section id="bab7" class="u-smart-card p-5 md:p-6">
        <p class="u-kicker text-gold-deep">Bab 7</p>
        <h2 class="u-display mt-1.5 text-xl text-ink md:text-2xl">Orang yang Tidak Patut Mewaris (Onwaardigheid)</h2>

        <h3 class="mt-4 text-sm font-bold text-ink">Pengertian</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            Ketidakpatutan (onwaardigheid) adalah kondisi di mana seseorang dianggap tidak patut menerima
            warisan karena perbuatannya terhadap pewaris.
        </p>

        <h3 class="mt-4 text-sm font-bold text-ink">Dasar hukum</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            KUHPerdata mengatur ketidakpatutan dalam kondisi tertentu (Pasal 840 — perlu verifikasi teks).
            Akibatnya, orang tersebut dianggap tidak pernah menjadi ahli waris.
        </p>

        <h3 class="mt-4 text-sm font-bold text-ink">Contoh kondisi</h3>
        <ul class="mt-2 space-y-1 text-sm text-ink/80">
            <li>Pembunuhan atau percobaan pembunuhan terhadap pewaris.</li>
            <li>Pencemaran nama baik yang berat.</li>
            <li>Alasan lain yang diatur dalam ketentuan hukum yang terverifikasi.</li>
        </ul>

        <h3 class="mt-4 text-sm font-bold text-ink">Akibat hukum</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            Orang yang tidak patut dianggap tidak pernah menjadi ahli waris, sehingga bagiannya
            dibagi kepada ahli waris lain sesuai ketentuan.
        </p>

        <p class="mt-3 text-xs text-ink/50">
            Sumber: KUHPerdata Pasal 840 — perlu verifikasi teks resmi. Jangan mengarang alasan
            ketidakpatutan di luar ketentuan yang terverifikasi.
        </p>
    </section>

    {{-- BAB 8 --}}
    <section id="bab8" class="u-smart-card p-5 md:p-6">
        <p class="u-kicker text-gold-deep">Bab 8</p>
        <h2 class="u-display mt-1.5 text-xl text-ink md:text-2xl">Wasiat (Testament)</h2>

        <h3 class="mt-4 text-sm font-bold text-ink">Pengertian</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            Wasiat adalah pernyataan kehendak seseorang (pewaris) yang dibuat menurut bentuk yang
            ditentukan undang-undang, berisi tentang apa yang dikehendaki terjadi setelah ia meninggal,
            yang dapat dicabut kembali selama hidup.
        </p>

        <h3 class="mt-4 text-sm font-bold text-ink">Bentuk wasiat</h3>
        <ul class="mt-2 space-y-1 text-sm text-ink/80">
            <li><strong>Wasiat umum (notariil)</strong>: dibuat di hadapan notaris.</li>
            <li><strong>Wasiat olografis</strong>: ditulis tangan oleh pewaris, ditandatangani, dan diserahkan kepada notaris.</li>
            <li><strong>Wasiat rahasia/tertutup</strong>: apabila relevan dan dikenal dalam KUHPerdata.</li>
        </ul>

        <h3 class="mt-4 text-sm font-bold text-ink">Syarat wasiat</h3>
        <ul class="mt-2 space-y-1 text-sm text-ink/80">
            <li>Pewaris cakap (dewasa, berakal sehat).</li>
            <li>Dibuat menurut bentuk yang ditentukan undang-undang.</li>
            <li>Tidak bertentangan dengan legitime portie.</li>
            <li>Dapat dicabut atau diubah selama pewaris hidup.</li>
        </ul>

        <h3 class="mt-4 text-sm font-bold text-ink">Batasi kebebasan pewaris</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            Kebebasan membuat wasiat dibatasi oleh legitime portie. Pewaris tidak dapat memberikan
            seluruh hartanya kepada pihak lain jika ada ahli waris yang memiliki hak legitime portie.
        </p>

        <p class="mt-3 text-xs text-ink/50">
            Sumber: KUHPerdata Pasal 875–912 (wasiat) — perlu verifikasi teks resmi.
        </p>
    </section>

    {{-- BAB 9 --}}
    <section id="bab9" class="u-smart-card p-5 md:p-6">
        <p class="u-kicker text-gold-deep">Bab 9</p>
        <h2 class="u-display mt-1.5 text-xl text-ink md:text-2xl">Legitime Portie</h2>

        <h3 class="mt-4 text-sm font-bold text-ink">Pengertian</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            Legitime portie adalah bagian mutlak yang dilindungi undang-undang bagi ahli waris tertentu,
            sehingga pewaris tidak dapat mencabut atau menguranginya melalui wasiat atau hibah.
        </p>

        <h3 class="mt-4 text-sm font-bold text-ink">Tujuan</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            Melindungi ahli waris yang berhak (biasanya anak dan pasangan) agar tidak kehilangan
            seluruh bagian akibat kehendak pewaris.
        </p>

        <h3 class="mt-4 text-sm font-bold text-ink">Hubungan dengan wasiat dan hibah</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            Wasiat dan hibah yang melebihi batas legitime portie dapat dikurangi (reductie) agar
            bagian mutlak ahli waris terlindungi. Siapa yang berhak dan persentasenya bergantung
            pada susunan keluarga dan ketentuan yang berlaku — tidak ada satu persentase yang
            berlaku untuk semua.
        </p>

        <h3 class="mt-4 text-sm font-bold text-ink">Konsekuensi pelanggaran</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            Jika legitime portie dilanggar, ahli waris yang dirugikan dapat menuntut pengurangan
            pemberian (reductie) sesuai ketentuan hukum.
        </p>

        <p class="mt-3 text-xs text-ink/50">
            Sumber: KUHPerdata Pasal 913–928 (legitime portie) — perlu verifikasi teks resmi.
            Persentase dan dasar penghitungan harus diverifikasi terhadap ketentuan yang tepat.
        </p>
    </section>

    {{-- BAB 10 --}}
    <section id="bab10" class="u-smart-card p-5 md:p-6">
        <p class="u-kicker text-gold-deep">Bab 10</p>
        <h2 class="u-display mt-1.5 text-xl text-ink md:text-2xl">Hibah dan Pemberian Semasa Hidup</h2>

        <h3 class="mt-4 text-sm font-bold text-ink">Pengertian hibah</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            Hibah (schenking) adalah pemberian sukarela dari seseorang kepada orang lain tanpa
            imbalan, yang berlaku selama pewaris masih hidup.
        </p>

        <h3 class="mt-4 text-sm font-bold text-ink">Perbedaan hibah dan warisan</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            Warisan berlaku setelah kematian. Hibah berlaku selama pewaris hidup. Hibah mengurangi
            harta peninggalan yang akan dibagi sebagai warisan.
        </p>

        <h3 class="mt-4 text-sm font-bold text-ink">Pengaruh hibah terhadap legitime portie</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            Hibah yang diberikan semasa hidup dapat mempengaruhi legitime portie (inbreng) apabila
            ketentuan yang berlaku mensyaratkan pengembalian hibah ke dalam perhitungan warisan.
            Tidak semua pemberian semasa hidup diperlakukan sama.
        </p>

        <p class="mt-3 text-xs text-ink/50">
            Sumber: KUHPerdata Pasal 939–958 (hibah) — perlu verifikasi teks resmi.
        </p>
    </section>

    {{-- BAB 11 --}}
    <section id="bab11" class="u-smart-card p-5 md:p-6">
        <p class="u-kicker text-gold-deep">Bab 11</p>
        <h2 class="u-display mt-1.5 text-xl text-ink md:text-2xl">Penerimaan dan Penolakan Warisan</h2>

        <h3 class="mt-4 text-sm font-bold text-ink">Penerimaan murni</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            Ahli waris menerima warisan secara penuh beserta utang-utangnya. Harta pribadi ahli waris
            dapat terancam jika utang pewaris melebihi harta peninggalan.
        </p>

        <h3 class="mt-4 text-sm font-bold text-ink">Penerimaan dengan hak istimewa (beneficiaire)</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            Ahli waris menerima warisan dengan hak untuk mengadakan pencatatan harta peninggalan
            (inventaris), sehingga tanggung jawab utang terbatas pada harta peninggalan.
        </p>

        <h3 class="mt-4 text-sm font-bold text-ink">Penolakan warisan</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            Ahli waris dapat menolak warisan. Penolakan bersifat mutlak dan tidak dapat dicabut.
            Ahli waris yang menolak dianggap tidak pernah menjadi ahli waris.
        </p>

        <h3 class="mt-4 text-sm font-bold text-ink">Risiko</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            Menerima warisan tanpa memahami kewajiban hukumnya dapat merugikan. Inventarisasi
            harta dan utang sangat penting sebelum memutuskan.
        </p>

        <p class="mt-3 text-xs text-ink/50">
            Sumber: KUHPerdata Pasal 959–975 (penerimaan/penolakan) — perlu verifikasi teks resmi.
        </p>
    </section>

    {{-- BAB 12 --}}
    <section id="bab12" class="u-smart-card p-5 md:p-6">
        <p class="u-kicker text-gold-deep">Bab 12</p>
        <h2 class="u-display mt-1.5 text-xl text-ink md:text-2xl">Harta Peninggalan, Utang, dan Kewajiban</h2>

        <h3 class="mt-4 text-sm font-bold text-ink">Boedel warisan</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            Boedel adalah seluruh harta dan kewajiban pewaris yang dapat dialihkan pada saat
            kematian. Termasuk harta bergerak dan tidak bergerak, hak-hak, serta utang.
        </p>

        <h3 class="mt-4 text-sm font-bold text-ink">Identifikasi harta peninggalan</h3>
        <ul class="mt-2 space-y-1 text-sm text-ink/80">
            <li>Pisahkan harta bersama (gono-gini) dari harta pribadi pewaris.</li>
            <li>Catat semua aset: tanah, bangunan, rekening, investasi, barang berharga.</li>
            <li>Catat semua kewajiban: utang, biaya pengurusan, wasiat (maks 1/3).</li>
            <li>Lakukan inventarisasi sebelum pembagian.</li>
        </ul>

        <h3 class="mt-4 text-sm font-bold text-ink">Utang pewaris</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink/80">
            Utang pewaris dibayar dari harta peninggalan. Urutan: biaya pengurusan jenazah,
            biaya pengurusan warisan, utang, baru pembagian warisan.
        </p>

        <p class="mt-3 text-xs text-ink/50">
            Sumber: KUHPerdata Pasal 976–1003 (boedel, utang) — perlu verifikasi teks resmi.
        </p>
    </section>

    {{-- BAB 13 --}}
    <section id="bab13" class="u-smart-card p-5 md:p-6">
        <p class="u-kicker text-gold-deep">Bab 13</p>
        <h2 class="u-display mt-1.5 text-xl text-ink md:text-2xl">Tahapan Penyelesaian Waris Perdata</h2>

        <ol class="mt-3 space-y-2 text-sm text-ink/80">
            <li><strong>1. Pastikan peristiwa kematian</strong> — waktu terbukanya warisan.</li>
            <li><strong>2. Identifikasi sistem hukum</strong> — perdata, Islam, atau adat yang relevan.</li>
            <li><strong>3. Identifikasi status perkawinan</strong> — harta bersama vs pribadi.</li>
            <li><strong>4. Inventarisasi harta dan kewajiban</strong> — aset dan utang.</li>
            <li><strong>5. Pisahkan harta pewaris</strong> — dari harta milik pihak lain.</li>
            <li><strong>6. Identifikasi ahli waris</strong> — golongan I–IV.</li>
            <li><strong>7. Periksa penggantian tempat</strong> — plaatsvervulling.</li>
            <li><strong>8. Periksa wasiat dan hibah</strong> — testamentair.</li>
            <li><strong>9. Periksa legitime portie</strong> — bagian mutlak.</li>
            <li><strong>10. Periksa penerimaan/penolakan</strong> — murni, beneficiaire, atau tolak.</li>
            <li><strong>11. Tentukan pembagian</strong> — berdasarkan aturan yang berlaku.</li>
            <li><strong>12. Susun hasil dan dokumen</strong> — laporan dan bukti.</li>
        </ol>

        <p class="mt-3 text-xs text-ink/50">
            Setiap langkah diperlukan untuk memastikan pembagian hukum yang benar dan terdokumentasi.
        </p>
    </section>

    {{-- BAB 14 --}}
    <section id="bab14" class="u-smart-card p-5 md:p-6">
        <p class="u-kicker text-gold-deep">Bab 14</p>
        <h2 class="u-display mt-1.5 text-xl text-ink md:text-2xl">Contoh Kasus dan Pembahasan</h2>

        <div class="mt-4 space-y-4">
            <div class="rounded-xl border border-black/5 bg-surface/60 p-4">
                <p class="text-sm font-bold text-ink">Kasus 1 — Pewaris meninggalkan pasangan dan anak</p>
                <p class="mt-1 text-xs text-ink/70">Pewaris meninggal, meninggalkan istri dan dua anak (laki-laki dan perempuan). Semua harta adalah harta peninggalan.</p>
                <p class="mt-2 text-xs text-ink/80"><strong>Persoalan:</strong> Bagaimana pembagian antara pasangan dan anak-anak? <strong>Dasar hukum:</strong> KUHPerdata (golongan I, bagian pasangan). <strong>Analisis:</strong> Pasangan mendapat bagian tertentu, sisa dibagi di antara anak-anak. <strong>Catatan:</strong> Nominal bergantung pada kondisi dan ketentuan yang berlaku; perlu verifikasi pasal.</p>
            </div>

            <div class="rounded-xl border border-black/5 bg-surface/60 p-4">
                <p class="text-sm font-bold text-ink">Kasus 2 — Pewaris tidak mempunyai keturunan</p>
                <p class="mt-1 text-xs text-ink/70">Pewaris meninggal tanpa anak, meninggalkan pasangan dan orang tua.</p>
                <p class="mt-2 text-xs text-ink/80"><strong>Persoalan:</strong> Apakah pasangan dan orang tua mewaris? <strong>Dasar hukum:</strong> KUHPerdata golongan II (orang tua) + hak pasangan. <strong>Analisis:</strong> Pasangan dan orang tua berhak mewaris. <strong>Catatan:</strong> Bagian masing-masing bergantung ketentuan; perlu verifikasi pasal.</p>
            </div>

            <div class="rounded-xl border border-black/5 bg-surface/60 p-4">
                <p class="text-sm font-bold text-ink">Kasus 3 — Terjadi penggantian tempat</p>
                <p class="mt-1 text-xs text-ink/70">Pewaris meninggal, anaknya (laki-laki) telah meninggal lebih dahulu tetapi meninggalkan cucu.</p>
                <p class="mt-2 text-xs text-ink/80"><strong>Persoalan:</strong> Siapa yang menggantikan kedudukan anak? <strong>Dasar hukum:</strong> KUHPerdata Pasal 838 (plaatsvervulling). <strong>Analisis:</strong> Cucu menggantikan kedudukan orang tuanya. <strong>Catatan:</strong> Perlu memeriksa syarat penggantian tempat.</p>
            </div>

            <div class="rounded-xl border border-black/5 bg-surface/60 p-4">
                <p class="text-sm font-bold text-ink">Kasus 4 — Terdapat wasiat</p>
                <p class="mt-1 text-xs text-ink/70">Pewaris membuat wasiat memberikan 1/2 harta kepada pihak ketiga, meninggalkan pasangan dan anak.</p>
                <p class="mt-2 text-xs text-ink/80"><strong>Persoalan:</strong> Apakah wasiat tersebut sah? <strong>Dasar hukum:</strong> KUHPerdata wasiat + legitime portie. <strong>Analisis:</strong> Wasiat yang melebihi batas legitime portie dapat dikurangi. <strong>Catatan:</strong> Persentase legitime portie perlu verifikasi.</p>
            </div>

            <div class="rounded-xl border border-black/5 bg-surface/60 p-4">
                <p class="text-sm font-bold text-ink">Kasus 5 — Terdapat hibah semasa hidup</p>
                <p class="mt-1 text-xs text-ink/70">Pewaris memberikan hibah sebidang tanah kepada salah satu anak semasa hidup.</p>
                <p class="mt-2 text-xs text-ink/80"><strong>Persoalan:</strong> Apakah hibah diperhitungkan dalam warisan? <strong>Dasar hukum:</strong> KUHPerdata hibah + inbreng. <strong>Analisis:</strong> Tergantung ketentuan inbreng yang berlaku. <strong>Catatan:</strong> Tidak semua pemberian diperlakukan sama.</p>
            </div>

            <div class="rounded-xl border border-black/5 bg-surface/60 p-4">
                <p class="text-sm font-bold text-ink">Kasus 6 — Terdapat utang pewaris</p>
                <p class="mt-1 text-xs text-ink/70">Pewaris meninggalkan harta dan utang yang cukup besar.</p>
                <p class="mt-2 text-xs text-ink/80"><strong>Persoalan:</strong> Siapa yang membayar utang? <strong>Dasar hukum:</strong> KUHPerdata (boedel, utang). <strong>Analisis:</strong> Utang dibayar dari harta peninggalan; ahli waris dapat memilih penerimaan murni atau beneficiaire. <strong>Catatan:</strong> Risiko pribadi bergantung pilihan.</p>
            </div>

            <div class="rounded-xl border border-black/5 bg-surface/60 p-4">
                <p class="text-sm font-bold text-ink">Kasus 7 — Salah satu calon ahli waris menolak warisan</p>
                <p class="mt-1 text-xs text-ink/70">Salah satu anak menolak warisan.</p>
                <p class="mt-2 text-xs text-ink/80"><strong>Persoalan:</strong> Apa akibatnya? <strong>Dasar hukum:</strong> KUHPerdata (penolakan). <strong>Analisis:</strong> Penolakan bersifat mutlak; yang menolak dianggap tidak pernah menjadi ahli waris. <strong>Catatan:</strong> Bagian dialihkan ke ahli waris lain.</p>
            </div>

            <div class="rounded-xl border border-black/5 bg-surface/60 p-4">
                <p class="text-sm font-bold text-ink">Kasus 8 — Pewaris tidak mempunyai keturunan</p>
                <p class="mt-1 text-xs text-ink/70">Pewaris meninggal tanpa anak, meninggalkan pasangan dan orang tua.</p>
                <p class="mt-2 text-xs text-ink/80"><strong>Persoalan:</strong> Siapa yang mewaris? <strong>Dasar hukum:</strong> KUHPerdata golongan II. <strong>Analisis:</strong> Pasangan dan orang tua berhak. <strong>Catatan:</strong> Bagian bergantung ketentuan.</p>
            </div>

            <div class="rounded-xl border border-black/5 bg-surface/60 p-4">
                <p class="text-sm font-bold text-ink">Kasus 9 — Terdapat anak luar kawin yang diakui</p>
                <p class="mt-1 text-xs text-ink/70">Pewaris meninggalkan anak luar kawin yang telah diakui.</p>
                <p class="mt-2 text-xs text-ink/80"><strong>Persoalan:</strong> Apakah anak luar kawin berhak mewaris? <strong>Dasar hukum:</strong> KUHPerdata + perkembangan putusan MK. <strong>Analisis:</strong> Kedudukan bergantung status hukumnya. <strong>Catatan:</strong> Perlu pemeriksaan peraturan terbaru.</p>
            </div>

            <div class="rounded-xl border border-black/5 bg-surface/60 p-4">
                <p class="text-sm font-bold text-ink">Kasus 10 — Persoalan legitime portie</p>
                <p class="mt-1 text-xs text-ink/70">Pewaris membuat wasiat yang diduga melanggar bagian mutlak anak.</p>
                <p class="mt-2 text-xs text-ink/80"><strong>Persoalan:</strong> Apakah wasiat tersebut dapat dikurangi? <strong>Dasar hukum:</strong> KUHPerdata legitime portie. <strong>Analisis:</strong> Jika legitime portie dilanggar, dapat dituntut pengurangan. <strong>Catatan:</strong> Persentase perlu verifikasi.</p>
            </div>
        </div>

        <p class="mt-4 text-xs text-ink/50">
            Catatan: Nominal pembagian tidak dipaksakan apabila dasar hukumnya belum cukup atau fakta kasus belum lengkap.
            Kasus tersebut ditandai memerlukan analisis lebih lanjut.
        </p>
    </section>

    {{-- SUMBER REFERENSI --}}
    <section class="u-smart-card p-5 md:p-6">
        <p class="u-kicker text-gold-deep">Sumber Referensi</p>
        <h2 class="u-display mt-1.5 text-xl text-ink md:text-2xl">Sumber Hukum</h2>
        <ul class="mt-3 space-y-2 text-sm text-ink/80">
            <li><strong>KUHPerdata (Burgerlijk Wetboek)</strong> — Buku II, Pasal 830–1033 (rentang penting, perlu verifikasi teks resmi).</li>
            <li><strong>Naskah resmi KUHPerdata</strong> — diperiksa melalui JDIH dan sumber hukum resmi.</li>
            <li><strong>Putusan Mahkamah Agung</strong> — sumber resmi MA untuk perkembangan interpretasi.</li>
            <li><strong>Literatur hukum</strong> — buku dan jurnal yang dapat diidentifikasi secara jelas.</li>
        </ul>
        <p class="mt-3 text-xs text-ink/50">
            Status verifikasi: sebagian materi masih "perlu pemeriksaan lanjutan". Kutipan pasal yang belum
            diverifikasi ditampilkan sebagai ringkasan, bukan kutipan resmi.
        </p>
    </section>
</div>
@endsection