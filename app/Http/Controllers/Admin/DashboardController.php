<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Calculation;
use App\Models\Heir;
use App\Models\Share;
use App\Models\Source;
use App\Models\User;
use Illuminate\View\View;

/**
 * Dashboard admin: ringkasan isi data + pintasan kelola.
 */
class DashboardController extends Controller
{
    public function index(): View
    {
        return view('pages.admin.dashboard', [
            'heirsCount' => Heir::count(),
            'sharesCount' => Share::count(),
            'sourcesCount' => Source::count(),
            'calculationsCount' => Calculation::count(),
            'usersCount' => User::count(),
        ]);
    }
}
