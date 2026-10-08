<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Estudiantes</title>

    <!-- Bootstrap 5 CDN -->
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

    <!-- Iconos Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

    <!-- BARRA SUPERIOR (NAVBAR) -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-heart-fill me-2"></i>Sistema Académico
            </a>

            <div class="d-flex align-items-center">
                <!-- Información del Usuario Autenticado -->
                <div class="text-white me-3 text-end d-none d-sm-block">
                    <span class="d-block fw-semibold">{{ Auth::user()->name }}</span>
                    <span class="badge bg-primary text-capitalize">{{ Auth::user()->rol }}</span>
                </div>

                <!-- Botón de Cerrar Sesión -->
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="container my-4">
        
        <!-- Mensajes de éxito -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card border-0">
            <div class="card-header list-header py-3 px-4 d-flex justify-content-between align-items-center">
                <div><h5 class="mb-0 soft-heading">Listado de estudiantes</h5><small class="text-muted">Administra la información académica de forma sencilla</small></div>

                <!-- Ocultar botón 'Nuevo Estudiante' si es Rol Estudiante -->
                @if(Auth::user()->rol === 'admin')
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
                                <th class="text-center">Acciones</th>
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
                                    <td class="text-center">
                                        <!-- Permitir Editar tanto a Admin como Estudiante -->
                                        <a href="{{ route('estudiantes.edit', $estudiante) }}" class="btn btn-warning btn-sm me-1" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <!-- Permitir Eliminar SOLO a Admin -->
                                        @if(Auth::user()->rol === 'admin')
                                            <form action="{{ route('estudiantes.destroy', $estudiante) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Deseas eliminar este registro?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No hay estudiantes registrados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT DE INACTIVIDAD (10 MINUTOS) -->
    <script>
        let tiempoInactividad = 10 * 60 * 1000; // 10 minutos
        let temporizador;

        function reiniciarTemporizador() {
            clearTimeout(temporizador);
            temporizador = setTimeout(cerrarSesionPorInactividad, tiempoInactividad);
        }

        function cerrarSesionPorInactividad() {
            alert("Tu sesión ha expirado por inactividad (10 minutos).");
            
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('logout') }}";
            
            let csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            
            form.appendChild(csrfInput);
            document.body.appendChild(form);
            form.submit();
        }

        window.onload = reiniciarTemporizador;
        document.onmousemove = reiniciarTemporizador;
        document.onkeypress = reiniciarTemporizador;
        document.onclick = reiniciarTemporizador;
        document.onscroll = reiniciarTemporizador;
    </script>

    <!-- Bootstrap JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>