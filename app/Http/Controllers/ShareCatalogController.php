<?php

namespace App\Http\Controllers;

use App\Models\Share;
use Illuminate\View\View;

class ShareCatalogController extends Controller
{
    public function __invoke(): View
    {
        $shares = Share::with(['heir', 'source'])
            ->orderBy('heir_id')
            ->orderBy('denominator')
            ->get();

        return view('pages.shares', ['shares' => $shares]);
    }
}
