<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

/**
 * Profil admin: ganti nama & kata sandi sendiri dari dalam panel.
 */
class ProfileController extends Controller
{
    public function show(): View
    {
        return view('pages.admin.profile', ['user' => auth()->user()]);
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $data = $request->validate([
            'kata_sandi_lama' => ['required', 'string'],
            'kata_sandi_baru' => ['required', 'string', 'min:8', 'different:kata_sandi_lama'],
            'kata_sandi_baru_konfirmasi' => ['required', 'string', 'same:kata_sandi_baru'],
        ], [], [
            'kata_sandi_lama' => 'Kata sandi lama',
            'kata_sandi_baru' => 'Kata sandi baru',
            'kata_sandi_baru_konfirmasi' => 'Konfirmasi kata sandi',
        ]);

        if (! Hash::check($data['kata_sandi_lama'], $user->password)) {
            return back()->withErrors(['kata_sandi_lama' => 'Kata sandi lama tidak sesuai.']);
        }

        $user->update(['password' => $data['kata_sandi_baru']]);

        return back()->with('status', 'Kata sandi berhasil diganti.');
    }

    public function updateName(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $data = $request->validate(['nama' => ['required', 'string', 'max:120']]);
        $user->update(['name' => $data['nama']]);

        return back()->with('status', 'Nama admin berhasil diperbarui.');
    }
}
