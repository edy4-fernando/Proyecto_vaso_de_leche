{{-- ============================================================
     admin/entregas.blade.php
     Ruta: GET /admin/entregas → AdminController@historialEntregas
     Variables: $entregas (LengthAwarePaginator)
     ============================================================ --}}

@extends('layouts.admin')

@section('title', 'Historial de Entregas — Vaso de Leche')
@section('breadcrumb', 'Historial de Entregas')
@php $activeModule = 'entregas'; @endphp

@section('content')

{{-- ── Encabezado ── --}}
<div class="vl-section-header">
  <div>
    <h2 class="vl-section-title">
      <i class="bi bi-journal-check text-primary"></i>
      Historial de Entregas
    </h2>
    <p class="vl-section-sub">
      Registro completo de todas las raciones despachadas en el programa
    </p>
  </div>
  <button class="vl-btn vl-btn--outline vl-btn--sm"
          onclick="window.print()">
    <i class="bi bi-printer-fill"></i>
    Imprimir
  </button>
</div>

{{-- ── Stat cards ── --}}
@php
  $totalGeneral  = $entregas->total();
  $hoy           = $entregas->filter(fn($e) =>
    \Carbon\Carbon::parse($e->fecha_entrega)->isToday()
  )->count();
  $esteMes       = $entregas->filter(fn($e) =>
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
        <div class="vl-stat__label">Hoy</div>
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
        <div class="vl-stat__label">Este mes</div>
      </div>
    </div>
  </div>
</div>
{{-- ── Filtros avanzados ── --}}
<div class="vl-card mb-4 vl-animate">
  <div class="vl-card__body" style="padding: 14px 20px;">
    <form method="GET" action="{{ route('admin.entregas') }}" id="formFiltrosEntregas">

      {{-- Fila 1: búsqueda + fechas --}}
      <div class="vl-search-bar" style="margin-bottom: 10px;">
        <div class="vl-input-icon-wrap" style="flex: 1; min-width: 200px;">
          <i class="bi bi-search"></i>
          <input type="text" name="q" class="vl-input"
                 value="{{ request('q') }}"
                 placeholder="Buscar por nombre o DNI…">
        </div>
        <div style="min-width: 148px;">
          <input type="date" name="desde" class="vl-input"
                 value="{{ request('desde') }}" title="Desde">
        </div>
        <div style="min-width: 148px;">
          <input type="date" name="hasta" class="vl-input"
                 value="{{ request('hasta') }}" title="Hasta">
        </div>
        <button type="submit" class="vl-btn vl-btn--primary vl-btn--sm">
          <i class="bi bi-funnel-fill"></i> Filtrar
        </button>
        @if(request()->hasAny(['q','desde','hasta','producto_id','tipo_beneficiario','operador_id']))
          <a href="{{ route('admin.entregas') }}" class="vl-btn vl-btn--ghost vl-btn--sm">
            <i class="bi bi-x-circle"></i> Limpiar
          </a>
        @endif
      </div>

      {{-- Fila 2: selectores avanzados --}}
      <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">

        <select name="producto_id" class="vl-select" style="width: auto; min-width: 190px;">
          <option value="">Todos los productos</option>
          <option value="general" {{ request('producto_id') === 'general' ? 'selected' : '' }}>
            — Ración general (sin producto)
          </option>
          @foreach($productos as $prod)
            <option value="{{ $prod->id }}" {{ request('producto_id') == $prod->id ? 'selected' : '' }}>
              {{ $prod->nombre }}@if($prod->marca) — {{ $prod->marca }}@endif
            </option>
          @endforeach
        </select>

        <select name="tipo_beneficiario" class="vl-select" style="width: auto; min-width: 180px;">
          <option value="">Todos los tipos</option>
          <optgroup label="Primera Prioridad">
            <option value="niño 0-6"  {{ request('tipo_beneficiario') === 'niño 0-6'  ? 'selected' : '' }}>Niño 0-6 años</option>
            <option value="gestante"  {{ request('tipo_beneficiario') === 'gestante'  ? 'selected' : '' }}>Gestante</option>
            <option value="lactante"  {{ request('tipo_beneficiario') === 'lactante'  ? 'selected' : '' }}>Lactante</option>
          </optgroup>
          <optgroup label="Segunda Prioridad">
            <option value="niño 7-13"     {{ request('tipo_beneficiario') === 'niño 7-13'     ? 'selected' : '' }}>Niño 7-13 años</option>
            <option value="adulto mayor"  {{ request('tipo_beneficiario') === 'adulto mayor'  ? 'selected' : '' }}>Adulto mayor</option>
            <option value="discapacitado" {{ request('tipo_beneficiario') === 'discapacitado' ? 'selected' : '' }}>Discapacitado</option>
            <option value="tbc"           {{ request('tipo_beneficiario') === 'tbc'           ? 'selected' : '' }}>TBC</option>
          </optgroup>
        </select>

        <select name="operador_id" class="vl-select" style="width: auto; min-width: 170px;">
          <option value="">Todos los operadores</option>
          @foreach($operadores as $op)
            <option value="{{ $op->id }}" {{ request('operador_id') == $op->id ? 'selected' : '' }}>
              {{ $op->name }}
            </option>
          @endforeach
        </select>

        {{-- Atajos de fecha --}}
        <div style="display: flex; gap: 6px; margin-left: auto;">
          <button type="button" class="vl-btn vl-btn--ghost vl-btn--sm" onclick="setFechaAtajo('hoy')">Hoy</button>
          <button type="button" class="vl-btn vl-btn--ghost vl-btn--sm" onclick="setFechaAtajo('semana')">7 días</button>
          <button type="button" class="vl-btn vl-btn--ghost vl-btn--sm" onclick="setFechaAtajo('mes')">Este mes</button>
        </div>

      </div>

      {{-- Etiquetas de filtros activos (visibles también al imprimir) --}}
      @php
        $filtrosActivos = array_filter([
          request('q')                 ? 'Búsqueda: "' . request('q') . '"'                                           : null,
          request('desde')             ? 'Desde: ' . request('desde')                                                  : null,
          request('hasta')             ? 'Hasta: ' . request('hasta')                                                  : null,
          request('tipo_beneficiario') ? 'Tipo: ' . request('tipo_beneficiario')                                       : null,
          request('operador_id')       ? 'Operador: ' . ($operadores->find(request('operador_id'))?->name ?? '—')      : null,
          request('producto_id') === 'general'
            ? 'Ración: General'
            : (request('producto_id') ? 'Producto: ' . ($productos->find(request('producto_id'))?->nombre ?? '—') : null),
        ]);
      @endphp
      @if(count($filtrosActivos))
        <div style="display: flex; gap: 6px; flex-wrap: wrap; margin-top: 10px;">
          @foreach($filtrosActivos as $etiqueta)
            <span class="vl-badge vl-badge--blue"
                  style="font-size: .7rem; display: inline-flex; align-items: center; gap: 4px;">
              <i class="bi bi-funnel-fill"></i>{{ $etiqueta }}
            </span>
          @endforeach
        </div>
      @endif

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
  <div class="vl-card__body" style="padding: 0;">
    <div class="vl-table-wrap">
      <table class="vl-table" id="tblEntregas">
        <thead>
          <tr>
            <th>#</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Beneficiario</th>
            <th>DNI</th>
            <th>Producto</th>
            <th>Cant.</th>
            <th>Operador</th>
            <th>Observaciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($entregas as $e)
            <tr>

              {{-- Número --}}
              <td>
                <span style="font-size: .72rem; color: var(--vl-text-muted);">
                  {{ $entregas->firstItem() + $loop->index }}
                </span>
              </td>

              {{-- Fecha --}}
              <td>
                <span style="font-size: .8rem; white-space: nowrap;">
                  @php
                    $fecha = \Carbon\Carbon::parse($e->fecha_entrega);
                  @endphp
                  @if($fecha->isToday())
                    <span class="vl-badge vl-badge--green">Hoy</span>
                  @elseif($fecha->isYesterday())
                    <span class="vl-badge vl-badge--blue">Ayer</span>
                  @else
                    {{ $fecha->format('d/m/Y') }}
                  @endif
                </span>
              </td>

              {{-- Hora --}}
              <td>
                <span style="font-size: .75rem; color: var(--vl-text-muted);">
                  {{ $e->hora_entrega ?? \Carbon\Carbon::parse($e->created_at)->format('H:i') }}
                </span>
              </td>

              {{-- Beneficiario --}}
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
                    @if($e->beneficiario)
                      <a href="{{ route('admin.perfil', $e->beneficiario->id) }}"
                         class="vl-table__cell-sub"
                         style="color: var(--vl-blue-600); text-decoration: none;">
                        Ver perfil →
                      </a>
                    @endif
                  </div>
                </div>
              </td>

              {{-- DNI --}}
              <td>
                <span style="font-family: var(--vl-font-mono); font-size: .78rem;">
                  {{ $e->beneficiario->dni ?? '—' }}
                </span>
              </td>

              {{-- Producto --}}
              <td>
                <span style="font-size: .78rem;">
                  {{ $e->producto->nombre ?? 'Ración general' }}
                  @if($e->producto?->marca)
                    <span style="color: var(--vl-text-muted); font-size: .7rem;">
                      — {{ $e->producto->marca }}
                    </span>
                  @endif
                </span>
              </td>

              {{-- Cantidad --}}
              <td>
                <span class="vl-badge vl-badge--blue">{{ $e->cantidad }}</span>
              </td>

              {{-- Operador --}}
              <td>
                <span style="font-size: .75rem; color: var(--vl-text-muted);">
                  {{ $e->user->name ?? 'Sistema' }}
                </span>
              </td>

              {{-- Observaciones --}}
              <td>
                @if($e->observaciones_incidencias)
                  <span class="vl-badge vl-badge--amber"
                        data-bs-toggle="tooltip"
                        data-bs-placement="left"
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
              <td colspan="9">
                <div class="vl-table__empty">
                  <i class="bi bi-journal-x"></i>
                  <p>No se encontraron entregas con los filtros seleccionados.</p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Paginación --}}
    @if($entregas->hasPages())
      <div class="p-3 border-top" style="border-color: var(--vl-border) !important;">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
          <span style="font-size: .75rem; color: var(--vl-text-muted);">
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

@push('scripts')
<script>
function setFechaAtajo(periodo) {
  const hoy    = new Date();
  const pad    = n => String(n).padStart(2, '0');
  const fmt    = d => `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}`;
  const form   = document.getElementById('formFiltrosEntregas');
  const desde  = form.querySelector('[name="desde"]');
  const hasta  = form.querySelector('[name="hasta"]');
  const hoyStr = fmt(hoy);

  hasta.value = hoyStr;

  if (periodo === 'hoy') {
    desde.value = hoyStr;
  } else if (periodo === 'semana') {
    const d = new Date(hoy);
    d.setDate(d.getDate() - 6);
    desde.value = fmt(d);
  } else if (periodo === 'mes') {
    desde.value = `${hoy.getFullYear()}-${pad(hoy.getMonth()+1)}-01`;
  }

  form.submit();
}
</script>
@endpush