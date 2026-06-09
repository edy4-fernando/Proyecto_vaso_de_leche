<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Beneficiario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-sm mx-auto" style="max-width: 600px;">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0 text-uppercase">Editar Datos del Beneficiario</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.actualizar', $beneficiario->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">DNI</label>
                        <input type="text" name="dni" class="form-control" value="{{ $beneficiario->dni }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nombres</label>
                            <input type="text" name="nombres" class="form-control" value="{{ $beneficiario->nombres }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Apellidos</label>
                            <input type="text" name="apellidos" class="form-control" value="{{ $beneficiario->apellidos }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Dirección</label>
                        <input type="text" name="direccion" class="form-control" value="{{ $beneficiario->direccion }}" required>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">GUARDAR CAMBIOS</button>
                        <a href="{{ route('admin.beneficiarios') }}" class="btn btn-link text-muted small">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>