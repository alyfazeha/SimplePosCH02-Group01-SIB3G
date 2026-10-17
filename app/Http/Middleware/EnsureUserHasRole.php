<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        // Ubah string roles (misal: "admin,manager") menjadi array
        $allowedRoles = explode(',', $roles);

        if (! $request->user() || ! in_array($request->user()->role, $allowedRoles)) {
            abort(403, "Halaman ini hanya untuk peran: {$roles}.");
        }

        return $next($request);
    }
}
