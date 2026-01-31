<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            abort(403, 'Accès non autorisé');
        }

        // Allow multiple roles separated by '|' or ',' (e.g. 'admin|employe')
        $allowed = preg_split('/[|,]/', $role);
        if (!in_array(auth()->user()->role, $allowed, true)) {
            abort(403, 'Accès non autorisé');
        }

        return $next($request);
    }
}
