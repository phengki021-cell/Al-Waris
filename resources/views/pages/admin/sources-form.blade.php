@extends('layouts.app')

@section('title', $source->exists ? 'Admin — Edit Referensi' : 'Admin — Tambah Referensi')

@section('content')
<div class="flex items-center justify-between gap-3">
    <h1 class="u-display text-2xl text-ink md:text-3xl">{{ $source->exists ? 'Edit Referensi' : 'Tambah Referensi' }}</h1>
    <a href="{{ route('admin.sources') }}" class="rounded-xl border border-gold/40 px-4 py-2 text-sm font-bold text-gold-deep transition hover:bg-gold-soft">&larr; Kembali</a>
</div>

@if ($errors->any())
    <div class="mt-4 rounded-xl bg-red-50 p-3 text-sm text-red-700 ring-1 ring-red-200">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<form method="POST" action="{{ $source->exists ? route('admin.sources.update', $source) : route('admin.sources.store') }}" class="mt-5 grid gap-4 md:grid-cols-2">
    @csrf
    @if ($source->exists)
        @method('PUT')
    @endif

    <div>
        <label class="block text-sm font-semibold text-ink/80">Kode (unik) <span class="text-red-600">*</span></label>
        <input name="code" required value="{{ old('code', $source->code) }}" class="mt-1 w-full rounded-xl border border-black/10 bg-surface px-3 py-2.5 text-sm">
    </div>
    <div>
        <label class="block text-sm font-semibold text-ink/80">Judul <span class="text-red-600">*</span></label>
        <input name="title" required value="{{ old('title', $source->title) }}" class="mt-1 w-full rounded-xl border border-black/10 bg-surface px-3 py-2.5 text-sm">
    </div>
    <div>
        <label class="block text-sm font-semibold text-ink/80">Jenis</label>
        <select name="type" class="mt-1 w-full rounded-xl border border-black/10 bg-surface px-3 py-2.5 text-sm">
            @foreach ($types as $type)
                <option value="{{ $type }}" @selected(old('type', $source->type) === $type)>{{ ucfirst($type) }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-semibold text-ink/80">Referensi (mis. "QS. An-Nisaa: 12")</label>
        <input name="reference" value="{{ old('reference', $source->reference) }}" class="mt-1 w-full rounded-xl border border-black/10 bg-surface px-3 py-2.5 text-sm">
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-ink/80">URL (opsional)</label>
        <input name="url" type="url" value="{{ old('url', $source->url) }}" class="mt-1 w-full rounded-xl border border-black/10 bg-surface px-3 py-2.5 text-sm">
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-ink/80">Kutipan / isi (opsional)</label>
        <textarea name="excerpt" rows="4" class="mt-1 w-full rounded-xl border border-black/10 bg-surface px-3 py-2.5 text-sm">{{ old('excerpt', $source->excerpt) }}</textarea>
    </div>
    <div class="flex items-end">
        <label class="flex items-center gap-2 text-sm font-semibold text-ink/80">
            <input type="hidden" name="is_primary" value="0">
            <input type="checkbox" name="is_primary" value="1" @checked(old('is_primary', $source->is_primary)) class="h-4 w-4"> Referensi utama
        </label>
    </div>
    <div class="md:col-span-2">
        <button type="submit" class="u-btn-gold px-6 py-2.5 text-sm">{{ $source->exists ? 'Simpan Perubahan' : 'Tambah Referensi' }}</button>
    </div>
</form>
@endsection
