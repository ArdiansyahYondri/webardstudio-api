<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Mengecek apakah user sudah login dan apakah dia seorang admin
        if (!$request->user() || !$request->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Area ini khusus Admin .ARDstudio.'
            ], 403);
        }

        return $next($request);
    }
}