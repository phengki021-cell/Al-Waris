<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Source;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * CRUD referensi/sumber (tabel sources): ayat, hadis, kitab, kompilasi, fatwa.
 */
class SourceAdminController extends Controller
{
    private const RULES = [
        'code' => ['required', 'string', 'max:64'],
        'title' => ['required', 'string', 'max:200'],
        'type' => ['required', 'in:quran,hadis,kitab,kompilasi,fatwa,lainnya'],
        'reference' => ['nullable', 'string', 'max:200'],
        'url' => ['nullable', 'url', 'max:255'],
        'excerpt' => ['nullable', 'string', 'max:5000'],
        'is_primary' => ['boolean'],
    ];

    public function index(): View
    {
        return view('pages.admin.sources', [
            'sources' => Source::query()->orderBy('type')->orderBy('title')->get(),
            'types' => Source::TYPES,
        ]);
    }

    public function create(): View
    {
        return $this->form(new Source);
    }

    public function edit(Source $source): View
    {
        return $this->form($source);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(self::RULES);
        $data['is_primary'] = $request->boolean('is_primary');
        Source::create($data);

        return redirect()->route('admin.sources')->with('status', 'Referensi baru ditambahkan.');
    }

    public function update(Request $request, Source $source): RedirectResponse
    {
        $data = $request->validate(self::RULES);
        $data['is_primary'] = $request->boolean('is_primary');
        $source->update($data);

        return redirect()->route('admin.sources')->with('status', 'Referensi diperbarui.');
    }

    public function destroy(Source $source): RedirectResponse
    {
        $source->delete();

        return redirect()->route('admin.sources')->with('status', 'Referensi dihapus.');
    }

    private function form(Source $source): View
    {
        return view('pages.admin.sources-form', [
            'source' => $source,
            'types' => Source::TYPES,
        ]);
    }
}
