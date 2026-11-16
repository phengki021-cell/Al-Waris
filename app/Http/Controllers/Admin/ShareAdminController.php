<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Heir;
use App\Models\Share;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * CRUD bagian warisan (tabel shares) — jatah pecahan tiap ahli waris
 * beserta syaratnya; dipakai halaman publik /bagian-warisan.
 */
class ShareAdminController extends Controller
{
    private const RULES = [
        'heir_id' => ['required', 'exists:heirs,id'],
        'numerator' => ['required', 'integer', 'min:0', 'max:9999'],
        'denominator' => ['required', 'integer', 'min:1', 'gt:numerator'],
        'condition' => ['required', 'string', 'max:500'],
        'note' => ['nullable', 'string', 'max:2000'],
        'source_id' => ['nullable', 'exists:sources,id'],
    ];

    public function index(): View
    {
        return view('pages.admin.shares', [
            'shares' => Share::query()->with(['heir', 'source'])->orderBy('heir_id')->orderBy('denominator')->get(),
            'heirs' => Heir::query()->orderBy('name_id')->get()->mapWithKeys(fn (Heir $h) => [$h->id => $h->name_id]),
            'sources' => \App\Models\Source::query()->orderBy('title')->get()->mapWithKeys(fn ($s) => [$s->id => $s->title]),
        ]);
    }

    public function create(): View
    {
        return $this->form(new Share);
    }

    public function edit(Share $share): View
    {
        return $this->form($share);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(self::RULES);
        $data['condition_hash'] = md5($data['condition']);

        try {
            Share::create($data);
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->withInput()->withErrors(['condition' => 'Kombinasi ahli waris + pecahan + syarat itu sudah ada (duplikat).']);
        }

        return redirect()->route('admin.shares')->with('status', 'Bagian warisan baru ditambahkan.');
    }

    public function update(Request $request, Share $share): RedirectResponse
    {
        $data = $request->validate(self::RULES);
        $data['condition_hash'] = md5($data['condition']);

        try {
            $share->update($data);
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->withInput()->withErrors(['condition' => 'Kombinasi ahli waris + pecahan + syarat itu sudah ada (duplikat).']);
        }

        return redirect()->route('admin.shares')->with('status', 'Bagian warisan diperbarui.');
    }

    public function destroy(Share $share): RedirectResponse
    {
        $share->delete();

        return redirect()->route('admin.shares')->with('status', 'Bagian warisan dihapus.');
    }

    private function form(Share $share): View
    {
        return view('pages.admin.shares-form', [
            'share' => $share,
            'heirs' => Heir::query()->orderBy('name_id')->get(),
            'sources' => \App\Models\Source::query()->orderBy('title')->get(),
        ]);
    }
}
