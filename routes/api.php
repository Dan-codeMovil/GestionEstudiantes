<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiEstudianteController;

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

    // Crear un nuevo estudiante (La validación de rol 'admin' se hace en el controlador)
    Route::post('/estudiantes', [ApiEstudianteController::class, 'store']);

    // Actualizar un estudiante existente
    Route::put('/estudiantes/{estudiante}', [ApiEstudianteController::class, 'update']);

    // Eliminar un estudiante (La validación de rol 'admin' se hace en el controlador)
    Route::delete('/estudiantes/{estudiante}', [ApiEstudianteController::class, 'destroy']);
});