<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrativo - Vaso de Leche</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --brand-dark:  #0f172a;
            --brand-navy:  #1e3a5f;
            --brand-blue:  #2563eb;
            --brand-sky:   #38bdf8;
            --border:      #cbd5e1;
            --text-muted:  #64748b;
        }

        body {
            /* Degradado oficial idéntico al portal principal */
            background: linear-gradient(135deg, #1e3a8a 0%, #a21caf 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: white;
        }

        /* Barra de Navegación Superior Transparente con Blur */
        .navbar-custom {
            background-color: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .login-wrapper {
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 0;
        }

        /* Tarjeta de Login Adaptada con Bordes Suaves y Sombra Densa */
        .login-card {
            width: 100%;
            max-width: 420px;
            border: none;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
            background: white;
            overflow: hidden;
            color: #1e293b;
        }

        /* Encabezado Noche Gubernamental con línea magenta/fucsia */
        .card-header {
            background: #0f172a;
            border-bottom: 4px solid #ec4899;
            color: white;
            text-align: center;
            padding: 2rem 1.5rem;
            border-radius: 0 !important;
        }
        .card-header h4 {
            font-weight: 700;
            letter-spacing: .08em;
            color: #ffffff;
            font-size: 1.25rem;
        }
        .card-header p {
            color: var(--brand-sky);
            letter-spacing: .06em;
            font-size: 0.8rem;
            margin-top: 4px;
        }

        .card-body { background: #fff; }

        .form-label {
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: .07em;
            color: #1e3a8a; /* Azul institucional */
        }

        .form-control {
            border: 2px solid var(--border);
            border-radius: 8px;
            font-size: .9rem;
            padding: .7rem .75rem;
            transition: border-color .15s, box-shadow .15s;
            color: #0f172a;
        }
        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,.15);
        }

        /* Botón de envío con el color Magenta oficial de la app */
        .btn-primary {
            background: #6b21a8;
            border: none;
            padding: .85rem;
            border-radius: 8px;
            font-size: .85rem;
            font-weight: 700;
            letter-spacing: .06em;
            box-shadow: 0 4px 12px rgba(107,33,168,.3);
            transition: background-color .15s, transform .1s;
        }
        .btn-primary:hover {
            background-color: #581c87;
            box-shadow: 0 6px 16px rgba(107,33,168,.4);
        }
        .btn-primary:active {
            transform: scale(0.98);
        }

        /* Alerta de errores de Laravel */
        .alert-danger {
            background-color: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
            border-left: 4px solid #ef4444;
            border-radius: 8px;
            font-size: .8rem;
            font-weight: 500;
        }

        /* Franja Inferior de Logos */
        .footer-logos {
            background-color: #0f172a;
            padding: 25px 0;
            border-top: 2px solid #a21caf;
            text-align: center;
        }
        .logo-item {
            opacity: 0.7;
            font-weight: 700;
            font-size: 0.85rem;
            letter-spacing: 0.05em;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container justify-content-between">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="#">
                <i class="bi bi-droplet-half text-info"></i> Cusco-Vaso de Leche
            </a>
            <a href="{{ route('asistencia.index') }}" class="btn btn-outline-light btn-sm px-3 rounded-pill fw-medium" style="font-size: 0.8rem;">
                <i class="bi bi-arrow-left-short"></i> Volver al Marcador
            </a>
        </div>
    </nav>

    <div class="login-wrapper">
        <div class="card login-card">
            <div class="card-header">
                <h4 class="fw-bold mb-0 text-uppercase"><i class="bi bi-shield-lock-fill me-2 text-info"></i>PLATAFORMA</h4>
                <p class="small mb-0 text-uppercase fw-semibold">Acceso Administrativo Autorizado</p>
            </div>
            <div class="card-body p-4">
                
                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label text-uppercase"><i class="bi bi-envelope-fill me-1"></i> Correo Electrónico</label>
                        <input type="email" name="email" class="form-control" placeholder="admin@ejemplo.com" required autofocus autocomplete="email">
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label text-uppercase"><i class="bi bi-key-fill me-1"></i> Contraseña</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required autocomplete="current-password">
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 fw-bold text-uppercase text-white">
                        Iniciar Sesión <i class="bi bi-box-arrow-in-right ms-1"></i>
                    </button>
                </form>

                @if($errors->any())
                    <div class="alert alert-danger border-0 small text-center mt-3 p-2 shadow-sm">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $errors->first() }}
                    </div>
                @endif
                
            </div>
        </div>
    </div>

    <div class="footer-logos">
        <div class="container">
            <div class="row justify-content-center align-items-center g-3 text-white">
                <div class="col-6 col-md-2 logo-item"><i class="bi bi-shield-check me-1"></i> BARCELONA</div>
                <div class="col-6 col-md-2 logo-item"><i class="bi bi-droplet me-1"></i> Vaso de Leche</div>
                <div class="col-6 col-md-2 logo-item"><i class="bi bi-egg-fried me-1"></i> QaliWarma</div>
                <div class="col-6 col-md-2 logo-item"><i class="bi bi-building me-1"></i> MIDIS</div>
                <div class="col-6 col-md-2 logo-item"><i class="bi bi-bank me-1"></i> Municipalidad</div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>