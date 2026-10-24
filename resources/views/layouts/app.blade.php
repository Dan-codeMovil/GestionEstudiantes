<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema Académico')</title>

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

    @auth
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('estudiantes.index') }}">
                <i class="bi bi-heart-fill me-2"></i>Sistema Académico
            </a>

            <div class="d-flex align-items-center">
                <div class="text-white me-3 text-end d-none d-sm-block">
                    <span class="d-block fw-semibold">{{ Auth::user()->name }}</span>
                    <span class="badge bg-primary text-capitalize">{{ Auth::user()->rol }}</span>
                </div>

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
    @endauth

    <main class="container my-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @auth
    <script>
        const tiempoInactividad = 10 * 60 * 1000; // 10 minutos
        let temporizador;

        function reiniciarTemporizador() {
            clearTimeout(temporizador);
            temporizador = setTimeout(cerrarSesionPorInactividad, tiempoInactividad);
        }

        function cerrarSesionPorInactividad() {
            alert("Tu sesión ha expirado por inactividad (10 minutos).");

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('logout') }}";

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';

            form.appendChild(csrfInput);
            document.body.appendChild(form);
            form.submit();
        }

        ['load', 'mousemove', 'keydown', 'click', 'scroll'].forEach(function (evento) {
            window.addEventListener(evento, reiniciarTemporizador);
        });
    </script>
    @endauth
</body>
</html>