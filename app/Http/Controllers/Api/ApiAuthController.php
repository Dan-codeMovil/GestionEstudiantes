<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Auditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str; 

class ApiAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $key = Str::lower($request->email) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 3)) {
            return response()->json([
                'message' => 'Demasiados intentos. Reintenta en ' . RateLimiter::availableIn($key) . ' segundos.'
            ], 429);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            RateLimiter::hit($key, 60);
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        RateLimiter::clear($key);

        $token = $user->createToken('api-token')->plainTextToken;

        Auditoria::create([
            'usuario'          => $user->email,
            'ip'               => $request->ip(),
            'tipo_transaccion' => 'LOGIN_API',
            'descripcion'      => 'Inicio de sesión vía API (Token generado)',
            'fecha_hora'       => Carbon::now(),
        ]);

        return response()->json([
            'message'      => 'Login exitoso',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => [
                'id'   => $user->id,
                'name' => $user->name,
                'rol'  => $user->rol,
            ],
        ], 200);
    }

    public function logout(Request $request)
    {
        // Registrar Auditoría antes de revocar
        Auditoria::create([
            'usuario'          => $request->user()->email,
            'ip'               => $request->ip(),
            'tipo_transaccion' => 'LOGOUT_API',
            'descripcion'      => 'Cierre de sesión vía API (Token eliminado)',
            'fecha_hora'       => Carbon::now()
        ]);

        // Eliminar el token actual que realizó la petición
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Token revocado correctamente'
        ], 200);
    }
}