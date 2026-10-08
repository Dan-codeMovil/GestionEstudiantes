<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Auditoria;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'name'     => 'required|string',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Auditoría de inicio de sesión exitoso
            Auditoria::create([
                'usuario'          => Auth::user()->name,
                'ip'               => $request->ip(),
                'tipo_transaccion' => 'LOGIN',
                'descripcion'      => 'Inicio de sesión exitoso',
                'fecha_hora'       => Carbon::now()
            ]);

            return redirect()->intended('/estudiantes');
        }

        return back()->withErrors(['name' => 'El usuario o la contraseña son incorrectos.']);
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auditoria::create([
                'usuario'          => Auth::user()->name,
                'ip'               => $request->ip(),
                'tipo_transaccion' => 'LOGOUT',
                'descripcion'      => 'Cierre de sesión manual o automático',
                'fecha_hora'       => Carbon::now()
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}