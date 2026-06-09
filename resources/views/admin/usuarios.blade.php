<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Personal - Vaso de Leche</title>
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
            background-color: rgba(220, 38, 38, 0.1);
            color: #dc2626 !important;
            border: 1px solid rgba(220, 38, 38, 0.2);
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
            background: linear-gradient(90deg, #dc2626 0%, var(--brand-fuchsia) 100%);
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

        .role-badge {
            padding: .35em .8em;
            font-size: .7rem;
            font-weight: 700;
            border-radius: 6px;
            letter-spacing: .05em;
            text-transform: uppercase;
        }
        .role-maestro {
            background-color: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        .role-trabajador {
            background-color: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .form-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--brand-dark);
            letter-spacing: 0.03em;
        }
        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.6rem 0.75rem;
            font-size: 0.9rem;
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
                <i class="bi bi-exclamation-octagon-fill fs-5"></i> {{ session('error') }}
            </div>
        @endif

        <div class="card p-3 mb-4">
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.dashboard') }}" class="btn nav-module-link">
                    <i class="bi bi-graph-up-arrow me-1"></i> Monitoreo de Hoy
                </a>
                <a href="{{ route('admin.beneficiarios') }}" class="btn nav-module-link">
                    <i class="bi bi-people-fill me-1"></i> Control de Beneficiarios
                </a>
                <a href="{{ route('admin.usuarios') }}" class="btn nav-module-link active">
                    <i class="bi bi-shield-lock-fill me-1"></i> Gestionar Personal Base
                </a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card p-4">
                    <h4 class="fw-bold text-dark mb-1" style="font-size: 1.1rem;">Registrar Usuario</h4>
                    <p class="text-muted small mb-3">Añade personal clínico o técnico al sistema administrativo.</p>
                    
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 small p-2 mb-3 rounded-3">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.usuarios.guardar') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-uppercase">Nombre Completo</label>
                            <input type="text" name="name" class="form-control" placeholder="Ej. Juan Pérez" value="{{ old('name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-uppercase">Correo Electrónico</label>
                            <input type="email" name="email" class="form-control" placeholder="usuario@muni.gob.pe" value="{{ old('email') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-uppercase">Contraseña Inicial</label>
                            <input type="password" name="password" class="form-control" placeholder="Mínimo 6 caracteres" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-uppercase">Asignar Nivel / Jerarquía</label>
                            <select name="rol" class="form-select" required>
                                <option value="trabajador" {{ old('rol') == 'trabajador' ? 'selected' : '' }}>Trabajador Común (Soporte)</option>
                                <option value="maestro" {{ old('rol') == 'maestro' ? 'selected' : '' }}>Administrador Maestro (Control Total)</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-danger w-100 fw-bold py-2.5 text-uppercase shadow-sm" style="background-color: var(--brand-fuchsia); border: none;">
                            Autorizar Acceso <i class="bi bi-person-plus-fill ms-1"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header-accent"></div>
                    <div class="p-3 bg-light border-bottom">
                        <h5 class="fw-bold m-0" style="font-size: 1rem; color: var(--brand-dark);">Personal con Credenciales Activas</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-3">Nombre Completo</th>
                                    <th>Correo Electrónico</th>
                                    <th>Rango / Rol</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($usuarios as $user)
                                    <tr>
                                        <td class="ps-3 fw-semibold text-dark" style="font-size: 0.9rem;">
                                            {{ $user->name }}
                                            @if($user->id === auth()->id())
                                                <span class="text-primary small fw-normal ms-1">(Tú)</span>
                                            @endif
                                        </td>
                                        <td class="text-secondary" style="font-size: 0.85rem;">
                                            {{ $user->email }}
                                        </td>
                                        <td>
                                            <span class="role-badge {{ $user->rol === 'maestro' ? 'role-maestro' : 'role-trabajador' }}">
                                                {{ $user->rol }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($user->id !== auth()->id())
                                                <form action="{{ route('admin.usuarios.eliminar', $user->id) }}" method="POST" onsubmit="return confirm('¿Está seguro de revocar permanentemente los accesos a este usuario?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger px-2 py-1" title="Revocar Acceso">
                                                        <i class="bi bi-trash3-fill"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted small italic">Protegido</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>