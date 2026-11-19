@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
<section class="mx-auto max-w-md space-y-6 pb-32">
    <div class="flex items-center gap-3">
        <a href="{{ route('home') }}" class="grid h-10 w-10 place-items-center rounded-full border border-charcoal/15 text-charcoal/70 transition hover:border-gold hover:text-[#123D32]">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-charcoal">Daftar</h1>
            <p class="text-sm text-charcoal/60">Buat akun Al-Waris baru.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('register.store') }}" class="space-y-4 rounded-2xl bg-paper p-6 shadow-smooth ring-1 ring-black/5">
        @csrf
        <div>
            <label class="mb-1 block text-sm font-medium text-charcoal">Nama</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   class="w-full rounded-xl border border-charcoal/15 bg-surface px-4 py-2.5 text-sm focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/30">
            @error('name') <p class="mt-1 text-xs text-red-700">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-charcoal">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="w-full rounded-xl border border-charcoal/15 bg-surface px-4 py-2.5 text-sm focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/30">
            @error('email') <p class="mt-1 text-xs text-red-700">{{ $message }}</p> @enderror
        </div>
        <div x-data="{ show: false }">
            <label class="mb-1 block text-sm font-medium text-charcoal">Kata sandi</label>
            <div class="relative">
                <input :type="show ? 'text' : 'password'" name="password" required
                       class="w-full rounded-xl border border-charcoal/15 bg-surface px-4 py-2.5 pr-11 text-sm focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/30">
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 grid w-11 place-items-center text-charcoal/50 transition hover:text-[#123D32]" aria-label="Tampilkan kata sandi">
                    <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                    <svg x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 1 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                </button>
            </div>
            @error('password') <p class="mt-1 text-xs text-red-700">{{ $message }}</p> @enderror
        </div>
        <div x-data="{ show: false }">
            <label class="mb-1 block text-sm font-medium text-charcoal">Konfirmasi kata sandi</label>
            <div class="relative">
                <input :type="show ? 'text' : 'password'" name="password_confirmation" required
                       class="w-full rounded-xl border border-charcoal/15 bg-surface px-4 py-2.5 pr-11 text-sm focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/30">
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 grid w-11 place-items-center text-charcoal/50 transition hover:text-[#123D32]" aria-label="Tampilkan kata sandi">
                    <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                    <svg x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 1 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                </button>
            </div>
        </div>
        <button type="submit" class="u-btn-gold w-full py-2.5 text-sm">Daftar</button>
        <p class="text-center text-sm text-charcoal/60">
            Sudah punya akun? <a href="{{ route('login') }}" class="font-semibold text-[#123D32] hover:underline">Masuk</a>
        </p>
    </form>
</section>
@endsection