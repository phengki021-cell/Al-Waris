@extends('layouts.app')

@section('title', $share->exists ? 'Admin — Edit Bagian Warisan' : 'Admin — Tambah Bagian Warisan')

@section('content')
<div class="flex items-center justify-between gap-3">
    <h1 class="u-display text-2xl text-ink md:text-3xl">{{ $share->exists ? 'Edit Bagian Warisan' : 'Tambah Bagian Warisan' }}</h1>
    <a href="{{ route('admin.shares') }}" class="rounded-xl border border-gold/40 px-4 py-2 text-sm font-bold text-gold-deep transition hover:bg-gold-soft">&larr; Kembali</a>
</div>

@if ($errors->any())
    <div class="mt-4 rounded-xl bg-red-50 p-3 text-sm text-red-900 ring-1 ring-red-200">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<form method="POST" action="{{ $share->exists ? route('admin.shares.update', $share) : route('admin.shares.store') }}" class="mt-5 grid gap-4 md:grid-cols-2">
    @csrf
    @if ($share->exists)
        @method('PUT')
    @endif

    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-ink/80">Ahli waris <span class="text-red-800">*</span></label>
        <select name="heir_id" required class="mt-1 w-full rounded-xl border border-black/10 bg-surface px-3 py-2.5 text-sm">
            <option value="">— pilih —</option>
            @foreach ($heirs as $heir)
                <option value="{{ $heir->id }}" @selected(old('heir_id', $share->heir_id) == $heir->id)>{{ $heir->name_id }}</option>
            @endforeach
        </select>
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-sm font-semibold text-ink/80">Pembagi (numerator)</label>
            <input name="numerator" type="number" min="0" required value="{{ old('numerator', $share->numerator) }}" class="mt-1 w-full rounded-xl border border-black/10 bg-surface px-3 py-2.5 text-sm">
        </div>
        <div>
            <label class="block text-sm font-semibold text-ink/80">Penyebut (denominator)</label>
            <input name="denominator" type="number" min="1" required value="{{ old('denominator', $share->denominator) }}" class="mt-1 w-full rounded-xl border border-black/10 bg-surface px-3 py-2.5 text-sm">
        </div>
    </div>
    <div>
        <label class="block text-sm font-semibold text-ink/80">Referensi (opsional)</label>
        <select name="source_id" class="mt-1 w-full rounded-xl border border-black/10 bg-surface px-3 py-2.5 text-sm">
            <option value="">— tanpa referensi —</option>
            @foreach ($sources as $source)
                <option value="{{ $source->id }}" @selected(old('source_id', $share->source_id) == $source->id)>{{ $source->title }}</option>
            @endforeach
        </select>
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-ink/80">Syarat bagian <span class="text-red-800">*</span></label>
        <input name="condition" required maxlength="500" value="{{ old('condition', $share->condition) }}" class="mt-1 w-full rounded-xl border border-black/10 bg-surface px-3 py-2.5 text-sm">
        <p class="mt-1 text-xs text-ink/60">Contoh: "Bila pewaris tidak memiliki keturunan".</p>
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-ink/80">Catatan (opsional)</label>
        <textarea name="note" rows="3" class="mt-1 w-full rounded-xl border border-black/10 bg-surface px-3 py-2.5 text-sm">{{ old('note', $share->note) }}</textarea>
    </div>
    <div class="md:col-span-2">
        <button type="submit" class="u-btn-gold px-6 py-2.5 text-sm">{{ $share->exists ? 'Simpan Perubahan' : 'Tambah Bagian Warisan' }}</button>
    </div>
</form>
@endsection
