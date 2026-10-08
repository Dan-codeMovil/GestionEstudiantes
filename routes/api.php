<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiEstudianteController;
use App\Http\Middleware\CheckRol;

/*
|--------------------------------------------------------------------------
| Rutas Públicas (No requieren Token)
|--------------------------------------------------------------------------
*/

// Inicio de Sesión vía API para generar el Token Bearer
Route::post('/login', [ApiAuthController::class, 'login']);

// Endpoint opcional para consultar la lista de estudiantes sin autenticar
Route::get('/v1/estudiantes-publico', [ApiEstudianteController::class, 'obtenerEstudiantesPublico']);


/*
|--------------------------------------------------------------------------
| Rutas Protegidas (Requieren Header Authorization: Bearer <TOKEN>)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Cerrar sesión y revocar el Token de acceso
    Route::post('/logout', [ApiAuthController::class, 'logout']);

    // Listar todos los estudiantes
    Route::get('/estudiantes', [ApiEstudianteController::class, 'index']);

    // Mostrar el detalle de un estudiante por su ID
    Route::get('/estudiantes/{estudiante}', [ApiEstudianteController::class, 'show']);

    Route::middleware([CheckRol::class . ':admin'])->group(function () {
        Route::post('/estudiantes', [ApiEstudianteController::class, 'store']);
        Route::put('/estudiantes/{estudiante}', [ApiEstudianteController::class, 'update']);
        Route::delete('/estudiantes/{estudiante}', [ApiEstudianteController::class, 'destroy']);
    });
});