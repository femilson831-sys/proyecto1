<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!auth()->check()) {
            abort(403);
        }

        if (auth()->user()->rol !== $role) {
            abort(403, 'No autorizado');
        }

        return $next($request);
    }
}