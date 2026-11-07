<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Verificar si el usuario está autenticado
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        // Verificar si tiene el rol correcto
        $userRole = auth()->user()->rol ?? 'cliente';
        
        if ($userRole !== $role) {
            abort(403, 'No tienes acceso a esta sección. Rol requerido: ' . $role . ', tu rol: ' . $userRole);
        }
        
        return $next($request);
    }
}