<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="30">
    <title>Panel Administrativo - Vaso de Leche</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --brand-dark:   #0f172a;
            --brand-navy:   #1e3a8a;
            --brand-purple: #a21caf;
            --brand-blue:   #2563eb;
            --brand-sky:    #38bdf8;
            --brand-fuchsia:#ec4899;
            --border:       #cbd5e1;
            --text-main:    #1e293b;
            --text-muted:   #64748b;
        }

        body {
            background-color: #f8fafc;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text-main);
            min-height: 100vh;
        }

        .navbar-custom {
            background: linear-gradient(135deg, var(--brand-dark) 0%, #1e3a5f 100%);
            border-bottom: 4px solid var(--brand-fuchsia);
            box-shadow: 0 4px 20px rgba(15,23,42,.25);
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.1rem;
            color: #ffffff !important;
        }

        .admin-layout { padding: 40px 0; }

        .nav-module-link {
            font-weight: 600;
            font-size: 0.85rem;
            letter-spacing: 0.03em;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            transition: all 0.2s ease;
            text-transform: uppercase;
        }
        .nav-module-link.active {
            background-color: rgba(37, 99, 235, 0.1);
            color: var(--brand-blue) !important;
            border: 1px solid rgba(37, 99, 235, 0.2);
        }
        .nav-module-link:hover:not(.active) {
            background-color: #f1f5f9;
            color: var(--brand-dark) !important;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);
            background: #ffffff;
            overflow: hidden;
        }
        .card-header-accent {
            height: 4px;
            background: linear-gradient(90deg, var(--brand-blue) 0%, var(--brand-purple) 100%);
        }

        .table thead th {
            background-color: #f1f5f9;
            color: #475569;
            font-size: .7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            padding: 1rem;
            border-bottom: 2px solid #e2e8f0;
        }

        td code {
            background: #eff6ff;
            color: var(--brand-blue);
            font-size: .85rem;
            font-weight: 700;
            padding: .25rem .6rem;
            border-radius: 6px;
            border: 1px solid #bfdbfe;
        }

        .status-pill {
            padding: .35em .8em;
            font-size: .7rem;
            font-weight: 700;
            border-radius: 50rem;
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .badge-count {
            background: linear-gradient(135deg, #4c1d95 0%, #701a75 100%);
            color: #fff;
            font-size: .85rem;
            font-weight: 700;
            border-radius: 8px;
            padding: .5rem 1.2rem;
        }

        /* Estilos del Formulario Modal */
        .form-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--brand-dark);
            letter-spacing: 0.05em;
        }
        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.55rem 0.75rem;
        }
        .form-control:focus {
            border-color: var(--brand-blue);
            box-shadow: none;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom py-3">
        <div class="container">
            <span class="navbar-brand mb-0 h1 d-flex align-items-center gap-2">
                <i class="bi bi-droplet-half text-info"></i> Cusco-Vaso de Leche
            </span>
            <div class="d-flex align-items-center gap-4">
                <div class="text-white text-end d-none d-md-block" style="font-size: 0.8rem;">
                    <span class="opacity-75 d-block">Operador: <strong class="text-info">{{ strtoupper(auth()->user()->name) }}</strong></span>
                    <span class="text-white-50" style="font-size: 0.7rem;">Rol: {{ ucfirst(auth()->user()->rol) }}</span>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-light px-3 rounded-pill fw-bold" style="font-size: 0.75rem;">
                        <i class="bi bi-box-arrow-right me-1"></i> CERRAR SESIÓN
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container admin-layout">
        
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-3 small mb-4 p-3 d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill fs-5"></i> {{ session('success') }}
            </div>
        @endif

        <div class="card p-3 mb-4">
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <a href="{{ route('admin.dashboard') }}" class="btn nav-module-link active">
                    <i class="bi bi-graph-up-arrow me-1"></i> Monitoreo de Hoy
                </a>
                <a href="{{ route('admin.beneficiarios') }}" class="btn nav-module-link">
                    <i class="bi bi-people-fill me-1"></i> Control de Beneficiarios
                </a>
                
                @if(auth()->user()->rol === 'maestro')
                    <a href="{{ route('admin.usuarios') }}" class="btn nav-module-link text-danger">
                        <i class="bi bi-shield-lock-fill me-1"></i> Gestionar Personal Base
                    </a>
                @endif

                <button type="button" class="btn btn-success btn-sm ms-md-3 fw-bold px-3 py-2 rounded-3 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.02em;" data-bs-toggle="modal" data-bs-target="#modalBeneficiario">
                    <i class="bi bi-person-plus-fill me-1"></i> Nuevo Beneficiario
                </button>
            </div>
        </div>

        <div class="row align-items-center mb-4 g-3">
            <div class="col-md-6">
                <h2 class="page-title mb-1" style="font-size: 1.3rem; font-weight: 700;"><i class="bi bi-calendar-check me-2 text-primary"></i>Entregas Realizadas Hoy</h2>
                <p class="text-muted small mb-0">Sincronizado al: <strong>{{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</strong></p>
            </div>
            <div class="col-md-6 text-md-end">
                <span class="badge-count text-uppercase">Total Distribuciones: {{ $entregasHoy->count() }}</span>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header-accent"></div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Hora de Registro</th>
                                    <th>Identificación (DNI)</th>
                                    <th>Apellidos y Nombres</th>
                                    <th>Detalle Complemento</th>
                                    <th class="text-center">Verificación</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($entregasHoy as $entrega)
                                    <tr>
                                        <td class="ps-4 text-secondary style="font-size: 0.85rem;">
                                            <i class="bi bi-clock me-1 opacity-50"></i> {{ $entrega->created_at->format('H:i:s') }}
                                        </td>
                                        <td><code>{{ $entrega->beneficiario->dni }}</code></td>
                                        <td class="text-dark fw-semibold" style="font-size: 0.9rem;">
                                            {{ strtoupper($entrega->beneficiario->apellido) }}, {{ $entrega->beneficiario->nombre }}
                                        </td>
                                        <td class="text-muted" style="font-size: 0.85rem;">
                                            <i class="bi bi-box-seam me-1"></i> {{ $entrega->productos_entregados }}
                                        </td>
                                        <td class="text-center">
                                            <span class="status-pill"><i class="bi bi-check2-circle"></i> ENTREGADO</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <p class="mb-0 fw-medium">No se registran transacciones para el periodo actual.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modalBeneficiario" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modalBeneficiarioLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 style="border-radius: 16px; overflow: hidden; box-shadow: 0 20px 50px rgba(0,0,0,0.2);">
                
                <div class="modal-header text-white border-0 py-3" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%); border-bottom: 3px solid var(--brand-fuchsia) !important;">
                    <h5 class="modal-title fw-bold text-uppercase" id="modalBeneficiarioLabel" style="font-size: 0.95rem; letter-spacing: 0.05em;">
                        <i class="bi bi-person-vcard-fill text-info me-2"></i>Inscribir Nuevo Beneficiario
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-submit="modal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4 bg-white text-dark">
                    <form action="{{ route('beneficiario.guardar') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label text-uppercase">Documento Nacional de Identidad (DNI)</label>
                            <input type="text" name="dni" class="form-control fw-bold text-primary" placeholder="8 dígitos numéricos" maxlength="8" pattern="[0-9]{8}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-uppercase">Nombres</label>
                                <input type="text" name="nombres" class="form-control" placeholder="Ej. Ana Lucía" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-uppercase">Apellidos</label>
                                <input type="text" name="apellidos" class="form-control" placeholder="Ej. Quispe Flores" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-uppercase">Fecha de Nacimiento</label>
                            <input type="date" name="fecha_nacimiento" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-uppercase">Dirección Domiciaria</label>
                            <input type="text" name="direccion" class="form-control" placeholder="Ej. Av. El Sol 456 - Cusco" required>
                        </div>

                        <input type="hidden" name="estado" value="activo">

                        <div class="mt-4 d-flex gap-2">
                            <button type="button" class="btn btn-secondary w-50 fw-semibold text-uppercase" data-bs-dismiss="modal" style="font-size: 0.8rem;">Cancelar</button>
                            <button type="submit" class="btn btn-primary w-50 fw-bold text-uppercase" style="background-color: var(--brand-purple); border: none; font-size: 0.8rem;">Guardar Padrón</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>