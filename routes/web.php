<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\CheckRol;

// Rutas Públicas (Login)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas Protegidas por Autenticación
Route::middleware(['auth'])->group(function () {

    // Rutas accesibles para Estudiantes y Admin (Listar y Editar)
    Route::middleware([CheckRol::class . ':admin,estudiante'])->group(function () {
        Route::get('/estudiantes', [EstudianteController::class, 'index'])->name('estudiantes.index');
        Route::get('/estudiantes/{estudiante}/edit', [EstudianteController::class, 'edit'])->name('estudiantes.edit');
        Route::put('/estudiantes/{estudiante}', [EstudianteController::class, 'update'])->name('estudiantes.update');
    });

    // Rutas EXCLUSIVAS de Admin (Crear y Eliminar)
    Route::middleware([CheckRol::class . ':admin'])->group(function () {
        Route::get('/estudiantes/create', [EstudianteController::class, 'create'])->name('estudiantes.create');
        Route::post('/estudiantes', [EstudianteController::class, 'store'])->name('estudiantes.store');
        Route::delete('/estudiantes/{estudiante}', [EstudianteController::class, 'destroy'])->name('estudiantes.destroy');
    });
});

Route::get('/', function () {
    return redirect()->route('estudiantes.index');
});