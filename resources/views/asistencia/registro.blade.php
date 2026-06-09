<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Beneficiario - Vaso de Leche</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { 
            background: linear-gradient(135deg, #1e3a8a 0%, #a21caf 100%); 
            font-family: 'Inter', -apple-system, sans-serif; 
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

        /* TU TARJETA DE FORMULARIO ORIGINAL (Con esteroides visuales) */
        .card { 
            border: none; 
            border-radius: 16px; 
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); 
            background: #ffffff;
            color: #1e293b;
        }
        .card-header { 
            background-color: #0f172a; 
            color: white; 
            border-radius: 16px 16px 0 0 !important; 
            padding: 1.5rem; 
            text-align: center;
        }
        .form-label { 
            font-weight: 600; 
            color: #475569; 
            font-size: 0.8rem; 
            text-transform: uppercase; 
            letter-spacing: 0.025em;
        }
        .form-control { 
            border: 1px solid #cbd5e1; 
            padding: 0.75rem; 
            border-radius: 8px;
        }
        .form-control:focus { 
            border-color: #3b82f6; 
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15); 
        }
        .btn-success { 
            background-color: #6b21a8; /* Morado/magenta institucional */
            border: none; 
            padding: 14px; 
            font-weight: 600; 
            text-transform: uppercase;
            border-radius: 8px;
            letter-spacing: 0.05em;
        }
        .btn-success:hover { background-color: #581c87; }
        .text-header-main { font-size: 1.2rem; letter-spacing: 0.05em; }

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
                <i class="bi bi-droplet-half text-info"></i> PVL - GESTIÓN SOCIAL
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-2">
                    <li class="nav-item"><a class="nav-link" href="#">Padrón de Beneficiarios</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Servicios Nutricionales</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Noticias</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Compañía</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Contacto</a></li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-outline-light btn-sm px-3 rounded-pill" href="{{ route('login') }}">LOGIN ADMIN</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-danger btn-sm px-3 rounded-pill fw-bold" style="background-color: #ec4899; border: none;" href="#">PADRÓN OFICIAL</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container main-wrapper">
        <div class="row align-items-center g-5">
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header border-0">
                        <h2 class="text-header-main fw-bold mb-0 text-uppercase">FICHA DE EMPADRONAMIENTO</h2>
                        <p class="small mb-0 opacity-75">Registro de Nuevo Beneficiario</p>
                    </div>
                    <div class="card-body p-4">
                        
                        <form action="{{ route('beneficiario.guardar') }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Documento Nacional de Identidad (DNI)</label>
                                    <input type="text" name="dni" class="form-control" maxlength="8" placeholder="Ingrese 8 dígitos" required>
                                    @error('dni') <small class="text-danger small">{{ $message }}</small> @enderror
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Nombres Completos</label>
                                    <input type="text" name="nombres" class="form-control" placeholder="Ej: Juan Alberto" required>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Apellidos Completos</label>
                                    <input type="text" name="apellidos" class="form-control" placeholder="Ej: Perez Garcia" required>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Fecha de Nacimiento</label>
                                    <input type="date" name="fecha_nacimiento" class="form-control" required>
                                </div>

                                <div class="col-md-12 mb-4">
                                    <label class="form-label">Dirección Domiciliaria</label>
                                    <input type="text" name="direccion" class="form-control" placeholder="Calle, Av. o Mz. y Lote" required>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success shadow-sm">
                                    FINALIZAR E INSCRIBIR
                                </button>
                                <a href="{{ route('asistencia.index') }}" class="btn btn-link text-muted small text-decoration-none text-center mt-2">
                                    Cancelar y volver
                                </a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-3" style="line-height: 1.2;">
                    Registro de Beneficiarios: <br>
                    <span class="text-info">Ingresa los Datos de la Persona</span>
                </h1>
                <p class="lead text-white-50 mb-4">
                    Formulario de empadronamiento digital para el acceso a los programas alimentarios distritales. Verifique su información antes de enviar.
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
                            <span class="d-block fs-3 fw-bold text-danger" style="color: #f472b6 !important;">3.4k</span>
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
                    <div class="fs-2 text-primary"><i class="bi bi-graph-up-arrow"></i></div>
                    <div>
                        <h6 class="fw-bold mb-0">Cobertura Integral del Programa</h6>
                        <small class="text-muted">Impacto y métricas de distribución actualizadas en tiempo real.</small>
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

    <footer class="py-3 text-center" style="background-color: #0b0f19;">
        <p class="text-muted m-0" style="font-size: 0.75rem;">
            SISTEMA DE GESTIÓN SOCIAL V1.0 &copy; {{ date('Y') }}
        </p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>