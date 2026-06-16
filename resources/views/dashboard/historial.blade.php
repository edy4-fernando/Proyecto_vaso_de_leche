@extends('layouts.dashboard')

@section('title', 'Historial de Entregas — Vaso de Leche')
@section('breadcrumb', 'Historial de Entregas')
@php $activeModule = 'historial'; @endphp

@section('content')

{{-- ── Encabezado ── --}}
<div class="vl-section-header">
  <div>
    <h2 class="vl-section-title">
      <i class="bi bi-clock-history text-primary"></i>
      Historial de Entregas
    </h2>
    <p class="vl-section-sub">
      Registro completo — solo lectura
    </p>
  </div>
  <button class="vl-btn vl-btn--outline vl-btn--sm"
          onclick="window.print()">
    <i class="bi bi-printer-fill"></i>
    Imprimir
  </button>
</div>

{{-- ── Stats ── --}}
@php
  $totalGeneral = $entregas->total();
  $hoy          = $entregas->getCollection()->filter(fn($e) =>
    \Carbon\Carbon::parse($e->fecha_entrega)->isToday()
  )->count();
  $esteMes      = $entregas->getCollection()->filter(fn($e) =>
    \Carbon\Carbon::parse($e->fecha_entrega)->isCurrentMonth()
  )->count();
@endphp

<div class="row g-3 mb-4">
  <div class="col-6 col-lg-4 vl-animate">
    <div class="vl-stat">
      <div class="vl-stat__icon vl-stat__icon--blue">
        <i class="bi bi-journal-check"></i>
      </div>
      <div class="vl-stat__body">
        <div class="vl-stat__value">{{ $totalGeneral }}</div>
        <div class="vl-stat__label">Total histórico</div>
      </div>
    </div>
  </div>
  <div class="col-6 col-lg-4 vl-animate vl-animate--delay-1">
    <div class="vl-stat">
      <div class="vl-stat__icon vl-stat__icon--green">
        <i class="bi bi-calendar-day"></i>
      </div>
      <div class="vl-stat__body">
        <div class="vl-stat__value">{{ $hoy }}</div>
        <div class="vl-stat__label">Hoy (esta página)</div>
      </div>
    </div>
  </div>
  <div class="col-12 col-lg-4 vl-animate vl-animate--delay-2">
    <div class="vl-stat">
      <div class="vl-stat__icon vl-stat__icon--violet">
        <i class="bi bi-calendar-month"></i>
      </div>
      <div class="vl-stat__body">
        <div class="vl-stat__value">{{ $esteMes }}</div>
        <div class="vl-stat__label">Este mes (esta página)</div>
      </div>
    </div>
  </div>
</div>

{{-- ── Filtros ── --}}
<div class="vl-card mb-4 vl-animate">
  <div class="vl-card__body" style="padding:14px 20px;">
    <form method="GET" action="{{ route('dashboard.historial') }}">
      <div class="vl-search-bar">
        <div class="vl-input-icon-wrap" style="flex:1; min-width:200px;">
          <i class="bi bi-search"></i>
          <input type="text"
                 name="q"
                 class="vl-input"
                 value="{{ request('q') }}"
                 placeholder="Buscar por nombre o DNI…">
        </div>
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
        @if(request()->hasAny(['q','desde','hasta']))
          <a href="{{ route('dashboard.historial') }}"
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
      Registros de Entregas
    </h5>
    <span class="vl-badge vl-badge--blue">
      {{ $entregas->total() }} total
    </span>
  </div>
  <div class="vl-card__body" style="padding:0;">
    <div class="vl-table-wrap">
      <table class="vl-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Beneficiario</th>
            <th>DNI</th>
            <th>Tipo</th>
            <th>Producto</th>
            <th>Cant.</th>
            <th>Sector</th>
            <th>Operador</th>
          </tr>
        </thead>
        <tbody>
          @forelse($entregas as $e)
            <tr>
              <td>
                <span style="font-size:.72rem; color:var(--vl-text-muted);">
                  {{ $entregas->firstItem() + $loop->index }}
                </span>
              </td>
              <td>
                @php $fecha = \Carbon\Carbon::parse($e->fecha_entrega); @endphp
                @if($fecha->isToday())
                  <span class="vl-badge vl-badge--green">Hoy</span>
                @elseif($fecha->isYesterday())
                  <span class="vl-badge vl-badge--blue">Ayer</span>
                @else
                  <span style="font-size:.8rem;">
                    {{ $fecha->format('d/m/Y') }}
                  </span>
                @endif
              </td>
              <td>
                <span style="font-size:.75rem; color:var(--vl-text-muted);">
                  {{ $e->hora_entrega ?? \Carbon\Carbon::parse($e->created_at)->format('H:i') }}
                </span>
              </td>
              <td>
                <div class="vl-table__cell-user">
                  <div class="vl-table__mini-avatar">
                    {{ strtoupper(substr($e->beneficiario->nombre ?? 'B', 0, 1)) }}
                  </div>
                  <div>
                    <span class="vl-table__cell-name">
                      {{ strtoupper($e->beneficiario->apellido ?? '') }},
                      {{ $e->beneficiario->nombre ?? '—' }}
                    </span>
                  </div>
                </div>
              </td>
              <td>
                <span style="font-family:var(--vl-font-mono); font-size:.78rem;">
                  {{ $e->beneficiario->dni ?? '—' }}
                </span>
              </td>
              <td>
                <span class="vl-badge vl-badge--blue">
                  {{ ucfirst($e->beneficiario->tipo_beneficiario ?? '—') }}
                </span>
              </td>
              <td>
                <span style="font-size:.78rem;">
                  {{ $e->producto->nombre ?? 'Ración general' }}
                </span>
              </td>
              <td>
                <span class="vl-badge vl-badge--green">
                  {{ $e->cantidad }}
                </span>
              </td>
              <td>
                <span style="font-size:.75rem; color:var(--vl-text-muted);">
                  {{ $e->beneficiario->sector_o_comite ?? '—' }}
                </span>
              </td>
              <td>
                <span style="font-size:.75rem; color:var(--vl-text-muted);">
                  {{ $e->user->name ?? 'Sistema' }}
                </span>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="10">
                <div class="vl-table__empty">
                  <i class="bi bi-journal-x"></i>
                  <p>No se encontraron entregas.</p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Paginación --}}
    @if($entregas->hasPages())
      <div class="p-3 border-top"
           style="border-color:var(--vl-border) !important;">
        <div class="d-flex align-items-center
                    justify-content-between flex-wrap gap-2">
          <span style="font-size:.75rem; color:var(--vl-text-muted);">
            Mostrando {{ $entregas->firstItem() }}–{{ $entregas->lastItem() }}
            de {{ $entregas->total() }} registros
          </span>
          {{ $entregas->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
      </div>
    @endif

  </div>
</div>

@endsection