@extends('layouts.app')

@section('title', 'Contoh Kasus Hukum Waris Perdata')

@section('content')
<div class="mx-auto max-w-4xl space-y-6 pb-32">
    @include('partials.back-button')
    <section>
        <p class="u-kicker text-gold-deep">Hukum Waris Perdata</p>
        <h1 class="u-display mt-1.5 text-2xl text-ink md:text-3xl">Contoh Kasus</h1>
        <p class="mt-2 text-sm text-ink/70">Simulasi pendidikan. Setiap kasus dilengkapi fakta, pertanyaan hukum, dasar hukum, dan kesimpulan pembelajaran.</p>
    </section>

    <div class="space-y-4">
        <div class="u-smart-card p-5">
            <h2 class="text-sm font-bold text-ink">Kasus 1 — Pewaris meninggalkan pasangan dan anak</h2>
            <p class="mt-1 text-xs text-ink/70">Fakta: Pewaris meninggal, meninggalkan istri dan dua anak (laki-laki dan perempuan). Semua harta adalah harta peninggalan.</p>
            <p class="mt-2 text-xs text-ink/80"><strong>Pertanyaan hukum:</strong> Bagaimana pembagian antara pasangan dan anak? <strong>Dasar:</strong> KUHPerdata (Golongan I, bagian pasangan). <strong>Tahapan:</strong> Identifikasi ahli waris → golongan → pembagian berdasarkan ketentuan. <strong>Kesimpulan:</strong> Pasangan mendapat bagian tertentu, sisa dibagi di antara anak-anak. <strong>Catatan:</strong> Nominal bergantung ketentuan yang berlaku; perlu verifikasi pasal.</p>
        </div>

        <div class="u-smart-card p-5">
            <h2 class="text-sm font-bold text-ink">Kasus 2 — Pewaris tidak mempunyai keturunan</h2>
            <p class="mt-1 text-xs text-ink/70">Fakta: Pewaris meninggal tanpa anak, meninggalkan pasangan dan orang tua.</p>
            <p class="mt-2 text-xs text-ink/80"><strong>Pertanyaan:</strong> Siapa yang mewaris? <strong>Dasar:</strong> KUHPerdata Golongan II. <strong>Analisis:</strong> Pasangan dan orang tua berhak. <strong>Catatan:</strong> Bagian bergantung ketentuan.</p>
        </div>

        <div class="u-smart-card p-5">
            <h2 class="text-sm font-bold text-ink">Kasus 3 — Terjadi penggantian tempat</h2>
            <p class="mt-1 text-xs text-ink/70">Fakta: Pewaris meninggal, anaknya (laki-laki) telah meninggal lebih dahulu tetapi memiliki cucu.</p>
            <p class="mt-2 text-xs text-ink/80"><strong>Pertanyaan:</strong> Siapa yang menggantikan kedudukan anak? <strong>Dasar:</strong> KUHPerdata Pasal 838 (plaatsvervulling). <strong>Analisis:</strong> Cucu menggantikan kedudukan orang tuanya. <strong>Catatan:</strong> Perlu memeriksa syarat penggantian tempat.</p>
        </div>

        <div class="u-smart-card p-5">
            <h2 class="text-sm font-bold text-ink">Kasus 4 — Terdapat wasiat</h2>
            <p class="mt-1 text-xs text-ink/70">Fakta: Pewaris membuat wasiat memberikan 1/2 harta kepada pihak ketiga, meninggalkan pasangan dan anak.</p>
            <p class="mt-2 text-xs text-ink/80"><strong>Pertanyaan:</strong> Apakah wasiat tersebut sah? <strong>Dasar:</strong> KUHPerdata wasiat + legitime portie. <strong>Analisis:</strong> Wasiat yang melebihi batas legitime portie dapat dikurangi. <strong>Catatan:</strong> Persentase legitime portie perlu verifikasi.</p>
        </div>

        <div class="u-smart-card p-5">
            <h2 class="text-sm font-bold text-ink">Kasus 5 — Terdapat hibah semasa hidup</h2>
            <p class="mt-1 text-xs text-ink/70">Fakta: Pewaris memberikan hibah sebidang tanah kepada salah satu anak semasa hidup.</p>
            <p class="mt-2 text-xs text-ink/80"><strong>Pertanyaan:</strong> Apakah hibah diperhitungkan dalam warisan? <strong>Dasar:</strong> KUHPerdata hibah + inbreng. <strong>Analisis:</strong> Tergantung ketentuan inbreng yang berlaku. <strong>Catatan:</strong> Tidak semua pemberian diperlakukan sama.</p>
        </div>

        <div class="u-smart-card p-5">
            <h2 class="text-sm font-bold text-ink">Kasus 6 — Terdapat utang pewaris</h2>
            <p class="mt-1 text-xs text-ink/70">Fakta: Pewaris meninggalkan harta dan utang yang cukup besar.</p>
            <p class="mt-2 text-xs text-ink/80"><strong>Pertanyaan:</strong> Siapa yang membayar utang? <strong>Dasar:</strong> KUHPerdata (boedel, utang). <strong>Analisis:</strong> Utang dibayar dari harta peninggalan; ahli waris dapat memilih penerimaan murni atau beneficiaire. <strong>Catatan:</strong> Risiko pribadi bergantung pilihan.</p>
        </div>
    </div>

    <p class="text-xs text-ink/50">
        Label: simulasi pendidikan. Nominal pembagian tidak dipaksakan apabila dasar hukumnya belum cukup.
    </p>
</div>
@endsection