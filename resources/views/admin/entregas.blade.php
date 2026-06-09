@extends('layouts.app') 

@section('content')
<div class="container-fluid mt-4">
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="m-0 font-weight-bold"><i class="bi bi-journal-check"></i> Historial de Entregas - Vaso de Leche</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="tablaEntregas" width="100%" cellspacing="0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Beneficiario</th>
                            <th>DNI</th>
                            <th>Atendido Por (Admin)</th>
                            <th>Fecha de Entrega</th>
                            <th>Detalle del Insumo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($entregas as $entrega)
                            <tr>
                                <td><strong>#{{ $entrega->id }}</strong></td>
                                <td>{{ $entrega->beneficiario->nombre }} {{ $entrega->beneficiario->apellido }}</td>
                                <td><span class="badge bg-secondary">{{ $entrega->beneficiario->dni }}</span></td>
                                <td>{{ $entrega->user->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($entrega->fecha_entrega)->format('d/m/Y') }}</td>
                                <td>
                                    <span class="text-success font-weight-bold">
                                        <i class="bi bi-box-seam"></i> {{ $entrega->productos_entregados }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-info-circle fs-4 d-block mb-2"></i>
                                    No se han registrado entregas de raciones en el sistema todavía.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection