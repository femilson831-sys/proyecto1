<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GuiaAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('guia_logged_in')) {
            return redirect()->route('guia.login')->with('error', 'Debes iniciar sesión primero.');
        }
        return $next($request);
    }
}