@extends('layouts.app')

@section('title', 'Surat Wasiat')

@section('content')
<div class="mx-auto max-w-4xl space-y-6 pb-32">
    @include('partials.back-button')
    <section>
        <p class="u-kicker text-gold-deep">Hukum Waris Perdata</p>
        <h1 class="u-display mt-1.5 text-2xl text-ink md:text-3xl">Surat Wasiat</h1>
        <p class="mt-2 text-sm text-ink/70">Pengertian, bentuk, syarat, dan informasi wasiat menurut KUHPerdata. Dokumen yang dibuat aplikasi tidak otomatis sah sebagai akta resmi.</p>
    </section>

    <div class="u-smart-card p-5 space-y-3">
        <h2 class="text-sm font-bold text-ink">Pengertian</h2>
        <p class="text-sm text-ink/80">Wasiat adalah pernyataan kehendak seseorang yang dibuat menurut bentuk yang ditentukan undang-undang, berisi tentang apa yang dikehendaki terjadi setelah ia meninggal, yang dapat dicabut selama hidup.</p>
        <h2 class="text-sm font-bold text-ink mt-3">Bentuk wasiat</h2>
        <ul class="mt-1 list-disc pl-5 text-sm text-ink/80">
            <li>Wasiat umum (notariil) — dibuat di hadapan notaris.</li>
            <li>Wasiat olografis — ditulis tangan oleh pewaris.</li>
            <li>Wasiat tertutup/rahasia — jika relevan.</li>
        </ul>
        <h2 class="text-sm font-bold text-ink mt-3">Syarat</h2>
        <ul class="mt-1 list-disc pl-5 text-sm text-ink/80">
            <li>Pewaris cakap (dewasa, berakal sehat).</li>
            <li>Dibuat menurut bentuk yang ditentukan undang-undang.</li>
            <li>Tidak bertentangan dengan legitime portie.</li>
            <li>Dapat dicabut atau diubah selama pewaris hidup.</li>
        </ul>
        <h2 class="text-sm font-bold text-ink mt-3">Catatan hukum</h2>
        <p class="text-sm text-ink/80">Keabsahan wasiat bergantung pada pemenuhan syarat hukum yang berlaku. Aplikasi ini hanya membantu pencatatan, bukan pengesahan resmi.</p>
        <p class="mt-3 text-xs text-ink/50">Sumber: KUHPerdata Pasal 875–912 — perlu verifikasi teks resmi.</p>
    </div>
</div>
@endsection