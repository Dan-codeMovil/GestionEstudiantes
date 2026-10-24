@extends('layouts.app')

@section('title', 'Gestión de Estudiantes')

@section('content')
    @php $esAdmin = Auth::user()->rol === 'admin'; @endphp

    <div class="card border-0">
        <div class="card-header list-header py-3 px-4 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0 soft-heading">Listado de estudiantes</h5>
                <small class="text-muted">Administra la información académica de forma sencilla</small>
            </div>

            @if($esAdmin)
                <a href="{{ route('estudiantes.create') }}" class="btn btn-primary btn-sm px-3">
                    <i class="bi bi-person-plus-fill me-1"></i> Nuevo estudiante
                </a>
            @endif
        </div>

        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Documento</th>
                            <th>Carrera</th>
                            @if($esAdmin)
                                <th class="text-center">Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($estudiantes as $estudiante)
                            <tr>
                                <td>{{ $estudiante->id }}</td>
                                <td>{{ $estudiante->nombre }}</td>
                                <td>{{ $estudiante->email }}</td>
                                <td>{{ $estudiante->documento }}</td>
                                <td>{{ $estudiante->carrera }}</td>

                                @if($esAdmin)
                                    <td class="text-center">
                                        <a href="{{ route('estudiantes.edit', $estudiante) }}" class="btn btn-warning btn-sm me-1" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <form action="{{ route('estudiantes.destroy', $estudiante) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('¿Deseas eliminar este registro?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $esAdmin ? 6 : 5 }}" class="text-center text-muted py-4">No hay estudiantes registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $estudiantes->links() }}
        </div>
    </div>
@endsection