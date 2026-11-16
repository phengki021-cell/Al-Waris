@extends('layouts.app')

@section('title', $heir->exists ? 'Admin — Edit Ahli Waris' : 'Admin — Tambah Ahli Waris')

@section('content')
<div class="flex items-center justify-between gap-3">
    <h1 class="u-display text-2xl text-ink md:text-3xl">{{ $heir->exists ? 'Edit: '.$heir->name_id : 'Tambah Ahli Waris' }}</h1>
    <a href="{{ route('admin.heirs') }}" class="rounded-xl border border-gold/40 px-4 py-2 text-sm font-bold text-gold-deep transition hover:bg-gold-soft">&larr; Kembali</a>
</div>

@if ($errors->any())
    <div class="mt-4 rounded-xl bg-red-50 p-3 text-sm text-red-700 ring-1 ring-red-200">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<form method="POST" action="{{ $heir->exists ? route('admin.heirs.update', $heir) : route('admin.heirs.store') }}" class="mt-5 grid gap-4 md:grid-cols-2">
    @csrf
    @if ($heir->exists)
        @method('PUT')
    @endif

    <div>
        <label class="block text-sm font-semibold text-ink/80">Kode (unik, bahasa Inggris) <span class="text-red-600">*</span></label>
        <input name="code" value="{{ old('code', $heir->code) }}" required class="mt-1 w-full rounded-xl border border-black/10 bg-surface px-3 py-2.5 text-sm">
    </div>
    <div>
        <label class="block text-sm font-semibold text-ink/80">Nama (Indonesia) <span class="text-red-600">*</span></label>
        <input name="name_id" value="{{ old('name_id', $heir->name_id) }}" required class="mt-1 w-full rounded-xl border border-black/10 bg-surface px-3 py-2.5 text-sm">
    </div>
    <div>
        <label class="block text-sm font-semibold text-ink/80">Nama Arab (latin)</label>
        <input name="name_ar" value="{{ old('name_ar', $heir->name_ar) }}" class="mt-1 w-full rounded-xl border border-black/10 bg-surface px-3 py-2.5 text-sm">
    </div>
    <div>
        <label class="block text-sm font-semibold text-ink/80">Slug</label>
        <input name="slug" value="{{ old('slug', $heir->slug) }}" class="mt-1 w-full rounded-xl border border-black/10 bg-surface px-3 py-2.5 text-sm">
    </div>
    <div>
        <label class="block text-sm font-semibold text-ink/80">Jenis kelamin</label>
        <select name="gender" class="mt-1 w-full rounded-xl border border-black/10 bg-surface px-3 py-2.5 text-sm">
            <option value="m" @selected(old('gender', $heir->gender) === 'm')>Laki-laki</option>
            <option value="f" @selected(old('gender', $heir->gender) === 'f')>Perempuan</option>
        </select>
    </div>
    <div>
        <label class="block text-sm font-semibold text-ink/80">Golongan</label>
        <select name="category" class="mt-1 w-full rounded-xl border border-black/10 bg-surface px-3 py-2.5 text-sm">
            @foreach (['fard' => 'Dzawil Furudh', 'asabah' => 'Ashabah', 'arham' => 'Dzawil Arham', 'hajib' => 'Hajib'] as $val => $label)
                <option value="{{ $val }}" @selected(old('category', $heir->category) === $val)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-sm font-semibold text-ink/80">Pembagi maks (angka)</label>
            <input name="max_share_numerator" type="number" min="0" value="{{ old('max_share_numerator', $heir->max_share_numerator) }}" class="mt-1 w-full rounded-xl border border-black/10 bg-surface px-3 py-2.5 text-sm">
        </div>
        <div>
            <label class="block text-sm font-semibold text-ink/80">Penyebut maks</label>
            <input name="max_share_denominator" type="number" min="1" value="{{ old('max_share_denominator', $heir->max_share_denominator) }}" class="mt-1 w-full rounded-xl border border-black/10 bg-surface px-3 py-2.5 text-sm">
        </div>
    </div>
    <div class="flex items-end">
        <label class="flex items-center gap-2 text-sm font-semibold text-ink/80">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $heir->exists ? $heir->is_active : true)) class="h-4 w-4"> Aktif (tampil publik)
        </label>
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-ink/80">Penjelasan singkat <span class="text-red-600">*</span></label>
        <textarea name="detail" rows="4" required class="mt-1 w-full rounded-xl border border-black/10 bg-surface px-3 py-2.5 text-sm">{{ old('detail', $heir->detail) }}</textarea>
    </div>
    <div class="md:col-span-2">
        <button type="submit" class="u-btn-gold px-6 py-2.5 text-sm">{{ $heir->exists ? 'Simpan Perubahan' : 'Tambah Ahli Waris' }}</button>
    </div>
</form>
@endsection
