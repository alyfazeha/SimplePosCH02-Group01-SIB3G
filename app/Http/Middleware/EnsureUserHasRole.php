<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Cek apakah user sudah login dan rolenya ada di dalam daftar $roles
        if (! $request->user() || ! in_array($request->user()->role, $roles)) {
            $allowed = implode(', ', $roles);
            abort(403, "Halaman ini hanya untuk peran: {$allowed}.");
        }

        return $next($request);
    }
}
