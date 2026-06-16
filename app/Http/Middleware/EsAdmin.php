<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EsAdmin
{
    public function handle(Request $request, Closure $next, string $rol = 'maestro')
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if ($rol === 'maestro' && Auth::user()->rol !== 'maestro') {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Acceso restringido. Solo el maestro puede acceder al Dashboard.');
        }

        return $next($request);
    }
}