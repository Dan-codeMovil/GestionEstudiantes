<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Estudiante;
use App\Models\Auditoria;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ApiEstudianteController extends Controller
{
    private function registrarAuditoria($usuario, $transaccion, $descripcion, $ip)
    {
        Auditoria::create([
            'usuario'          => $usuario,
            'ip'               => $ip,
            'tipo_transaccion' => $transaccion,
            'descripcion'      => $descripcion,
            'fecha_hora'       => Carbon::now()
        ]);
    }

    public function index()
    {
        return response()->json(Estudiante::all(), 200);
    }

    public function store(Request $request)
    {
        // Verificar si es Admin
        if ($request->user()->rol !== 'admin') {
            return response()->json(['message' => 'No autorizado. Se requiere rol admin.'], 403);
        }

        $validated = $request->validate([
            'nombre'    => 'required|string|max:100',
            'email'     => 'required|email|unique:estudiantes,email',
            'documento' => 'required|numeric|unique:estudiantes,documento',
            'carrera'   => 'required|string|max:100',
        ]);

        $estudiante = Estudiante::create($validated);

        $this->registrarAuditoria(
            $request->user()->name,
            'CREAR_API',
            "Creó estudiante ID: {$estudiante->id} vía API",
            $request->ip()
        );

        return response()->json([
            'message' => 'Estudiante creado exitosamente',
            'data'    => $estudiante
        ], 201);
    }

    public function show(Estudiante $estudiante)
    {
        return response()->json($estudiante, 200);
    }

    public function update(Request $request, Estudiante $estudiante)
    {
        $validated = $request->validate([
            'nombre'    => 'required|string|max:100',
            'email'     => 'required|email|unique:estudiantes,email,' . $estudiante->id,
            'documento' => 'required|numeric|unique:estudiantes,documento,' . $estudiante->id,
            'carrera'   => 'required|string|max:100',
        ]);

        $estudiante->update($validated);

        $this->registrarAuditoria(
            $request->user()->name,
            'EDITAR_API',
            "Actualizó estudiante ID: {$estudiante->id} vía API",
            $request->ip()
        );

        return response()->json([
            'message' => 'Estudiante actualizado correctamente',
            'data'    => $estudiante
        ], 200);
    }

    public function destroy(Request $request, Estudiante $estudiante)
    {
        if ($request->user()->rol !== 'admin') {
            return response()->json(['message' => 'No autorizado. Se requiere rol admin.'], 403);
        }

        $id = $estudiante->id;
        $estudiante->delete();

        $this->registrarAuditoria(
            $request->user()->name,
            'ELIMINAR_API',
            "Eliminó estudiante ID: {$id} vía API",
            $request->ip()
        );

        return response()->json(['message' => 'Estudiante eliminado correctamente'], 200);
    }
}