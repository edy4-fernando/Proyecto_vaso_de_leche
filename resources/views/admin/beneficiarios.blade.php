<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Beneficiarios - Vaso de Leche</title>

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
            --row-hover:    #f8fafc;
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

        .table-hover tbody tr:hover {
            background-color: #eff6ff;
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

        .badge-count {
            background: linear-gradient(135deg, #4c1d95 0%, #701a75 100%);
            color: #fff;
            font-size: .85rem;
            font-weight: 700;
            border-radius: 8px;
            padding: .5rem 1.2rem;
        }

        .btn-outline-primary {
            border-color: #3b82f6;
            color: #2563eb;
            font-weight: 600;
            font-size: 0.75rem;
        }
        .btn-outline-primary:hover {
            background-color: #2563eb;
            border-color: #2563eb;
        }

        .btn-outline-danger {
            border-color: #ef4444;
            color: #dc2626;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .form-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--brand-dark);
            letter-spacing: 0.05em;
        }
        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.55rem 0.75rem;
        }
        .form-control:focus, .form-select:focus {
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
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-light px-3 rounded-pill fw-bold" style="font-size: 0.75rem;">
                    CERRAR SESIÓN
                </button>
            </form>
        </div>
    </nav>

    <div class="container admin-layout">

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-3 small mb-4 p-3 d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill fs-5"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm rounded-3 small mb-4 p-3 d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i> {{ session('error') }}
            </div>
        @endif

        <div class="card p-3 mb-4">
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <a href="{{ route('admin.dashboard') }}" class="btn nav-module-link">
                    <i class="bi bi-graph-up-arrow me-1"></i> Monitoreo de Hoy
                </a>
                <a href="{{ route('admin.beneficiarios') }}" class="btn nav-module-link active">
                    <i class="bi bi-people-fill me-1"></i> Control de Beneficiarios
                </a>
                
                @if(auth()->user()->rol === 'maestro')
                    <a href="{{ route('admin.usuarios') }}" class="btn nav-module-link text-danger">
                        <i class="bi bi-shield-lock-fill me-1"></i> Gestionar Personal Base
                    </a>
                @endif

                @if(auth()->user()->rol === 'maestro')
                    <button type="button" class="btn btn-success btn-sm ms-md-3 fw-bold px-3 py-2 rounded-3 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.02em;" data-bs-toggle="modal" data-bs-target="#modalBeneficiario">
                        <i class="bi bi-person-plus-fill me-1"></i> Nuevo Beneficiario
                    </button>
                @endif
            </div>
        </div>

        <div class="row align-items-center mb-4 g-3">
            <div class="col-md-6">
                <h2 class="page-title mb-1" style="font-size: 1.3rem; font-weight: 700;"><i class="bi bi-folder-fill me-2 text-primary"></i>Base de Datos General</h2>
                <p class="text-muted small mb-0">Padrón histórico consolidado de ciudadanos registrados en el programa.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <span class="badge-count text-uppercase">Registrados: {{ $beneficiarios->count() }}</span>
            </div>
        </div>

        <div class="card">
            <div class="card-header-accent"></div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Identificación DNI</th>
                            <th>Apellidos y Nombres</th>
                            <th>Dirección Domiciliaria</th>
                            <th class="text-center">Raciones Retiradas</th> 
                            <th class="text-center">Acciones de Gestión</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($beneficiarios as $b)
                            <tr>
                                <td class="ps-4">
                                    <code>{{ $b->dni }}</code>
                                </td>
                                <td class="fw-bold text-dark" style="font-size: 0.9rem;">
                                    {{ strtoupper($b->apellido) }}, {{ $b->nombre }}
                                </td>
                                <td class="text-muted small">
                                    <i class="bi bi-geo-alt me-1 opacity-75"></i> {{ $b->direccion }}
                                </td>
                                
                                <td class="text-center">
                                    @if($b->entregas->count() > 0)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1 fw-bold" style="font-size: 0.75rem;">
                                            <i class="bi bi-calendar-check-fill me-1"></i> {{ $b->entregas->count() }} Atenciones
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-3 py-1 fw-bold" style="font-size: 0.75rem;">
                                            <i class="bi bi-clock-history me-1"></i> Sin Ración
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        @if(auth()->user()->rol === 'maestro')
                                            <button type="button" class="btn btn-sm btn-success fw-bold text-uppercase px-2 py-1" style="font-size: 0.72rem;" data-bs-toggle="modal" data-bs-target="#modalEntregarRacion" data-id="{{ $b->id }}" data-nombre="{{ $b->nombre }} {{ $b->apellido }}">
                                                <i class="bi bi-droplet-fill"></i> Ración
                                            </button>

                                            <a href="{{ route('admin.editar', $b->id) }}" class="btn btn-sm btn-outline-primary px-3 py-1 text-uppercase fw-bold">
                                                <i class="bi bi-pencil-square me-1"></i> Editar
                                            </a>
                                            
                                            <form action="{{ route('admin.eliminar', $b->id) }}" method="POST" onsubmit="return confirm('¿Está completamente seguro de eliminar permanentemente a este ciudadano del padrón histórico?');" class="m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger px-2 py-1 text-uppercase fw-bold">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-1 rounded-pill fw-semibold" style="font-size: 0.75rem;">
                                                <i class="bi bi-lock-fill me-1"></i> Solo Lectura
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-people fs-2 opacity-25 mb-2 d-block"></i>
                                    <p class="mb-0 fw-medium">No existen beneficiarios cargados en la base de datos.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    @if(auth()->user()->rol === 'maestro')
        <div class="modal fade" id="modalBeneficiario" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modalBeneficiarioLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0" style="border-radius: 16px; overflow: hidden; box-shadow: 0 20px 50px rgba(0,0,0,0.2);">
                    <div class="modal-header text-white border-0 py-3" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%); border-bottom: 3px solid var(--brand-fuchsia) !important;">
                        <h5 class="modal-title fw-bold text-uppercase" id="modalBeneficiarioLabel" style="font-size: 0.95rem; letter-spacing: 0.05em;">
                            <i class="bi bi-person-vcard-fill text-info me-2"></i>Inscribir Nuevo Beneficiario
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                <label class="form-label text-uppercase">Dirección Domiciliaria</label>
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

        <div class="modal fade" id="modalEntregarRacion" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0" style="border-radius: 16px; overflow: hidden; box-shadow: 0 20px 50px rgba(0,0,0,0.2);">
                    <div class="modal-header text-white border-0 py-3" style="background: linear-gradient(135deg, #0f172a 0%, #a21caf 100%); border-bottom: 3px solid var(--brand-blue) !important;">
                        <h5 class="modal-title fw-bold text-uppercase" style="font-size: 0.95rem; letter-spacing: 0.05em;">
                            <i class="bi bi-droplet-fill text-info me-2"></i>Despachar Alimentos PVL
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4 bg-white text-dark">
                        <form action="{{ route('admin.entregas.guardar') }}" method="POST">
                            @csrf
                            
                            <input type="hidden" name="beneficiario_id" id="modal_beneficiario_id">

                            <div class="mb-3">
                                <label class="form-label text-uppercase">Ciudadano Receptor</label>
                                <input type="text" id="modal_beneficiario_nombre" class="form-control fw-bold text-secondary bg-light" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-uppercase">Seleccionar Alimento e Inventario Disponible</label>
                                <select name="producto_id" class="form-select fw-semibold text-dark" required>
                                    @style('font-size: 0.85rem;')
                                    <option value="" disabled selected>-- Elija un lote del almacén --</option>
                                    @foreach($productos as $prod)
                                        <option value="{{ $prod->id }}">
                                            [{{ strtoupper($prod->tipo_insumo) }}] {{ $prod->nombre }} (Marca: {{ $prod->marca }} | Stock: {{ $prod->stock_actual }} und)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-uppercase">Cantidad Solicitada (Raciones / Unidades)</label>
                                <input type="number" name="cantidad_entregada" class="form-control fw-bold text-primary" min="1" value="1" required>
                            </div>

                            <div class="mt-4 d-flex gap-2">
                                <button type="button" class="btn btn-secondary w-50 fw-semibold text-uppercase" data-bs-dismiss="modal" style="font-size: 0.8rem;">Cerrar</button>
                                <button type="submit" class="btn btn-success w-50 fw-bold text-uppercase" style="font-size: 0.8rem;">Confirmar Salida</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        const modalEntregarRacion = document.getElementById('modalEntregarRacion');
        if (modalEntregarRacion) {
            modalEntregarRacion.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget;
                const beneficiarioId = button.getAttribute('data-id');
                const beneficiarioNombre = button.getAttribute('data-nombre');

                document.getElementById('modal_beneficiario_id').value = beneficiarioId;
                document.getElementById('modal_beneficiario_nombre').value = beneficiarioNombre;
            });
        }
    </script>
</body>
</html>