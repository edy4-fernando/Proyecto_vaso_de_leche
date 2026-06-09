<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Asistencia - Vaso de Leche</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { 
            background: linear-gradient(135deg, #1e3a8a 0%, #a21caf 100%); 
            font-family: 'Inter', sans-serif; 
            min-height: 100vh;
            color: white;
        }
        
        /* Barra de Navegación Superior Estilo Portal */
        .navbar-custom {
            background-color: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            font-size: 0.9rem;
            font-weight: 500;
        }
        .navbar-nav .nav-link:hover {
            color: #38bdf8 !important;
        }

        /* Contenedor Principal */
        .main-wrapper {
            padding: 60px 0;
        }

        /* TARJETA DE CONTROL DE ASISTENCIA */
        .card { 
            border: none;
            border-radius: 16px; 
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); 
            background: #ffffff;
            color: #1e293b;
        }
        .header-accent {
            height: 4px;
            background: #3b82f6;
            border-radius: 16px 16px 0 0;
            margin: -24px -24px 20px -24px;
        }
        .text-header { color: #1e293b; letter-spacing: -0.025em; }
        
        .btn-primary { 
            background-color: #6b21a8; 
            border: none; 
            padding: 0.9rem;
            border-radius: 8px;
        }
        .btn-primary:hover { background-color: #581c87; }
        
        .form-control-lg { 
            border: 2px solid #cbd5e1; 
            font-size: 1.6rem; 
            letter-spacing: 0.12em;
            color: #1e3a8a;
            border-radius: 8px;
        }
        .form-control-lg:focus { 
            border-color: #3b82f6; 
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15); 
        }

        /* Cuadros Estadísticos del Costado */
        .stat-box {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
        }

        /* Footer de Convenios e Instituciones */
        .footer-logos {
            background-color: #0f172a;
            padding: 30px 0;
            border-top: 2px solid #a21caf;
        }
        .logo-item {
            opacity: 0.8;
            font-weight: 700;
            font-size: 0.9rem;
            letter-spacing: 0.05em;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="#">
                <i class="bi bi-droplet-half text-info"></i> Cusco-Vaso de Leche
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-2">
                    <li class="nav-item"><a class="nav-link" href="#">Servicios Nutricionales</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Noticias</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Contacto</a></li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-outline-light btn-sm px-3 rounded-pill" href="{{ route('login') }}">LOGIN ADMIN</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container main-wrapper">
        <div class="row align-items-center g-5">
            
            <div class="col-lg-6">
                <div class="card p-4">
                    <div class="header-accent"></div>
                    
                    <h3 class="text-center fw-bold text-header mb-2">CONTROL DE ASISTENCIA</h3>
                    <p class="text-center text-muted small mb-4">Programa Municipal Vaso de Leche</p>

                    {{-- Mensaje de Éxito --}}
                    @if(session('success'))
                        <div class="alert alert-success border-0 bg-success-subtle text-success text-center fw-medium small p-2">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Mensaje de Error (DNI no encontrado) - ELIMINADO EL BOTÓN DE REGISTRO PÚBLICO --}}
                    @if(session('error'))
                        <div class="alert alert-danger border-0 bg-danger-subtle text-danger text-center small mb-3 p-2">
                            {{ session('error') }}
                        </div>
                        <div class="alert alert-light text-center border text-muted small py-2" style="font-size: 0.75rem;">
                            <i class="bi bi-info-circle"></i> Si es un usuario nuevo, solicite su inscripción con el administrador de turno.
                        </div>
                    @endif

                    {{-- Errores de Validación --}}
                    @if($errors->any())
                        <div class="alert alert-warning border-0 bg-warning-subtle text-warning-emphasis text-center small p-2">
                            El DNI debe contener exactamente 8 caracteres numéricos.
                        </div>
                    @endif

                    <form action="{{ route('asistencia.buscar') }}" method="POST" class="mt-2">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label d-block text-center text-muted small fw-bold mb-3">INGRESE DNI DEL BENEFICIARIO</label>
                            <input type="text" 
                                   class="form-control form-control-lg text-center fw-bold" 
                                   id="dni" 
                                   name="dni" 
                                   maxlength="8" 
                                   placeholder="00000000" 
                                   required 
                                   autofocus>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm text-uppercase text-white">
                            Registrar Asistencia
                        </button>
                    </form>
                    
                    <div class="mt-4 pt-3 border-top text-center">
                        <p class="text-muted" style="font-size: 0.7rem;">
                            © {{ date('Y') }} Sistema de Gestión de Programas Sociales
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-3" style="line-height: 1.2;">
                    Validación Diaria: <br>
                    <span class="text-info">Control del Ciudadano</span>
                </h1>
                <p class="lead text-white-50 mb-4">
                    Plataforma digital centralizada para el marcaje de raciones y raciones del programa alimentario. El padrón oficial es gestionado de manera estricta por personal autorizado de la gerencia de desarrollo social.
                </p>

                <div class="row g-3 mb-4">
                    <div class="col-4">
                        <div class="stat-box">
                            <span class="d-block fs-3 fw-bold text-info">2.5k</span>
                            <span class="small text-white-50 text-uppercase" style="font-size: 0.65rem;">Beneficiarios</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-box" style="border-color: #ec4899;">
                            <span class="d-block fs-3 fw-bold" style="color: #f472b6 !important;">3.4k</span>
                            <span class="small text-white-50 text-uppercase" style="font-size: 0.65rem;">Población Vuln.</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-box">
                            <span class="d-block fs-3 fw-bold text-warning">11k</span>
                            <span class="small text-white-50 text-uppercase" style="font-size: 0.65rem;">Entregas Mes</span>
                        </div>
                    </div>
                </div>

                <div class="p-3 rounded-3 bg-white text-dark shadow d-flex align-items-center gap-3">
                    <div class="fs-2 text-primary"><i class="bi bi-shield-lock-fill"></i></div>
                    <div>
                        <h6 class="fw-bold mb-0">Acceso Restringido y Seguro</h6>
                        <small class="text-muted">La inscripción de nuevos ciudadanos requiere validación presencial.</small>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="footer-logos text-center">
        <div class="container">
            <p class="small text-white-50 text-uppercase tracking-widest mb-4" style="font-size: 0.7rem;">Más de 300 instituciones confían en nuestra gestión</p>
            <div class="row justify-content-center align-items-center g-4 text-white opacity-75">
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