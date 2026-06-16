@extends('layouts.dashboard')

@section('title', 'Monitoreo de Hoy — Vaso de Leche')
@section('breadcrumb', 'Monitoreo de Hoy')
@php $activeModule = 'monitoreo'; @endphp

@section('meta-refresh')
  <meta http-equiv="refresh" content="30">
@endsection

@section('content')

{{-- ── STAT CARDS ── --}}
<div class="row g-3 mb-4">

  <div class="col-6 col-lg-3 vl-animate">
    <div class="vl-stat">
      <div class="vl-stat__icon vl-stat__icon--blue">
        <i class="bi bi-box-arrow-in-down-right"></i>
      </div>
      <div class="vl-stat__body">
        <div class="vl-stat__value">{{ $totalHoy ?? 0 }}</div>
        <div class="vl-stat__label">Entregas hoy</div>
      </div>
    </div>
  </div>

  <div class="col-6 col-lg-3 vl-animate vl-animate--delay-1">
    <div class="vl-stat">
      <div class="vl-stat__icon vl-stat__icon--green">
        <i class="bi bi-people-fill"></i>
      </div>
      <div class="vl-stat__body">
        <div class="vl-stat__value">{{ $totalBeneficiarios ?? 0 }}</div>
        <div class="vl-stat__label">Beneficiarios activos</div>
      </div>
    </div>
  </div>

  <div class="col-6 col-lg-3 vl-animate vl-animate--delay-2">
    <div class="vl-stat">
      <div class="vl-stat__icon vl-stat__icon--amber">
        <i class="bi bi-box-seam"></i>
      </div>
      <div class="vl-stat__body">
        <div class="vl-stat__value">{{ $stockCritico ?? 0 }}</div>
        <div class="vl-stat__label">Stock crítico</div>
      </div>
    </div>
  </div>

  <div class="col-6 col-lg-3 vl-animate vl-animate--delay-3">
    <div class="vl-stat">
      <div class="vl-stat__icon vl-stat__icon--violet">
        <i class="bi bi-calendar-check"></i>
      </div>
      <div class="vl-stat__body">
        <div class="vl-stat__value">
          {{ \Carbon\Carbon::now()->format('d/m') }}
        </div>
        <div class="vl-stat__label">
          {{ \Carbon\Carbon::now()->isoFormat('dddd') }}
        </div>
      </div>
    </div>
  </div>

</div>

{{-- ── TABLA DE MONITOREO ── --}}
<div class="vl-card vl-animate vl-animate--delay-1">
  <div class="vl-card__accent"></div>
  <div class="vl-card__header">
    <h5 class="vl-card__title">
      <i class="bi bi-list-check text-primary"></i>
      Entregas Registradas Hoy
    </h5>
    <div class="d-flex align-items-center gap-2">
      <span class="vl-badge vl-badge--blue">
        {{ $entregasHoy->count() }} registros
      </span>
      <span style="font-size:.7rem; color:var(--vl-text-muted);">
        <i class="bi bi-arrow-repeat me-1"></i>Auto cada 30s
      </span>
    </div>
  </div>

  {{-- Buscador --}}
  <div style="padding:12px 20px 0;">
    <div class="vl-input-icon-wrap">
      <i class="bi bi-search"></i>
      <input type="text"
             class="vl-input"
             id="vlTableSearch"
             data-table="tblMonitoreo"
             data-cols="0,1,2,3"
             placeholder="Buscar por DNI, nombre u operador…">
    </div>
  </div>

  <div class="vl-card__body" style="padding-top:12px;">
    <div class="vl-table-wrap">
      <table class="vl-table" id="tblMonitoreo">
        <thead>
          <tr>
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
          @forelse($entregasHoy as $e)
            <tr>
              <td>
                <span style="font-size:.75rem; color:var(--vl-text-muted);
                             white-space:nowrap;">
                  <i class="bi bi-clock me-1"></i>
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
                <div class="vl-table__cell-user">
                  <div class="vl-table__mini-avatar"
                       style="width:24px; height:24px; font-size:.6rem;">
                    {{ strtoupper(substr($e->user->name ?? 'S', 0, 1)) }}
                  </div>
                  <span style="font-size:.75rem; color:var(--vl-text-muted);">
                    {{ $e->user->name ?? 'Sistema' }}
                  </span>
                </div>
              </td>
            </tr>
          @empty
            <tr id="vlTableEmpty">
              <td colspan="8">
                <div class="vl-table__empty">
                  <i class="bi bi-inbox"></i>
                  <p>No se han registrado entregas hoy.</p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection