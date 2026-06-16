{{-- ============================================================
     admin/perfil-beneficiario.blade.php
     Ruta: GET /admin/beneficiarios/{id}/perfil
     Variables: $beneficiario, $entregas, $totalRaciones,
                $racionesEsteMes, $ultimaEntrega
     ============================================================ --}}

@extends('layouts.admin')

@section('title', 'Perfil — ' . $beneficiario->nombre . ' ' . $beneficiario->apellido)
@section('breadcrumb', 'Perfil de Beneficiario')
@php $activeModule = 'beneficiarios'; @endphp

@section('content')

{{-- ── Botón volver ── --}}
<div class="mb-3">
  <a href="{{ route('admin.beneficiarios') }}"
     class="vl-btn vl-btn--ghost vl-btn--sm">
    <i class="bi bi-arrow-left"></i>
    Volver al padrón
  </a>
</div>

<div class="row g-4">

  {{-- ══════════════════════════════════════════
       COLUMNA IZQUIERDA — Datos del beneficiario
       ══════════════════════════════════════════ --}}
  <div class="col-lg-4">

    {{-- Card perfil --}}
    <div class="vl-profile-card vl-animate mb-4">

      {{-- Avatar grande --}}
      <div class="vl-profile-avatar">
        {{ strtoupper(substr($beneficiario->nombre, 0, 1) . substr($beneficiario->apellido, 0, 1)) }}
      </div>

      <h3 class="vl-profile-name">
        {{ strtoupper($beneficiario->apellido) }}, {{ $beneficiario->nombre }}
      </h3>
      <p class="vl-profile-dni">
        <i class="bi bi-card-text me-1"></i>
        DNI: {{ $beneficiario->dni }}
      </p>

      {{-- Estado --}}
      @if($beneficiario->estado)
        <span class="vl-badge vl-badge--green mb-3">
          <i class="bi bi-check-circle-fill"></i> Activo en el padrón
        </span>
      @else
        <span class="vl-badge vl-badge--rose mb-3">
          <i class="bi bi-x-circle-fill"></i> Dado de baja
        </span>
      @endif

      {{-- Tipo --}}
      <div style="margin-top: 4px;">
        <span class="vl-badge vl-badge--blue">
          {{ ucfirst($beneficiario->tipo_beneficiario ?? 'Sin tipo') }}
        </span>
      </div>

      {{-- Acciones rápidas --}}
      <div style="
        display: flex; gap: 8px; margin-top: 20px;
        justify-content: center; flex-wrap: wrap;
      ">
        <a href="{{ route('admin.editar', $beneficiario->id) }}"
           class="vl-btn vl-btn--primary vl-btn--sm">
          <i class="bi bi-pencil-fill"></i> Editar
        </a>

        @if(auth()->user()->rol === 'maestro')
          <form action="{{ route('admin.beneficiarios.toggle', $beneficiario->id) }}"
                method="POST" class="m-0">
            @csrf
            @method('PATCH')
            <button type="submit"
                    class="vl-btn vl-btn--outline vl-btn--sm {{ $beneficiario->estado ? 'text-warning' : 'text-success' }}">
              <i class="bi bi-{{ $beneficiario->estado ? 'person-dash-fill' : 'person-check-fill' }}"></i>
              {{ $beneficiario->estado ? 'Dar de baja' : 'Reactivar' }}
            </button>
          </form>
        @endif
      </div>

    </div>

    {{-- Card datos personales --}}
    <div class="vl-card vl-animate vl-animate--delay-1">
      <div class="vl-card__accent"></div>
      <div class="vl-card__header">
        <h5 class="vl-card__title">
          <i class="bi bi-person-lines-fill text-primary"></i>
          Datos Personales
        </h5>
      </div>
      <div class="vl-card__body">

        @php
          $datos = [
            ['Fecha de nacimiento', $beneficiario->fecha_nacimiento
              ? \Carbon\Carbon::parse($beneficiario->fecha_nacimiento)->format('d/m/Y')
                . ' (' . \Carbon\Carbon::parse($beneficiario->fecha_nacimiento)->age . ' años)'
              : '—', 'bi-calendar3'],
            ['Dirección', $beneficiario->direccion ?? '—', 'bi-geo-alt-fill'],
            ['Teléfono', $beneficiario->telefono ?? '—', 'bi-telephone-fill'],
            ['Sector / Comité', $beneficiario->sector_o_comite ?? '—', 'bi-building'],
            ['Apoderado', $beneficiario->nombre_apoderado ?? '—', 'bi-person-fill'],
            ['DNI apoderado', $beneficiario->dni_apoderado ?? '—', 'bi-card-text'],
          ];
        @endphp

        @foreach($datos as [$label, $valor, $icon])
          <div style="
            display: flex; align-items: flex-start; gap: 10px;
            padding: 9px 0;
            {{ !$loop->last ? 'border-bottom: 1px solid var(--vl-border);' : '' }}
          ">
            <i class="bi {{ $icon }}"
               style="color: var(--vl-blue-600); font-size: .85rem;
                      margin-top: 1px; flex-shrink: 0;"></i>
            <div>
              <div style="font-size: .68rem; color: var(--vl-text-muted);
                          text-transform: uppercase; letter-spacing: .5px;">
                {{ $label }}
              </div>
              <div style="font-size: .82rem; color: var(--vl-text-main);
                          font-weight: 500; margin-top: 1px;">
                {{ $valor }}
              </div>
            </div>
          </div>
        @endforeach

        {{-- Observaciones médicas --}}
        @if($beneficiario->observaciones_medicas)
          <div style="
            margin-top: 10px;
            background: rgba(245,158,11,.08);
            border: 1px solid rgba(245,158,11,.2);
            border-radius: var(--vl-radius-sm);
            padding: 10px 12px;
          ">
            <div style="font-size: .68rem; color: var(--vl-amber);
                        text-transform: uppercase; letter-spacing: .5px;
                        margin-bottom: 4px;">
              <i class="bi bi-exclamation-triangle-fill me-1"></i>
              Observaciones médicas
            </div>
            <p style="font-size: .78rem; color: var(--vl-text-sub); margin: 0;">
              {{ $beneficiario->observaciones_medicas }}
            </p>
          </div>
        @endif

      </div>
    </div>

  </div>

  {{-- ══════════════════════════════════════════
       COLUMNA DERECHA — Stats + Historial
       ══════════════════════════════════════════ --}}
  <div class="col-lg-8">

    {{-- Stats rápidas --}}
    <div class="row g-3 mb-4">

      <div class="col-6 col-md-4 vl-animate">
        <div class="vl-stat">
          <div class="vl-stat__icon vl-stat__icon--blue">
            <i class="bi bi-box-arrow-in-down-right"></i>
          </div>
          <div class="vl-stat__body">
            <div class="vl-stat__value">{{ $totalRaciones ?? 0 }}</div>
            <div class="vl-stat__label">Total raciones</div>
          </div>
        </div>
      </div>

      <div class="col-6 col-md-4 vl-animate vl-animate--delay-1">
        <div class="vl-stat">
          <div class="vl-stat__icon vl-stat__icon--green">
            <i class="bi bi-calendar-month"></i>
          </div>
          <div class="vl-stat__body">
            <div class="vl-stat__value">{{ $racionesEsteMes ?? 0 }}</div>
            <div class="vl-stat__label">Este mes</div>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-4 vl-animate vl-animate--delay-2">
        <div class="vl-stat">
          <div class="vl-stat__icon vl-stat__icon--violet">
            <i class="bi bi-clock-history"></i>
          </div>
          <div class="vl-stat__body">
            <div class="vl-stat__value" style="font-size: 1rem;">
              @if($ultimaEntrega)
                {{ \Carbon\Carbon::parse($ultimaEntrega->fecha_entrega)->format('d/m/Y') }}
              @else
                —
              @endif
            </div>
            <div class="vl-stat__label">Última entrega</div>
          </div>
        </div>
      </div>

    </div>

    {{-- Historial de entregas --}}
    <div class="vl-card vl-animate vl-animate--delay-1">
      <div class="vl-card__accent"></div>
      <div class="vl-card__header">
        <h5 class="vl-card__title">
          <i class="bi bi-journal-check text-primary"></i>
          Historial de Entregas
        </h5>
        <span class="vl-badge vl-badge--blue">
          {{ $entregas->total() ?? $entregas->count() }} registros
        </span>
      </div>
      <div class="vl-card__body" style="padding: 0;">
        <div class="vl-table-wrap">
          <table class="vl-table">
            <thead>
              <tr>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Operador</th>
                <th>Observaciones</th>
              </tr>
            </thead>
            <tbody>
              @forelse($entregas as $e)
                <tr>
                  <td>
                    <span style="font-size: .8rem; white-space: nowrap;">
                      {{ \Carbon\Carbon::parse($e->fecha_entrega)->format('d/m/Y') }}
                    </span>
                  </td>
                  <td>
                    <span style="font-size: .75rem; color: var(--vl-text-muted);">
                      {{ $e->hora_entrega ?? '—' }}
                    </span>
                  </td>
                  <td>
                    <span style="font-size: .78rem;">
                      {{ $e->producto->nombre ?? 'Ración general' }}
                    </span>
                  </td>
                  <td>
                    <span class="vl-badge vl-badge--blue">{{ $e->cantidad }}</span>
                  </td>
                  <td>
                    <span style="font-size: .75rem; color: var(--vl-text-muted);">
                      {{ $e->user->name ?? 'Sistema' }}
                    </span>
                  </td>
                  <td>
                    @if($e->observaciones_incidencias)
                      <span class="vl-badge vl-badge--amber"
                            data-bs-toggle="tooltip"
                            title="{{ $e->observaciones_incidencias }}">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        Ver nota
                      </span>
                    @else
                      <span style="color: var(--vl-text-muted); font-size: .75rem;">—</span>
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6">
                    <div class="vl-table__empty">
                      <i class="bi bi-inbox"></i>
                      <p>Este beneficiario no tiene entregas registradas.</p>
                    </div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        {{-- Paginación --}}
        @if(method_exists($entregas, 'links'))
          <div class="p-3">
            {{ $entregas->links('pagination::bootstrap-5') }}
          </div>
        @endif

      </div>
    </div>

  </div>

</div>

@endsection