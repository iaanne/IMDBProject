<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;  // TAMBAHKAN INI!

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response  // TAMBAHKAN `: Response`
    {
        // Cek user sudah login belum
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Anda harus login terlebih dahulu.');
        }

        $user = Auth::user();

        // Cek apakah user punya role yang sesuai
        if (!in_array($user->role, $roles)) {
            abort(403, 'Akses ditolak! Anda tidak punya izin untuk halaman ini.');
        }

        return $next($request);
    }
}