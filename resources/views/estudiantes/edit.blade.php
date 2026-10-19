<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Estudiante</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<style>
    :root{
        --rose:#d88fa8;
        --rose-dark:#bd6f8d;
        --rose-soft:#f8e8ee;
        --rose-pale:#fff7fa;
        --ink:#4d3b43;
        --muted:#8b7b82;
        --line:#ead9df;
    }

    * { box-sizing: border-box; }

    body{
        min-height:100vh;
        font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color:var(--ink);
        background:
            radial-gradient(circle at 10% 15%, rgba(216,143,168,.20), transparent 28%),
            radial-gradient(circle at 90% 85%, rgba(244,194,209,.22), transparent 30%),
            linear-gradient(135deg, #fff8fb 0%, #f8edf2 48%, #fdf9fb 100%);
    }

    .card{
        border:1px solid rgba(216,143,168,.16) !important;
        border-radius:22px !important;
        box-shadow:0 18px 45px rgba(119,72,91,.12) !important;
        background:rgba(255,255,255,.92);
    }

    .btn{
        border-radius:11px !important;
        font-weight:600;
        transition:all .25s ease;
    }

    .btn-primary,
    .btn-success{
        background:linear-gradient(135deg, var(--rose), var(--rose-dark)) !important;
        border:none !important;
        box-shadow:0 7px 18px rgba(189,111,141,.22);
    }

    .btn-primary:hover,
    .btn-success:hover{
        transform:translateY(-2px);
        box-shadow:0 10px 22px rgba(189,111,141,.30);
    }

    .btn-secondary{
        background:#fff !important;
        color:var(--rose-dark) !important;
        border:1px solid var(--line) !important;
    }

    .btn-warning{
        background:#f4d6a4 !important;
        border:none !important;
        color:#694d29 !important;
    }

    .btn-danger{
        background:#d9859c !important;
        border:none !important;
    }

    .form-label{
        color:#735b65 !important;
        font-weight:600 !important;
        font-size:.78rem;
        letter-spacing:.04em;
    }

    .form-control{
        border:1px solid var(--line) !important;
        border-radius:11px !important;
        background:#fffafb !important;
        color:var(--ink);
        padding:.72rem .9rem;
    }

    .form-control:focus{
        border-color:var(--rose) !important;
        box-shadow:0 0 0 .22rem rgba(216,143,168,.18) !important;
        background:#fff !important;
    }

    .alert-danger{
        border:1px solid #f0c7d2;
        border-radius:12px;
        background:#fff3f6;
        color:#875264;
    }

    .alert-success{
        border:1px solid #d9c2cc;
        border-radius:12px;
        background:#fff7fa;
        color:#70505d;
    }

    .brand-icon,
    .page-icon{
        width:62px;
        height:62px;
        margin:0 auto 13px;
        border-radius:50%;
        display:flex;
        align-items:center;
        justify-content:center;
        background:var(--rose-soft);
        color:var(--rose-dark);
        font-size:27px;
    }

    .page-title{
        color:#4d3b43;
        font-weight:700;
        letter-spacing:-.02em;
    }

    .subtitle{
        color:var(--muted);
        font-size:.88rem;
    }

    .table thead th{
        background:#fff4f7 !important;
        color:#765c66;
        border-bottom:1px solid var(--line);
        font-size:.78rem;
        text-transform:uppercase;
        letter-spacing:.04em;
    }

    .table tbody td{
        border-color:#f0e3e8;
        color:#5a4850;
    }

    .table-hover tbody tr:hover{
        background:#fff8fa;
    }

    .badge.bg-primary{
        background:var(--rose-dark) !important;
    }

    .navbar{
        background:linear-gradient(135deg,#4d3b43,#765362) !important;
        border-bottom:3px solid #e7b5c6;
    }

    .navbar-brand{
        letter-spacing:.01em;
    }

    .navbar .btn-outline-danger{
        color:#fff !important;
        border-color:rgba(255,255,255,.45) !important;
    }

    .navbar .btn-outline-danger:hover{
        background:#d9859c !important;
        border-color:#d9859c !important;
    }

    .list-header{
        background:linear-gradient(135deg,#fffafb,#fff4f7) !important;
        border-bottom:1px solid var(--line) !important;
    }

    .soft-heading{
        color:#765c66;
        font-weight:700;
    }
</style>

</head>

<body>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    
    <div class="card p-4 p-sm-5" style="width:100%; max-width:460px;">
        
        <div class="text-center mb-4"><div class="page-icon"><i class="bi bi-person-gear"></i></div><h3 class="page-title mb-1">Editar estudiante</h3><p class="subtitle mb-0">Actualiza la información del estudiante</p></div>

        <a href="{{ route('estudiantes.index') }}" class="btn btn-secondary btn-sm mb-4">
            <i class="bi bi-arrow-left me-1"></i> Volver al listado
        </a>

        <!-- Mostrar errores -->
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulario -->
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
                <inputmode="numeric">
                <input type="text" name="documento" class="form-control"
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

</body>
</html>