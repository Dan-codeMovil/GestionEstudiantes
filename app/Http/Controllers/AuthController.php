<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $key = Str::lower($request->email) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $segundos = RateLimiter::availableIn($key);
            return back()->withErrors([
                'email' => "Demasiados intentos. Intenta de nuevo en {$segundos} segundos.",
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials)) {
            RateLimiter::clear($key);
            $request->session()->regenerate();

            Auditoria::create([
                'usuario'          => Auth::user()->email,
                'ip'               => $request->ip(),
                'tipo_transaccion' => 'LOGIN',
                'descripcion'      => 'Inicio de sesión exitoso',
                'fecha_hora'       => Carbon::now(),
            ]);

            return redirect()->intended('/estudiantes');
        }

        RateLimiter::hit($key, 60);

        return back()->withErrors([
            'email' => 'El correo o la contraseña son incorrectos.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auditoria::create([
                'usuario'          => Auth::user()->email,
                'ip'               => $request->ip(),
                'tipo_transaccion' => 'LOGOUT',
                'descripcion'      => 'Cierre de sesión manual o automático',
                'fecha_hora'       => Carbon::now(),
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}