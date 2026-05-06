<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah user sudah login
        // 2. Cek apakah role user ada di dalam daftar role yang diperbolehkan
        if (!$request->user() || !in_array($request->user()->role, $roles)) {
            return response()->json([
                'status' => false,
                'message' => 'Akses ditolak! Anda tidak memiliki izin untuk fitur ini.'
            ], 403); // 403 artinya Forbidden/Terlarang
        }

        return $next($request);
    }
}
