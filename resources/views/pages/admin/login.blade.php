<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Admin — Login — Al-Waris</title>
    <link rel="icon" href="/favicon.ico" sizes="32x32">
    <link rel="stylesheet" href="/build/assets/app-BVbjwQL_.css">
    <style>body{background:linear-gradient(160deg,#08152F,#10254B);min-height:100vh;display:grid;place-items:center;font-family:ui-sans-serif,system-ui}</style>
</head>
<body>
    <main class="w-full max-w-sm rounded-3xl bg-white/95 p-8 shadow-2xl">
        <div class="text-center">
            <p class="text-xs font-bold uppercase tracking-[0.3em] text-[#10254B]">Al&nbsp;Waris</p>
            <h1 class="mt-2 text-xl font-bold text-[#08152F]">Panel Admin</h1>
            <p class="mt-1 text-sm text-slate-500">Masuk untuk mengelola data situs.</p>
        </div>

        @if ($errors->any())
            <div class="mt-4 rounded-xl bg-red-50 p-3 text-sm text-red-700 ring-1 ring-red-200" role="alert">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.attempt') }}" class="mt-5 space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-sm font-semibold text-slate-700">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-[#D4AF57] focus:ring-2 focus:ring-[#D4AF57]/40">
            </div>
            <div>
                <label for="password" class="block text-sm font-semibold text-slate-700">Kata sandi</label>
                <input id="password" type="password" name="password" required
                       class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-[#D4AF57] focus:ring-2 focus:ring-[#D4AF57]/40">
            </div>
            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="ingat" class="h-4 w-4 rounded border-slate-300"> Ingat saya
            </label>
            <button type="submit" class="w-full rounded-xl bg-[#8E1744] px-4 py-2.5 text-sm font-bold text-white transition hover:bg-[#a01f52]">
                Masuk
            </button>
        </form>

        <p class="mt-5 text-center text-xs text-slate-400">
            © 2026 Al-Waris | Dikembangkan oleh Hengki Pranata
        </p>
    </main>
</body>
</html>
