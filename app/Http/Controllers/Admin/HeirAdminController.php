<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Heir;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * CRUD data ahli waris untuk admin (kayak pengelolaan master data sifasya).
 * Editan di sini langsung memengaruhi kartu halaman publik /ahli-waris.
 */
class HeirAdminController extends Controller
{
    private const RULES = [
        'code' => ['required', 'string', 'max:64'],
        'name_id' => ['required', 'string', 'max:120'],
        'name_ar' => ['nullable', 'string', 'max:120'],
        'slug' => ['nullable', 'string', 'max:120'],
        'gender' => ['required', 'in:m,f'],
        'category' => ['required', 'in:fard,asabah,arham,hajib'],
        'max_share_numerator' => ['nullable', 'integer', 'min:0'],
        'max_share_denominator' => ['nullable', 'integer', 'min:1', 'gt:max_share_numerator'],
        'detail' => ['required', 'string'],
        'is_active' => ['boolean'],
    ];

    public function index(): View
    {
        return view('pages.admin.heirs', [
            'heirs' => Heir::query()->orderBy('category')->orderBy('name_id')->get(),
            'categories' => ['fard' => 'Dzawil Furudh', 'asabah' => 'Ashabah', 'arham' => 'Dzawil Arham', 'hajib' => 'Hajib (penghalang)'],
        ]);
    }

    public function create(): View
    {
        return $this->form(new Heir);
    }

    public function edit(Heir $heir): View
    {
        return $this->form($heir);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(self::RULES);
        $data['is_active'] = $request->boolean('is_active', true);
        Heir::create($data);

        return redirect()->route('admin.heirs')->with('status', 'Ahli waris baru ditambahkan.');
    }

    public function update(Request $request, Heir $heir): RedirectResponse
    {
        $data = $request->validate(self::RULES);
        $data['is_active'] = $request->boolean('is_active');
        $heir->update($data);

        return redirect()->route('admin.heirs')->with('status', 'Data ahli waris diperbarui.');
    }

    public function destroy(Heir $heir): RedirectResponse
    {
        $heir->delete();

        return redirect()->route('admin.heirs')->with('status', 'Ahli waris dihapus.');
    }

    /** Form bersama untuk create/edit. */
    private function form(Heir $heir): View
    {
        return view('pages.admin.heirs-form', [
            'heir' => $heir,
            'categories' => ['fard' => 'Dzawil Furudh', 'asabah' => 'Ashabah', 'arham' => 'Dzawil Arham', 'hajib' => 'Hajib (penghalang)'],
        ]);
    }
}
