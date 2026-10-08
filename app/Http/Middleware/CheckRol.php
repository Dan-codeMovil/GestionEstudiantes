<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRol
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        if (in_array($user->rol, $roles)) {
            return $next($request);
        }

        abort(403, 'No tienes permisos para realizar esta acción.');
    }
}