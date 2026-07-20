<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrganizerMiddleware
{
    /**
     * Melindungi seluruh area /organizer/*. Superadmin sengaja TIDAK
     * otomatis diloloskan di sini — dashboard organizer memang khusus
     * untuk pengurus organisasi, superadmin punya panel sendiri (/admin).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isOrganizer() || ! $user->organization_id) {
            abort(403, 'Akses ditolak. Anda belum terdaftar sebagai pengurus organisasi.');
        }

        return $next($request);
    }
}