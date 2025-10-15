<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Verificar user autenticat
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Has d\'iniciar sessió per accedir');
        }

        // Verificar rol correcte
        if (auth()->user()->rol->value !== $role) {
            abort(403, 'No tens permís per accedir a aquesta pàgina');
        }

        return $next($request);
    }
}