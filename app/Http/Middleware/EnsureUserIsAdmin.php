<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Halaman panel admin hanya untuk akun dengan is_admin = true.
 * Tamu diarahkan ke login; akun biasa ditolak.
 */
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->is_admin) {
            abort(403, 'Halaman ini khusus admin.');
        }

        return $next($request);
    }
}
