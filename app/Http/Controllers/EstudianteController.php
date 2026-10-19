<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\Auditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EstudianteController extends Controller
{
    private function registrarAuditoria($transaccion, $descripcion, $ip)
    {
        Auditoria::create([
            'usuario'          => Auth::user()->email ?? 'Sistema',
            'ip'               => $ip,
            'tipo_transaccion' => $transaccion,
            'descripcion'      => $descripcion,
            'fecha_hora'       => Carbon::now()
        ]);
    }

public function index()
{
    // Carga los estudiantes para la vista web de Blade
    $estudiantes = Estudiante::all();

    // DEBE RETORNAR LA VISTA, NO UN JSON
    return view('estudiantes.index', compact('estudiantes'));
}


    public function create()
    {
        return view('estudiantes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
        'nombre'    => 'required|string|max:100',
        'email'     => 'required|email|unique:estudiantes,email',
        'documento' => ['required', 'regex:/^\d{5,15}$/', 'unique:estudiantes,documento'],
        'carrera'   => 'required|string|max:100',
    ], [
        'documento.regex' => 'El documento debe tener entre 5 y 15 dígitos, sin espacios ni símbolos.',
    ]);

        $estudiante = Estudiante::create($validated);

        $this->registrarAuditoria('CREAR', "Creó al estudiante ID: {$estudiante->id}", $request->ip());

        return redirect()->route('estudiantes.index')->with('success', 'Estudiante registrado correctamente.');
    }

    public function edit(Estudiante $estudiante)
    {
        return view('estudiantes.edit', compact('estudiante'));
    }

    public function update(Request $request, Estudiante $estudiante)
    {
        $validated = $request->validate([
            'nombre'    => 'required|string|max:100',
            'email'     => 'required|email|unique:estudiantes,email,' . $estudiante->id,
            'documento' => ['required', 'regex:/^\d{5,15}$/', 'unique:estudiantes,documento,' . $estudiante->id],
            'carrera'   => 'required|string|max:100',
        ], [
            'documento.regex' => 'El documento debe tener entre 5 y 15 dígitos, sin espacios ni símbolos.',
        ]);

        $estudiante->update($validated);

        $this->registrarAuditoria('EDITAR', "Actualizó al estudiante ID: {$estudiante->id}", $request->ip());

        return redirect()->route('estudiantes.index')->with('success', 'Estudiante actualizado correctamente.');
    }

    public function destroy(Request $request, Estudiante $estudiante)
    {
        $id = $estudiante->id;
        $estudiante->delete();

        $this->registrarAuditoria('ELIMINAR', "Eliminó al estudiante ID: {$id}", $request->ip());

        return redirect()->route('estudiantes.index')->with('success', 'Estudiante eliminado correctamente.');
    }
}