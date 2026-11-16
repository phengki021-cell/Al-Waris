@extends('layouts.app')

@section('title', 'Admin — Profil')

@section('content')
<div class="flex items-center justify-between gap-3">
    <div>
        <p class="u-kicker text-gold-deep">Panel Admin</p>
        <h1 class="u-display mt-1.5 text-2xl text-ink md:text-3xl">Profil &amp; Keamanan</h1>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="rounded-xl border border-gold/40 px-4 py-2 text-sm font-bold text-gold-deep transition hover:bg-gold-soft">&larr; Dashboard</a>
</div>

@if (session('status'))
    <p class="mt-4 rounded-xl bg-emerald-50 p-3 text-sm font-semibold text-emerald-800 ring-1 ring-emerald-200">{{ session('status') }}</p>
@endif

<section class="u-smart-card mt-6 p-5 md:p-6">
    <h2 class="text-lg font-bold">Nama Admin</h2>
    <form method="POST" action="{{ route('admin.profile.name') }}" class="mt-3 flex flex-wrap items-end gap-3">
        @csrf
        @method('PUT')
        <div class="min-w-[240px] flex-1">
            <label class="block text-sm font-semibold text-ink/80">Nama tampilan</label>
            <input name="nama" value="{{ old('nama', $user->name) }}" required class="mt-1 w-full rounded-xl border border-black/10 bg-surface px-3 py-2.5 text-sm">
        </div>
        <button type="submit" class="u-btn-gold px-5 py-2.5 text-sm">Simpan Nama</button>
    </form>
    <p class="mt-2 text-xs text-ink/60">Email akun: <strong>{{ $user->email }}</strong> (tidak dapat diubah dari panel).</p>
</section>

<section class="u-smart-card mt-6 p-5 md:p-6">
    <h2 class="text-lg font-bold">Ganti Kata Sandi</h2>
    <form method="POST" action="{{ route('admin.profile.password') }}" class="mt-3 grid gap-4 max-w-md">
        @csrf
        @method('PUT')
        @if ($errors->has('kata_sandi_lama'))
            <p class="rounded-xl bg-red-50 p-3 text-sm text-red-900 ring-1 ring-red-200">{{ $errors->first('kata_sandi_lama') }}</p>
        @endif
        <div>
            <label class="block text-sm font-semibold text-ink/80">Kata sandi lama</label>
            <input type="password" name="kata_sandi_lama" required class="mt-1 w-full rounded-xl border border-black/10 bg-surface px-3 py-2.5 text-sm">
        </div>
        <div>
            <label class="block text-sm font-semibold text-ink/80">Kata sandi baru (minimal 8 karakter)</label>
            <input type="password" name="kata_sandi_baru" required minlength="8" class="mt-1 w-full rounded-xl border border-black/10 bg-surface px-3 py-2.5 text-sm">
        </div>
        <div>
            <label class="block text-sm font-semibold text-ink/80">Konfirmasi kata sandi baru</label>
            <input type="password" name="kata_sandi_baru_konfirmasi" required minlength="8" class="mt-1 w-full rounded-xl border border-black/10 bg-surface px-3 py-2.5 text-sm">
        </div>
        <div>
            <button type="submit" class="u-btn-gold px-6 py-2.5 text-sm">Ganti Kata Sandi</button>
        </div>
    </form>
</section>
@endsection
