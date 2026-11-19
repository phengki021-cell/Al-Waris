@extends('layouts.app')

@section('title', __('messages.settings'))

@section('content')
<section class="mx-auto max-w-3xl space-y-6 pb-32">
    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ url()->previous() === url()->current() ? route('home') : url()->previous() }}"
           class="grid h-10 w-10 place-items-center rounded-full border border-charcoal/15 text-charcoal/70 transition hover:border-gold hover:text-[#123D32]">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-charcoal">{{ __('messages.settings') }}</h1>
            <p class="text-sm text-charcoal/60">{{ __('messages.settings_desc') }}</p>
        </div>
    </div>

    {{-- Bahasa --}}
    <div class="rounded-2xl bg-paper p-5 shadow-smooth ring-1 ring-black/5">
        <div class="flex items-center gap-3">
            <span class="grid h-10 w-10 place-items-center rounded-xl bg-emerald-100 text-emerald-900 ring-1 ring-emerald-200">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z"/><path stroke-linecap="round" d="M3.6 9h16.8M3.6 15h16.8M12 3c-2.5 3-2.5 15 0 18M12 3c2.5 3 2.5 15 0 18"/></svg>
            </span>
            <div>
                <h2 class="font-semibold text-charcoal">{{ __('messages.language') }}</h2>
                <p class="text-xs text-charcoal/60">{{ __('messages.language_desc') }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('settings.locale') }}" class="mt-4">
            @csrf
            <input type="text" id="langSearch" placeholder="{{ __('messages.language_search') }}"
                   class="mb-3 w-full rounded-xl border border-charcoal/15 bg-surface px-4 py-2.5 text-sm focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/30">
            <div class="max-h-64 overflow-y-auto rounded-xl border border-charcoal/10">
                @php
                    $langs = [
                        ['code'=>'id','name'=>'Bahasa Indonesia','available'=>true],
                        ['code'=>'en','name'=>'English','available'=>true],
                        ['code'=>'ar','name'=>'العربية','available'=>true],
                        ['code'=>'ms','name'=>'Melayu','available'=>false],
                        ['code'=>'ur','name'=>'اردو','available'=>false],
                        ['code'=>'tr','name'=>'Türkçe','available'=>false],
                        ['code'=>'fr','name'=>'Français','available'=>false],
                        ['code'=>'de','name'=>'Deutsch','available'=>false],
                        ['code'=>'es','name'=>'Español','available'=>false],
                        ['code'=>'bn','name'=>'বাংলা','available'=>false],
                        ['code'=>'hi','name'=>'हिन्दी','available'=>false],
                    ];
                    $current = app()->getLocale();
                @endphp
                @foreach ($langs as $l)
                    <label class="lang-item flex cursor-pointer items-center justify-between px-4 py-3 text-sm transition hover:bg-gold-soft/40 {{ $l['available'] ? '' : 'opacity-50' }}"
                           data-name="{{ strtolower($l['name']) }}">
                        <span class="flex items-center gap-3">
                            <input type="radio" name="locale" value="{{ $l['code'] }}"
                                   {{ $current === $l['code'] ? 'checked' : '' }}
                                   {{ $l['available'] ? '' : 'disabled' }}
                                   class="h-4 w-4 accent-[#123D32]">
                            <span class="{{ $l['code']==='ar' ? 'font-quran' : '' }}">{{ $l['name'] }}</span>
                        </span>
                        @if (! $l['available'])
                            <span class="text-xs text-charcoal/50">{{ __('messages.coming_soon') }}</span>
                        @elseif ($current === $l['code'])
                            <span class="text-xs font-semibold text-gold-deep">✓</span>
                        @endif
                    </label>
                @endforeach
            </div>
            <button type="submit" class="u-btn-gold mt-3 w-full py-2.5 text-sm">{{ __('messages.save') }}</button>
        </form>
    </div>

    {{-- Akun --}}
    <div class="rounded-2xl bg-paper p-5 shadow-smooth ring-1 ring-black/5">
        <div class="flex items-center gap-3">
            <span class="grid h-10 w-10 place-items-center rounded-xl bg-emerald-100 text-emerald-900 ring-1 ring-emerald-200">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0"/></svg>
            </span>
            <div>
                <h2 class="font-semibold text-charcoal">{{ __('messages.account') }}</h2>
                <p class="text-xs text-charcoal/60">{{ __('messages.account_desc') }}</p>
            </div>
        </div>
        <div class="mt-4 space-y-3">
            @auth
                <div class="flex items-center gap-4 rounded-xl bg-surface px-4 py-3">
                    <div class="relative">
                        @if (auth()->user()->avatar)
                            <img src="/{{ auth()->user()->avatar }}" alt="Foto profil" class="h-14 w-14 rounded-full object-cover ring-2 ring-gold/40">
                        @else
                            <div class="grid h-14 w-14 place-items-center rounded-full bg-emerald-100 text-emerald-900 ring-2 ring-gold/40">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-7 w-7"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0"/></svg>
                            </div>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-semibold text-charcoal">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-charcoal/60">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data" class="flex items-center gap-2">
                    @csrf
                    <input type="file" name="avatar" accept="image/*" required class="block w-full text-xs text-charcoal/70 file:mr-3 file:rounded-lg file:border-0 file:bg-[#123D32] file:px-3 file:py-2 file:text-xs file:font-semibold file:text-ivory">
                    <button type="submit" class="shrink-0 rounded-xl bg-gold px-4 py-2 text-xs font-semibold text-[#09271F] transition hover:bg-gold/90">{{ __('messages.save') }}</button>
                </form>
                @error('avatar') <p class="text-xs text-red-700">{{ $message }}</p> @enderror
                @if (session('status')) <p class="text-xs text-emerald-700">{{ session('status') }}</p> @endif
                <form method="POST" action="{{ route('logout') ?? '#' }}">
                    @csrf
                    <button type="submit" class="w-full rounded-xl border border-red-200 px-4 py-3 text-sm font-medium text-red-900 transition hover:bg-red-50">{{ __('messages.logout') }}</button>
                </form>
            @else
                <p class="rounded-xl bg-surface px-4 py-3 text-sm text-charcoal/70">{{ __('messages.not_logged_in') }}</p>
                <a href="{{ route('login') ?? '#' }}" class="block rounded-xl bg-[#123D32] px-4 py-3 text-center text-sm font-semibold text-ivory transition hover:bg-[#1B4A3C]">{{ __('messages.login') }}</a>
                <a href="{{ route('register') ?? '#' }}" class="block rounded-xl border border-charcoal/15 px-4 py-3 text-center text-sm font-medium text-charcoal transition hover:bg-gold-soft/40">{{ __('messages.register') }}</a>
                <p class="text-center text-xs text-charcoal/50">{{ __('messages.guest') }}</p>
            @endauth
        </div>
    </div>

    {{-- Tampilan --}}
    <div class="rounded-2xl bg-paper p-5 shadow-smooth ring-1 ring-black/5">
        <div class="flex items-center gap-3">
            <span class="grid h-10 w-10 place-items-center rounded-xl bg-emerald-100 text-emerald-900 ring-1 ring-emerald-200">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/></svg>
            </span>
            <div>
                <h2 class="font-semibold text-charcoal">{{ __('messages.appearance') }}</h2>
                <p class="text-xs text-charcoal/60">{{ __('messages.appearance_desc') }}</p>
            </div>
        </div>
        <div class="mt-4 space-y-4" x-data="settingsAppearance()">
            <div>
                <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-charcoal/60">{{ __('messages.theme') }}</p>
                <div class="grid grid-cols-3 gap-2">
                    <button type="button" @click="setTheme('light')" :class="theme==='light' ? 'bg-[#123D32] text-ivory ring-[#123D32]' : 'bg-surface text-charcoal ring-charcoal/15'" class="rounded-xl px-3 py-2 text-sm font-medium ring-1 transition">{{ __('messages.theme_light') }}</button>
                    <button type="button" @click="setTheme('dark')" :class="theme==='dark' ? 'bg-[#123D32] text-ivory ring-[#123D32]' : 'bg-surface text-charcoal ring-charcoal/15'" class="rounded-xl px-3 py-2 text-sm font-medium ring-1 transition">{{ __('messages.theme_dark') }}</button>
                    <button type="button" @click="setTheme('system')" :class="theme==='system' ? 'bg-[#123D32] text-ivory ring-[#123D32]' : 'bg-surface text-charcoal ring-charcoal/15'" class="rounded-xl px-3 py-2 text-sm font-medium ring-1 transition">{{ __('messages.theme_system') }}</button>
                </div>
            </div>
            <div>
                <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-charcoal/60">{{ __('messages.font_size') }}</p>
                <div class="grid grid-cols-3 gap-2">
                    <button type="button" @click="setFont('small')" :class="font==='small' ? 'bg-[#123D32] text-ivory' : 'bg-surface text-charcoal'" class="rounded-xl px-3 py-2 text-sm ring-1 ring-charcoal/15 transition">{{ __('messages.font_small') }}</button>
                    <button type="button" @click="setFont('normal')" :class="font==='normal' ? 'bg-[#123D32] text-ivory' : 'bg-surface text-charcoal'" class="rounded-xl px-3 py-2 text-sm ring-1 ring-charcoal/15 transition">{{ __('messages.font_normal') }}</button>
                    <button type="button" @click="setFont('large')" :class="font==='large' ? 'bg-[#123D32] text-ivory' : 'bg-surface text-charcoal'" class="rounded-xl px-3 py-2 text-sm ring-1 ring-charcoal/15 transition">{{ __('messages.font_large') }}</button>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-charcoal">{{ __('messages.animation') }}</span>
                <button type="button" @click="toggleAnim()" :class="anim ? 'bg-[#123D32]' : 'bg-charcoal/30'" class="relative h-6 w-11 rounded-full transition">
                    <span :class="anim ? 'translate-x-5' : 'translate-x-0.5'" class="absolute top-0.5 h-5 w-5 rounded-full bg-white shadow transition"></span>
                </button>
            </div>
        </div>
    </div>

    {{-- Notifikasi --}}
    <div class="rounded-2xl bg-paper p-5 shadow-smooth ring-1 ring-black/5">
        <div class="flex items-center gap-3">
            <span class="grid h-10 w-10 place-items-center rounded-xl bg-emerald-100 text-emerald-900 ring-1 ring-emerald-200">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022 23.848 23.848 0 0 0 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/></svg>
            </span>
            <div>
                <h2 class="font-semibold text-charcoal">{{ __('messages.notifications') }}</h2>
                <p class="text-xs text-charcoal/60">{{ __('messages.notifications_desc') }}</p>
            </div>
        </div>
        <div class="mt-4" x-data="{ supported: ('Notification' in window), enabled: false }" x-init="enabled = (Notification.permission === 'granted')">
            <template x-if="!supported">
                <p class="rounded-xl bg-surface px-4 py-3 text-sm text-charcoal/70">{{ __('messages.notifications_not_supported') }}</p>
            </template>
            <template x-if="supported">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-charcoal">Izinkan notifikasi</span>
                    <button type="button" @click="Notification.requestPermission().then(p => enabled = (p==='granted'))" :class="enabled ? 'bg-[#123D32]' : 'bg-charcoal/30'" class="relative h-6 w-11 rounded-full transition">
                        <span :class="enabled ? 'translate-x-5' : 'translate-x-0.5'" class="absolute top-0.5 h-5 w-5 rounded-full bg-white shadow transition"></span>
                    </button>
                </div>
            </template>
        </div>
    </div>

    {{-- Privasi --}}
    <div class="rounded-2xl bg-paper p-5 shadow-smooth ring-1 ring-black/5">
        <div class="flex items-center gap-3">
            <span class="grid h-10 w-10 place-items-center rounded-xl bg-emerald-100 text-emerald-900 ring-1 ring-emerald-200">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
            </span>
            <div>
                <h2 class="font-semibold text-charcoal">{{ __('messages.privacy') }}</h2>
                <p class="text-xs text-charcoal/60">{{ __('messages.privacy_desc') }}</p>
            </div>
        </div>
        <div class="mt-4 space-y-2">
            <a href="{{ route('privacy') }}" class="block rounded-xl border border-charcoal/10 px-4 py-3 text-sm font-medium text-charcoal transition hover:bg-gold-soft/40">{{ __('messages.privacy_policy') }}</a>
            <a href="{{ route('about') }}" class="block rounded-xl border border-charcoal/10 px-4 py-3 text-sm font-medium text-charcoal transition hover:bg-gold-soft/40">{{ __('messages.terms') }}</a>
            <form method="POST" action="{{ route('history') }}" onsubmit="return confirm('{{ __('messages.delete_history_confirm') }}');" class="hidden"></form>
        </div>
    </div>

    {{-- Bantuan --}}
    <div class="rounded-2xl bg-paper p-5 shadow-smooth ring-1 ring-black/5">
        <div class="flex items-center gap-3">
            <span class="grid h-10 w-10 place-items-center rounded-xl bg-emerald-100 text-emerald-900 ring-1 ring-emerald-200">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z"/></svg>
            </span>
            <div>
                <h2 class="font-semibold text-charcoal">{{ __('messages.help') }}</h2>
                <p class="text-xs text-charcoal/60">{{ __('messages.help_desc') }}</p>
            </div>
        </div>
        <div class="mt-4 space-y-2">
            <a href="{{ route('how-to') }}" class="block rounded-xl border border-charcoal/10 px-4 py-3 text-sm font-medium text-charcoal transition hover:bg-gold-soft/40">{{ __('messages.guide') }}</a>
            <a href="{{ route('about') }}" class="block rounded-xl border border-charcoal/10 px-4 py-3 text-sm font-medium text-charcoal transition hover:bg-gold-soft/40">{{ __('messages.about') }}</a>
            <div class="rounded-xl bg-surface px-4 py-3 text-sm text-charcoal/70">
                <p>{{ __('messages.version') }}: 1.0.0</p>
                <p>{{ __('messages.developer') }}: Hengki Pranata</p>
            </div>
        </div>
    </div>
</section>

<script>
function settingsAppearance() {
    return {
        theme: localStorage.getItem('alw-theme') || 'system',
        font: localStorage.getItem('alw-font') || 'normal',
        anim: localStorage.getItem('alw-anim') !== 'off',
        setTheme(t) {
            this.theme = t;
            localStorage.setItem('alw-theme', t);
            window.dispatchEvent(new CustomEvent('theme-change', { detail: t }));
        },
        setFont(s) {
            this.font = s;
            localStorage.setItem('alw-font', s);
            document.documentElement.style.fontSize = s === 'small' ? '14px' : (s === 'large' ? '18px' : '16px');
        },
        toggleAnim() {
            this.anim = !this.anim;
            localStorage.setItem('alw-anim', this.anim ? 'on' : 'off');
            document.documentElement.classList.toggle('alw-no-anim', !this.anim);
        },
        init() {
            this.setFont(this.font);
            if (!this.anim) document.documentElement.classList.add('alw-no-anim');
        }
    };
}
</script>

<script>
document.getElementById('langSearch')?.addEventListener('input', function (e) {
    const q = e.target.value.toLowerCase();
    document.querySelectorAll('.lang-item').forEach(function (el) {
        el.style.display = (el.dataset.name || '').includes(q) ? '' : 'none';
    });
});
</script>
@endsection