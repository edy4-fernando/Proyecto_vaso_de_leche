<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido - Sistema de Gestión Social</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { 
            background: linear-gradient(135deg, #1e3a8a 0%, #a21caf 100%); 
            font-family: 'Inter', sans-serif; 
            min-height: 100vh;
            color: white;
        }
        .navbar-custom {
            background-color: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .main-wrapper {
            padding: 40px 0;
        }
        .card-bienvenida {
            border: none;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
            background: #ffffff;
            color: #1e293b;
        }
        .badge-contador {
            background-color: #f472b6;
            color: white;
            font-size: 2.5rem;
            font-weight: 800;
            padding: 10px 25px;
            border-radius: 12px;
            display: inline-block;
            box-shadow: 0 10px 15px -3px rgba(244, 114, 182, 0.3);
        }
        .info-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            padding: 1.2rem;
            height: 100%;
        }
        .btn-salir {
            background-color: #dc2626;
            color: white;
            border: none;
            padding: 1rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            border-radius: 10px;
            transition: background 0.2s;
        }
        .btn-salir:hover {
            background-color: #b91c1c;
            color: white;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="#">
                <i class="bi bi-droplet-half text-info"></i> Cusco-Vaso de Leche
            </a>
            <div class="navbar-nav ms-auto">
                <span class="nav-link active fw-bold text-info"><i class="bi bi-person-circle me-1"></i> Perfil Activo</span>
            </div>
        </div>
    </nav>

    <div class="container main-wrapper">
        <div class="row g-4">
            
            <div class="col-lg-5">
                <div class="card card-bienvenida p-4 text-center h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="text-success display-4 mb-2"><i class="bi bi-check-circle-fill"></i></div>
                        <h4 class="text-muted text-uppercase tracking-wider small fw-bold mb-1">¡Asistencia Confirmada!</h4>
                        
                        <h2 class="fw-bold text-dark px-2 my-3" style="letter-spacing: -0.025em;">
                            ¡Hola, bienvenido(a)! <br>
                            <span class="text-primary">{{ $beneficiario->nombre }} {{ $beneficiario->apellido }}</span>
                        </h2>
                        
                        <p class="text-muted small px-3">Tu ración diaria correspondiente al Programa del Vaso de Leche ha sido cargada con éxito en el sistema municipal.</p>
                    </div>

                    <div class="my-4 p-3 bg-light rounded-3 border">
                        <span class="text-uppercase tracking-wide d-block text-muted small fw-bold mb-2">Total de Raciones Recibidas</span>
                        <div class="badge-contador mb-2">{{ $totalRaciones }}</div>
                        <small class="d-block text-secondary font-monospace">Raciones registradas en este período</small>
                    </div>

                    <div class="d-grid">
                        <a href="{{ route('asistencia.index') }}" class="btn btn-salir text-uppercase shadow-sm">
                            <i class="bi bi-box-arrow-left me-2"></i> Finalizar y Salir
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="row g-3 h-100">
                    
                    <div class="col-md-12">
                        <div class="info-card">
                            <h5 class="fw-bold text-info mb-2"><i class="bi bi-journal-bookmark-fill me-2"></i> Conócenos: Nuestra Misión</h5>
                            <p class="small text-white-50 m-0" style="line-height: 1.5;">
                                El Programa del Vaso de Leche (PVL) de la Municipalidad busca mejorar el estado nutricional de los sectores con mayor vulnerabilidad económica, proveyendo un complemento alimentario diario de alta calidad láctea y cereales nativos.
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-card" style="border-left: 4px solid #f472b6;">
                            <h6 class="fw-bold text-white mb-2"><i class="bi bi-building-fill-add me-2"></i> Obras Realidad</h6>
                            <p class="small text-white-50 m-0" style="font-size: 0.8rem;">
                                ¡Modernización de comedores en marcha! Se ha completado la refacción de 12 nuevos centros de distribución comunal equipados con sistemas de refrigeración industrial.
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-card" style="border-left: 4px solid #fbbf24;">
                            <h6 class="fw-bold text-white mb-2"><i class="bi bi-heart-pulse-fill me-2"></i> Campañas de Salud</h6>
                            <p class="small text-white-50 m-0" style="font-size: 0.8rem;">
                                Accede este fin de semana al control de descarte de anemia infantil y monitoreo de peso de manera gratuita en la plaza de armas del distrito.
                            </p>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="info-card d-flex align-items-center justify-content-between bg-dark bg-opacity-20 border-0">
                            <div>
                                <h6 class="fw-bold text-info m-0"><i class="bi bi-telephone-inbound-fill me-2"></i> ¿Tienes alguna duda o reclamo?</h6>
                                <small class="text-white-50">Comunícate directo con la Gerencia de Desarrollo Social.</small>
                            </div>
                            <div class="text-end font-monospace fw-bold text-warning" style="font-size: 1.1rem;">
                                ALÓ MUNI: #9933
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>