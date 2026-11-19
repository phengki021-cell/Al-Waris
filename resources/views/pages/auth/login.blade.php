@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<section class="mx-auto max-w-md space-y-6 pb-32">
    <div class="flex items-center gap-3">
        <a href="{{ route('home') }}" class="grid h-10 w-10 place-items-center rounded-full border border-charcoal/15 text-charcoal/70 transition hover:border-gold hover:text-[#123D32]">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-charcoal">Masuk</h1>
            <p class="text-sm text-charcoal/60">Masuk ke akun Al-Waris Anda.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('login.attempt') }}" class="space-y-4 rounded-2xl bg-paper p-6 shadow-smooth ring-1 ring-black/5">
        @csrf
        <div>
            <label class="mb-1 block text-sm font-medium text-charcoal">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full rounded-xl border border-charcoal/15 bg-surface px-4 py-2.5 text-sm focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/30">
            @error('email') <p class="mt-1 text-xs text-red-700">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-charcoal">Kata sandi</label>
            <input type="password" name="password" required
                   class="w-full rounded-xl border border-charcoal/15 bg-surface px-4 py-2.5 text-sm focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/30">
        </div>
        <div class="flex items-center gap-2">
            <input type="checkbox" name="remember" id="remember" class="h-4 w-4 accent-[#123D32]">
            <label for="remember" class="text-sm text-charcoal/70">Ingat saya</label>
        </div>
        <button type="submit" class="u-btn-gold w-full py-2.5 text-sm">Masuk</button>
        <p class="text-center text-sm text-charcoal/60">
            Belum punya akun? <a href="{{ route('register') }}" class="font-semibold text-[#123D32] hover:underline">Daftar</a>
        </p>
    </form>
</section>
@endsection