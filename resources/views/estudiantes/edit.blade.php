@extends('layouts.app')

@section('title', 'Editar Estudiante')

@section('content')
    <div class="d-flex justify-content-center">
        <div class="card p-4 p-sm-5" style="width:100%; max-width:460px;">

            <div class="text-center mb-4">
                <div class="page-icon"><i class="bi bi-person-gear"></i></div>
                <h3 class="page-title mb-1">Editar estudiante</h3>
                <p class="subtitle mb-0">Actualiza la información del estudiante</p>
            </div>

            <a href="{{ route('estudiantes.index') }}" class="btn btn-secondary btn-sm mb-4">
                <i class="bi bi-arrow-left me-1"></i> Volver al listado
            </a>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('estudiantes.update', $estudiante->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">NOMBRE COMPLETO</label>
                    <input type="text" name="nombre" class="form-control"
                           value="{{ old('nombre', $estudiante->nombre) }}" placeholder="Ingrese el nombre">
                </div>

                <div class="mb-3">
                    <label class="form-label">DOCUMENTO</label>
                    <input type="text" name="documento" class="form-control" inputmode="numeric"
                           value="{{ old('documento', $estudiante->documento) }}" placeholder="Ingrese el documento">
                </div>

                <div class="mb-3">
                    <label class="form-label">CORREO ELECTRÓNICO</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email', $estudiante->email) }}" placeholder="Ingrese el correo">
                </div>

                <div class="mb-3">
                    <label class="form-label">CARRERA</label>
                    <input type="text" name="carrera" class="form-control"
                           value="{{ old('carrera', $estudiante->carrera) }}" placeholder="Ingrese la carrera">
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2">
                    <i class="bi bi-save2 me-1"></i> Actualizar estudiante
                </button>
            </form>
        </div>
    </div>
@endsection