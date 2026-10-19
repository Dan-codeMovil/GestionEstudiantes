<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema Académico</title>
    
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Iconos Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card-login {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            border: none;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 420px;
        }
        .btn-custom {
            background-color: #2563eb;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            background-color: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.4);
        }
        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 0.25rem rgba(37, 99, 235, 0.25);
        }
        .brand-icon {
            background: #eff6ff;
            color: #2563eb;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 12px;
        }
    </style>
</head>
<body>

    <div class="card card-login p-4 p-sm-5">
        <div class="text-center mb-4">
            <div class="brand-icon">
                <i class="bi bi-person-badge"></i>
            </div>
            <h3 class="fw-bold text-dark mb-1">Bienvenido</h3>
            <p class="text-muted small">Ingresa tus datos para acceder al sistema</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center py-2" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <div class="small">{{ $errors->first() }}</div>
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label text-secondary fw-semibold small">EMAIL</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-person"></i></span>
                    <input type="email" class="form-control bg-light border-start-0" id="email" name="email" value="{{ old('email') }}" placeholder="Ej: admin@example.com" required autofocus>
                </div>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label text-secondary fw-semibold small">CONTRASEÑA</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-lock"></i></span>
                    <input type="password" class="form-control bg-light border-start-0" id="password" name="password" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-custom w-100 mb-2">
                Iniciar Sesión
            </button>
        </form>

        <div class="text-center mt-3">
            <span class="text-muted small">Sistema de Gestión Académica &copy; {{ date('Y') }}</span>
        </div>
    </div>

</body>
</html>