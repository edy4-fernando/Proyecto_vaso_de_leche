@extends('layouts.dashboard')

@section('title', 'Actividad — Vaso de Leche')
@section('breadcrumb', 'Historial de Actividad')
@php $activeModule = 'actividad'; @endphp

@section('content')

{{-- ── Encabezado ── --}}
<div class="vl-section-header">
  <div>
    <h2 class="vl-section-title">
      <i class="bi bi-activity text-primary"></i>
      Historial de Actividad
    </h2>
    <p class="vl-section-sub">
      Registro de todas las acciones realizadas por el personal del sistema
    </p>
  </div>
  <button class="vl-btn vl-btn--outline vl-btn--sm" onclick="window.print()">
    <i class="bi bi-printer-fill"></i>
    Imprimir
  </button>
</div>

{{-- ── Stats ── --}}
<div class="row g-3 mb-4">
  <div class="col-6 col-lg-3 vl-animate">
    <div class="vl-stat">
      <div class="vl-stat__icon vl-stat__icon--blue">
        <i class="bi bi-journal-text"></i>
      </div>
      <div class="vl-stat__body">
        <div class="vl-stat__value">{{ $totalActividad }}</div>
        <div class="vl-stat__label">Total acciones</div>
      </div>
    </div>
  </div>
  <div class="col-6 col-lg-3 vl-animate vl-animate--delay-1">
    <div class="vl-stat">
      <div class="vl-stat__icon vl-stat__icon--green">
        <i class="bi bi-calendar-day"></i>
      </div>
      <div class="vl-stat__body">
        <div class="vl-stat__value">{{ $actividadHoy }}</div>
        <div class="vl-stat__label">Hoy</div>
      </div>
    </div>
  </div>
  <div class="col-6 col-lg-3 vl-animate vl-animate--delay-2">
    <div class="vl-stat">
      <div class="vl-stat__icon vl-stat__icon--violet">
        <i class="bi bi-people-fill"></i>
      </div>
      <div class="vl-stat__body">
        <div class="vl-stat__value">{{ $usuariosActivos }}</div>
        <div class="vl-stat__label">Operadores activos</div>
      </div>
    </div>
  </div>
  <div class="col-6 col-lg-3 vl-animate vl-animate--delay-3">
    <div class="vl-stat">
      <div class="vl-stat__icon vl-stat__icon--amber">
        <i class="bi bi-exclamation-triangle-fill"></i>
      </div>
      <div class="vl-stat__body">
        <div class="vl-stat__value">{{ $accionesCriticas }}</div>
        <div class="vl-stat__label">Acciones críticas</div>
      </div>
    </div>
  </div>
</div>

{{-- ── Filtros ── --}}
<div class="vl-card mb-4 vl-animate">
  <div class="vl-card__body" style="padding:14px 20px;">
    <form method="GET" action="{{ route('dashboard.actividad') }}">
      <div class="vl-search-bar">

        <div class="vl-input-icon-wrap" style="flex:1; min-width:200px;">
          <i class="bi bi-search"></i>
          <input type="text" name="q" class="vl-input"
                 value="{{ request('q') }}"
                 placeholder="Buscar por acción o descripción…">
        </div>

        <select name="accion" class="vl-select" style="width:auto; min-width:160px;">
          <option value="">Todas las acciones</option>
          <option value="BENEFICIARIO_CREADO"    {{ request('accion') === 'BENEFICIARIO_CREADO'    ? 'selected' : '' }}>Beneficiario creado</option>
          <option value="BENEFICIARIO_EDITADO"   {{ request('accion') === 'BENEFICIARIO_EDITADO'   ? 'selected' : '' }}>Beneficiario editado</option>
          <option value="BENEFICIARIO_ELIMINADO" {{ request('accion') === 'BENEFICIARIO_ELIMINADO' ? 'selected' : '' }}>Beneficiario eliminado</option>
          <option value="BENEFICIARIO_DADO_DE_BAJA" {{ request('accion') === 'BENEFICIARIO_DADO_DE_BAJA' ? 'selected' : '' }}>Dado de baja</option>
          <option value="BENEFICIARIO_REACTIVADO" {{ request('accion') === 'BENEFICIARIO_REACTIVADO' ? 'selected' : '' }}>Reactivado</option>
          <option value="ENTREGA_REGISTRADA"     {{ request('accion') === 'ENTREGA_REGISTRADA'     ? 'selected' : '' }}>Entrega registrada</option>
          <option value="USUARIO_CREADO"         {{ request('accion') === 'USUARIO_CREADO'         ? 'selected' : '' }}>Usuario creado</option>
          <option value="USUARIO_ELIMINADO"      {{ request('accion') === 'USUARIO_ELIMINADO'      ? 'selected' : '' }}>Usuario eliminado</option>
          <option value="STOCK_REABASTECIDO"     {{ request('accion') === 'STOCK_REABASTECIDO'     ? 'selected' : '' }}>Stock reabastecido</option>
          <option value="PRODUCTO_ELIMINADO"     {{ request('accion') === 'PRODUCTO_ELIMINADO'     ? 'selected' : '' }}>Producto eliminado</option>
        </select>

        <div style="min-width:150px;">
          <input type="date" name="desde" class="vl-input"
                 value="{{ request('desde') }}" title="Desde">
        </div>

        <div style="min-width:150px;">
          <input type="date" name="hasta" class="vl-input"
                 value="{{ request('hasta') }}" title="Hasta">
        </div>

        <button type="submit" class="vl-btn vl-btn--primary vl-btn--sm">
          <i class="bi bi-funnel-fill"></i> Filtrar
        </button>

        @if(request()->hasAny(['q','accion','desde','hasta']))
          <a href="{{ route('dashboard.actividad') }}"
             class="vl-btn vl-btn--ghost vl-btn--sm">
            <i class="bi bi-x-circle"></i> Limpiar
          </a>
        @endif

      </div>
    </form>
  </div>
</div>

{{-- ── Tabla ── --}}
<div class="vl-card vl-animate vl-animate--delay-1">
  <div class="vl-card__accent"></div>
  <div class="vl-card__header">
    <h5 class="vl-card__title">
      <i class="bi bi-list-ul text-primary"></i>
      Registro de Acciones
    </h5>
    <span class="vl-badge vl-badge--blue">
      {{ $actividad->total() }} registros
    </span>
  </div>
  <div class="vl-card__body" style="padding:0;">
    <div class="vl-table-wrap">
      <table class="vl-table">
        <thead>
          <tr>
            <th>Fecha / Hora</th>
            <th>Operador</th>
            <th>Acción</th>
            <th>Descripción</th>
            <th>IP</th>
          </tr>
        </thead>
        <tbody>
          @forelse($actividad as $log)
            @php
              $config = [
                'BENEFICIARIO_CREADO'       => ['vl-badge--green',  'bi-person-plus-fill',    'Creación'],
                'BENEFICIARIO_EDITADO'      => ['vl-badge--blue',   'bi-pencil-fill',         'Edición'],
                'BENEFICIARIO_ELIMINADO'    => ['vl-badge--rose',   'bi-trash3-fill',         'Eliminación'],
                'BENEFICIARIO_DADO_DE_BAJA' => ['vl-badge--amber',  'bi-person-dash-fill',    'Baja'],
                'BENEFICIARIO_REACTIVADO'   => ['vl-badge--green',  'bi-person-check-fill',   'Reactivación'],
                'ENTREGA_REGISTRADA'        => ['vl-badge--blue',   'bi-journal-check',       'Entrega'],
                'USUARIO_CREADO'            => ['vl-badge--violet', 'bi-shield-plus',         'Usuario'],
                'USUARIO_ELIMINADO'         => ['vl-badge--rose',   'bi-shield-x',            'Usuario'],
                'STOCK_REABASTECIDO'        => ['vl-badge--green',  'bi-box-seam-fill',       'Inventario'],
                'PRODUCTO_ELIMINADO'        => ['vl-badge--rose',   'bi-box-seam',            'Inventario'],
              ];
              $c = $config[$log->accion] ?? ['vl-badge--slate', 'bi-circle-fill', 'Sistema'];
            @endphp
            <tr>

              {{-- Fecha --}}
              <td>
                <span style="font-size:.78rem; white-space:nowrap;">
                  {{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y') }}
                </span>
                <span style="display:block; font-size:.68rem; color:var(--vl-text-muted);">
                  {{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s') }}
                </span>
              </td>

              {{-- Operador --}}
              <td>
                <div class="vl-table__cell-user">
                  <div class="vl-table__mini-avatar"
                       style="width:26px; height:26px; font-size:.65rem;">
                    {{ strtoupper(substr($log->user->name ?? 'S', 0, 2)) }}
                  </div>
                  <div>
                    <span class="vl-table__cell-name" style="font-size:.78rem;">
                      {{ $log->user->name ?? 'Sistema' }}
                    </span>
                    <span class="vl-table__cell-sub">
                      {{ ucfirst($log->user->rol ?? '—') }}
                    </span>
                  </div>
                </div>
              </td>

              {{-- Acción --}}
              <td>
                <span class="vl-badge {{ $c[0] }}">
                  <i class="bi {{ $c[1] }}"></i>
                  {{ $c[2] }}
                </span>
              </td>

              {{-- Descripción --}}
              <td>
                <span style="font-size:.78rem; color:var(--vl-text-sub);">
                  {{ $log->descripcion }}
                </span>
              </td>

              {{-- IP --}}
              <td>
                <span style="font-size:.72rem; font-family:var(--vl-font-mono);
                             color:var(--vl-text-muted);">
                  {{ $log->ip ?? '—' }}
                </span>
              </td>

            </tr>
          @empty
            <tr>
              <td colspan="5">
                <div class="vl-table__empty">
                  <i class="bi bi-journal-x"></i>
                  <p>No hay actividad registrada con los filtros seleccionados.</p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Paginación --}}
    @if($actividad->hasPages())
      <div class="p-3 border-top"
           style="border-color:var(--vl-border) !important;">
        <div class="d-flex align-items-center
                    justify-content-between flex-wrap gap-2">
          <span style="font-size:.75rem; color:var(--vl-text-muted);">
            Mostrando {{ $actividad->firstItem() }}–{{ $actividad->lastItem() }}
            de {{ $actividad->total() }} registros
          </span>
          {{ $actividad->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
      </div>
    @endif

  </div>
</div>

@endsection