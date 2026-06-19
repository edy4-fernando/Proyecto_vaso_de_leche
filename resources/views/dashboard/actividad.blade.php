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

{{-- ── Filtros avanzados ── --}}
<div class="vl-card mb-4 vl-animate">
  <div class="vl-card__body" style="padding:14px 20px;">
    <form method="GET" action="{{ route('dashboard.actividad') }}" id="formFiltrosActividad">

      {{-- Fila 1: búsqueda + fechas + botones --}}
      <div class="vl-search-bar" style="margin-bottom:10px;">
        <div class="vl-input-icon-wrap" style="flex:1; min-width:200px;">
          <i class="bi bi-search"></i>
          <input type="text" name="q" class="vl-input"
                 value="{{ request('q') }}"
                 placeholder="Buscar por acción o descripción…">
        </div>
        <div style="min-width:148px;">
          <input type="date" name="desde" class="vl-input"
                 value="{{ request('desde') }}" title="Desde">
        </div>
        <div style="min-width:148px;">
          <input type="date" name="hasta" class="vl-input"
                 value="{{ request('hasta') }}" title="Hasta">
        </div>
        <button type="submit" class="vl-btn vl-btn--primary vl-btn--sm">
          <i class="bi bi-funnel-fill"></i> Filtrar
        </button>
        @if(request()->hasAny(['q','accion','desde','hasta','operador_id']))
          <a href="{{ route('dashboard.actividad') }}"
             class="vl-btn vl-btn--ghost vl-btn--sm">
            <i class="bi bi-x-circle"></i> Limpiar
          </a>
        @endif
      </div>

      {{-- Fila 2: selectores + atajos --}}
      <div style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">

        <select name="accion" class="vl-select" style="width:auto; min-width:190px;">
          <option value="">Todas las acciones</option>
          <optgroup label="Beneficiarios">
            <option value="BENEFICIARIO_CREADO"       {{ request('accion') === 'BENEFICIARIO_CREADO'       ? 'selected' : '' }}>Creado</option>
            <option value="BENEFICIARIO_EDITADO"      {{ request('accion') === 'BENEFICIARIO_EDITADO'      ? 'selected' : '' }}>Editado</option>
            <option value="BENEFICIARIO_ELIMINADO"    {{ request('accion') === 'BENEFICIARIO_ELIMINADO'    ? 'selected' : '' }}>Eliminado</option>
            <option value="BENEFICIARIO_DADO_DE_BAJA" {{ request('accion') === 'BENEFICIARIO_DADO_DE_BAJA' ? 'selected' : '' }}>Dado de baja</option>
            <option value="BENEFICIARIO_REACTIVADO"   {{ request('accion') === 'BENEFICIARIO_REACTIVADO'   ? 'selected' : '' }}>Reactivado</option>
            <option value="BENEFICIARIO_RESTAURADO"   {{ request('accion') === 'BENEFICIARIO_RESTAURADO'   ? 'selected' : '' }}>Restaurado</option>
          </optgroup>
          <optgroup label="Entregas">
            <option value="ENTREGA_REGISTRADA"  {{ request('accion') === 'ENTREGA_REGISTRADA'  ? 'selected' : '' }}>Entrega registrada</option>
            <option value="ENTREGA_RESTAURADA"  {{ request('accion') === 'ENTREGA_RESTAURADA'  ? 'selected' : '' }}>Entrega restaurada</option>
          </optgroup>
          <optgroup label="Usuarios">
            <option value="USUARIO_CREADO"    {{ request('accion') === 'USUARIO_CREADO'    ? 'selected' : '' }}>Usuario creado</option>
            <option value="USUARIO_ELIMINADO" {{ request('accion') === 'USUARIO_ELIMINADO' ? 'selected' : '' }}>Usuario eliminado</option>
          </optgroup>
          <optgroup label="Inventario">
            <option value="STOCK_REABASTECIDO"  {{ request('accion') === 'STOCK_REABASTECIDO'  ? 'selected' : '' }}>Stock reabastecido</option>
            <option value="PRODUCTO_ELIMINADO"  {{ request('accion') === 'PRODUCTO_ELIMINADO'  ? 'selected' : '' }}>Producto eliminado</option>
            <option value="PRODUCTO_RESTAURADO" {{ request('accion') === 'PRODUCTO_RESTAURADO' ? 'selected' : '' }}>Producto restaurado</option>
          </optgroup>
        </select>

        <select name="operador_id" class="vl-select" style="width:auto; min-width:170px;">
          <option value="">Todos los operadores</option>
          @foreach($operadores as $op)
            <option value="{{ $op->id }}" {{ request('operador_id') == $op->id ? 'selected' : '' }}>
              {{ $op->name }}
            </option>
          @endforeach
        </select>

        {{-- Atajos de fecha --}}
        <div style="display:flex; gap:6px; margin-left:auto;">
          <button type="button" class="vl-btn vl-btn--ghost vl-btn--sm"
                  onclick="setActividadAtajo('hoy')">Hoy</button>
          <button type="button" class="vl-btn vl-btn--ghost vl-btn--sm"
                  onclick="setActividadAtajo('semana')">7 días</button>
          <button type="button" class="vl-btn vl-btn--ghost vl-btn--sm"
                  onclick="setActividadAtajo('mes')">Este mes</button>
        </div>

      </div>

      {{-- Etiquetas de filtros activos --}}
      @php
        $filtrosAct = array_filter([
          request('q')           ? 'Búsqueda: "' . request('q') . '"'                                        : null,
          request('desde')       ? 'Desde: ' . request('desde')                                               : null,
          request('hasta')       ? 'Hasta: ' . request('hasta')                                               : null,
          request('accion')      ? 'Acción: ' . request('accion')                                             : null,
          request('operador_id') ? 'Operador: ' . ($operadores->find(request('operador_id'))?->name ?? '—')  : null,
        ]);
      @endphp
      @if(count($filtrosAct))
        <div style="display:flex; gap:6px; flex-wrap:wrap; margin-top:10px;">
          @foreach($filtrosAct as $etiqueta)
            <span class="vl-badge vl-badge--blue"
                  style="font-size:.7rem; display:inline-flex; align-items:center; gap:4px;">
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
            <th style="width:80px;">Detalles</th>
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

              {{-- Detalles --}}
              <td>
                <button type="button"
                        class="vl-btn vl-btn--ghost vl-btn--icon"
                        onclick="vlVerDetalleLog(
                          '{{ addslashes($log->user->name ?? 'Sistema') }}',
                          '{{ ucfirst($log->user->rol ?? '—') }}',
                          '{{ $log->ip ?? '—' }}',
                          '{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i:s') }}',
                          '{{ addslashes($log->accion) }}',
                          '{{ addslashes($log->descripcion ?? '') }}',
                          '{{ addslashes(json_encode($log->metadata)) }}'
                        )"
                        data-bs-toggle="tooltip" title="Ver detalles">
                  <i class="bi bi-eye-fill"></i>
                </button>
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

@push('scripts')
<script>
function vlVerDetalleLog(nombre, rol, ip, fecha, accion, descripcion, metadataJson) {
  document.getElementById('modalLogNombre').textContent = nombre;
  document.getElementById('modalLogRol').textContent    = rol;
  document.getElementById('modalLogIp').textContent     = ip;
  document.getElementById('modalLogFecha').textContent  = fecha;
  document.getElementById('modalLogAccion').textContent = accion;
  document.getElementById('modalLogDesc').textContent   = descripcion;

  const cambiosBox = document.getElementById('modalLogCambios');
  const cambiosSec = document.getElementById('modalLogCambiosSec');
  cambiosBox.innerHTML = '';

  let metadata = null;
  try { metadata = JSON.parse(metadataJson); } catch(e) {}

  if (metadata && metadata.cambios && metadata.cambios.length > 0) {
    cambiosSec.style.display = 'block';
    metadata.cambios.forEach(c => {
      const fila = document.createElement('div');
      fila.style.cssText = 'display:grid; grid-template-columns:110px 1fr 1fr; gap:8px; padding:7px 0; border-bottom:1px solid var(--vl-border); font-size:.78rem;';
      fila.innerHTML = `
        <span style="color:var(--vl-text-muted); font-weight:600;">${c.campo}</span>
        <span style="color:var(--vl-rose); text-decoration:line-through; word-break:break-word;">${c.antes}</span>
        <span style="color:var(--vl-emerald); word-break:break-word;">${c.despues}</span>
      `;
      cambiosBox.appendChild(fila);
    });
  } else {
    cambiosSec.style.display = 'none';
  }

  new bootstrap.Modal(document.getElementById('modalDetalleLog')).show();
}

function setActividadAtajo(periodo) {
  const hoy    = new Date();
  const pad    = n => String(n).padStart(2, '0');
  const fmt    = d => `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}`;
  const form   = document.getElementById('formFiltrosActividad');
  const desde  = form.querySelector('[name="desde"]');
  const hasta  = form.querySelector('[name="hasta"]');
  const hoyStr = fmt(hoy);
  hasta.value  = hoyStr;
  if (periodo === 'hoy') {
    desde.value = hoyStr;
  } else if (periodo === 'semana') {
    const d = new Date(hoy); d.setDate(d.getDate() - 6);
    desde.value = fmt(d);
  } else if (periodo === 'mes') {
    desde.value = `${hoy.getFullYear()}-${pad(hoy.getMonth()+1)}-01`;
  }
  form.submit();
}
</script>

{{-- Modal detalle de log --}}
<div class="modal fade" id="modalDetalleLog" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background:var(--vl-bg-card); border:1px solid var(--vl-border);">
      <div class="modal-header" style="border-color:var(--vl-border);">
        <h5 class="modal-title" style="font-size:.95rem; font-weight:700; color:var(--vl-text-main);">
          <i class="bi bi-journal-text me-2 text-primary"></i>Detalle de Acción
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" style="padding:20px;">
        @php
          $filas = [
            ['bi-person-fill',         'Operador',    'modalLogNombre'],
            ['bi-shield-fill',         'Rol',         'modalLogRol'],
            ['bi-calendar-event-fill', 'Fecha / Hora','modalLogFecha'],
            ['bi-tag-fill',            'Acción',      'modalLogAccion'],
            ['bi-wifi',                'IP',          'modalLogIp'],
            ['bi-card-text',           'Descripción', 'modalLogDesc'],
          ];
        @endphp
        @foreach($filas as [$icon, $label, $id])
          <div style="display:flex; gap:12px; padding:10px 0;
                      border-bottom:1px solid var(--vl-border);">
            <i class="bi {{ $icon }}"
               style="color:var(--vl-blue-600); font-size:.85rem; margin-top:2px; flex-shrink:0;"></i>
            <div>
              <div style="font-size:.68rem; color:var(--vl-text-muted);
                          text-transform:uppercase; letter-spacing:.5px;">{{ $label }}</div>
              <div id="{{ $id }}"
                   style="font-size:.85rem; color:var(--vl-text-main);
                          font-weight:500; margin-top:2px; word-break:break-word;"></div>
            </div>
          </div>
        @endforeach

        {{-- Sección de cambios (solo aparece si hay metadata) --}}
        <div id="modalLogCambiosSec" style="display:none; margin-top:14px;">
          <div style="font-size:.68rem; color:var(--vl-text-muted);
                      text-transform:uppercase; letter-spacing:.5px; margin-bottom:8px;">
            <i class="bi bi-arrows-collapse me-1" style="color:var(--vl-blue-600);"></i>
            Campos modificados
          </div>
          {{-- Encabezado de columnas --}}
          <div style="display:grid; grid-template-columns:110px 1fr 1fr; gap:8px;
                      padding:5px 0; font-size:.67rem; font-weight:700;
                      color:var(--vl-text-muted); text-transform:uppercase; letter-spacing:.4px;">
            <span>Campo</span>
            <span style="color:var(--vl-rose);">Antes</span>
            <span style="color:var(--vl-emerald);">Después</span>
          </div>
          <div id="modalLogCambios"></div>
        </div>
      </div>
      <div class="modal-footer" style="border-color:var(--vl-border);">
        <button type="button" class="vl-btn vl-btn--outline vl-btn--sm"
                data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
@endpush