@extends('layouts.admin')

@section('title', 'Registrar Entrega — Vaso de Leche')
@section('breadcrumb', 'Registrar Entrega')
@php $activeModule = 'dashboard'; @endphp

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
        <div class="vl-stat__value">{{ \Carbon\Carbon::now()->format('d/m') }}</div>
        <div class="vl-stat__label">{{ \Carbon\Carbon::now()->isoFormat('dddd') }}</div>
      </div>
    </div>
  </div>
</div>

{{-- ── DOS COLUMNAS ── --}}
<div class="row g-4">

  {{-- Formulario de entrega --}}
  <div class="col-lg-4 vl-animate">
    <div class="vl-card h-100">
      <div class="vl-card__accent"></div>
      <div class="vl-card__header">
        <h5 class="vl-card__title">
          <i class="bi bi-plus-circle-fill text-primary"></i>
          Registrar Entrega
        </h5>
      </div>
      <div class="vl-card__body">
        <form action="{{ route('admin.entregas.guardar') }}" method="POST">
          @csrf

          <div class="vl-form-group">
            <label class="vl-label">DNI del Beneficiario <span class="req">*</span></label>
            <div class="vl-input-icon-wrap">
              <i class="bi bi-search"></i>
              <input type="text" name="dni" class="vl-input"
                     placeholder="Ej: 12345678"
                     maxlength="8" pattern="\d{8}"
                     required autocomplete="off"
                     id="dniInput">
            </div>
            <div id="dniPreview"
                 style="margin-top:6px; font-size:.75rem;
                        color:var(--vl-emerald); display:none;">
              <i class="bi bi-person-check-fill me-1"></i>
              <span id="dniNombre"></span>
            </div>
          </div>

          <div class="vl-form-group">
            <label class="vl-label">Producto entregado</label>
            <select name="producto_id" class="vl-select">
              <option value="">— Ración general —</option>
              @foreach($productos ?? [] as $prod)
                <option value="{{ $prod->id }}"
                  {{ $prod->stock_actual <= 0 ? 'disabled' : '' }}>
                  {{ $prod->nombre }}
                  @if($prod->marca) — {{ $prod->marca }} @endif
                  (Stock: {{ $prod->stock_actual }})
                </option>
              @endforeach
            </select>
          </div>

          <div class="vl-form-group">
            <label class="vl-label">Cantidad <span class="req">*</span></label>
            <input type="number" name="cantidad" class="vl-input"
                   value="1" min="1" max="99" required>
          </div>

          <div class="vl-form-group">
            <label class="vl-label">Fecha de entrega <span class="req">*</span></label>
            <input type="date" name="fecha_entrega" class="vl-input"
                   value="{{ date('Y-m-d') }}" required>
          </div>

          <div class="vl-form-group">
            <label class="vl-label">Observaciones</label>
            <textarea name="observaciones_incidencias"
                      class="vl-textarea"
                      placeholder="Opcional…"
                      rows="3" maxlength="255"
                      data-charcount="charCount"></textarea>
            <div style="text-align:right; margin-top:3px;">
              <span id="charCount"
                    style="font-size:.68rem; color:var(--vl-text-muted);">
                0 / 255
              </span>
            </div>
          </div>

          <button type="submit" class="vl-btn vl-btn--primary w-100">
            <i class="bi bi-check-lg"></i>
            Confirmar entrega
          </button>
        </form>
      </div>
    </div>
  </div>

  {{-- Tabla entregas del día --}}
  <div class="col-lg-8 vl-animate vl-animate--delay-1">
    <div class="vl-card h-100">
      <div class="vl-card__accent"></div>
      <div class="vl-card__header">
        <h5 class="vl-card__title">
          <i class="bi bi-list-check text-primary"></i>
          Entregas de Hoy
        </h5>
        <div class="d-flex align-items-center gap-2">
          <span class="vl-badge vl-badge--blue">
            {{ $entregasHoy->count() }} registros
          </span>
          <span style="font-size:.7rem; color:var(--vl-text-muted);">
            <i class="bi bi-arrow-repeat me-1"></i>Auto 30s
          </span>
        </div>
      </div>

      <div style="padding:12px 20px 0;">
        <div class="vl-input-icon-wrap">
          <i class="bi bi-search"></i>
          <input type="text" class="vl-input"
                 id="vlTableSearch"
                 data-table="tblEntregasHoy"
                 data-cols="0,1,2"
                 placeholder="Buscar por DNI o nombre…">
        </div>
      </div>

      <div class="vl-card__body" style="padding-top:12px;">
        <div class="vl-table-wrap">
          <table class="vl-table" id="tblEntregasHoy">
            <thead>
              <tr>
                <th>Hora</th>
                <th>Beneficiario</th>
                <th>DNI</th>
                <th>Producto</th>
                <th>Cant.</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @forelse($entregasHoy as $e)
                <tr>
                  <td>
                    <span style="font-size:.75rem; color:var(--vl-text-muted);">
                      <i class="bi bi-clock me-1"></i>
                      {{ $e->hora_entrega ?? \Carbon\Carbon::parse($e->created_at)->format('H:i') }}
                    </span>
                  </td>
                  <td>
                    <div class="vl-table__cell-user">
                      <div class="vl-table__mini-avatar">
                        {{ strtoupper(substr($e->beneficiario->nombre ?? 'B', 0, 1)) }}
                      </div>
                      <span class="vl-table__cell-name">
                        {{ strtoupper($e->beneficiario->apellido ?? '') }},
                        {{ $e->beneficiario->nombre ?? '—' }}
                      </span>
                    </div>
                  </td>
                  <td>
                    <span style="font-family:var(--vl-font-mono); font-size:.78rem;">
                      {{ $e->beneficiario->dni ?? '—' }}
                    </span>
                  </td>
                  <td>
                    <span style="font-size:.78rem;">
                      {{ $e->producto->nombre ?? 'Ración general' }}
                    </span>
                  </td>
                  <td>
                    <span class="vl-badge vl-badge--blue">{{ $e->cantidad }}</span>
                  </td>
                  <td>
                    <button type="button"
                            class="vl-btn vl-btn--ghost vl-btn--sm"
                            style="font-size:.72rem;"
                            onclick="vlVerEntrega(
                              '{{ strtoupper($e->beneficiario->apellido ?? '') }}, {{ $e->beneficiario->nombre ?? '—' }}',
                              '{{ $e->beneficiario->dni ?? '—' }}',
                              '{{ $e->fecha_entrega }}',
                              '{{ $e->hora_entrega ?? '' }}',
                              '{{ $e->producto->nombre ?? 'Ración general' }}',
                              '{{ $e->cantidad }}',
                              '{{ $e->user->name ?? '—' }}',
                              '{{ addslashes($e->observaciones_incidencias ?? '') }}'
                            )">
                      <i class="bi bi-eye-fill me-1"></i>Detalles
                    </button>
                  </td>
                </tr>
              @empty
                <tr id="vlTableEmpty">
                  <td colspan="6">
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
  </div>

</div>

@endsection

@section('modals')
<div class="modal fade vl-modal" id="modalDetalleEntrega" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="bi bi-journal-check text-primary me-2"></i>
          Detalle de Entrega
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div style="display:flex; flex-direction:column; gap:10px;">
          <div style="display:flex; justify-content:space-between; padding:10px 14px;
                      background:var(--vl-bg-body); border-radius:var(--vl-radius);">
            <span style="font-size:.75rem; color:var(--vl-text-muted);">Beneficiario</span>
            <span style="font-size:.8rem; font-weight:700;" id="de-beneficiario"></span>
          </div>
          <div style="display:flex; justify-content:space-between; padding:10px 14px;
                      background:var(--vl-bg-body); border-radius:var(--vl-radius);">
            <span style="font-size:.75rem; color:var(--vl-text-muted);">DNI</span>
            <span style="font-size:.8rem; font-weight:700;" id="de-dni"></span>
          </div>
          <div style="display:flex; justify-content:space-between; padding:10px 14px;
                      background:var(--vl-bg-body); border-radius:var(--vl-radius);">
            <span style="font-size:.75rem; color:var(--vl-text-muted);">Fecha</span>
            <span style="font-size:.8rem; font-weight:700;" id="de-fecha"></span>
          </div>
          <div style="display:flex; justify-content:space-between; padding:10px 14px;
                      background:var(--vl-bg-body); border-radius:var(--vl-radius);">
            <span style="font-size:.75rem; color:var(--vl-text-muted);">Hora</span>
            <span style="font-size:.8rem; font-weight:700;" id="de-hora"></span>
          </div>
          <div style="display:flex; justify-content:space-between; padding:10px 14px;
                      background:var(--vl-bg-body); border-radius:var(--vl-radius);">
            <span style="font-size:.75rem; color:var(--vl-text-muted);">Producto</span>
            <span style="font-size:.8rem; font-weight:700;" id="de-producto"></span>
          </div>
          <div style="display:flex; justify-content:space-between; padding:10px 14px;
                      background:var(--vl-bg-body); border-radius:var(--vl-radius);">
            <span style="font-size:.75rem; color:var(--vl-text-muted);">Cantidad</span>
            <span style="font-size:.8rem; font-weight:700;" id="de-cantidad"></span>
          </div>
          <div style="display:flex; justify-content:space-between; padding:10px 14px;
                      background:var(--vl-bg-body); border-radius:var(--vl-radius);">
            <span style="font-size:.75rem; color:var(--vl-text-muted);">Operador</span>
            <span style="font-size:.8rem; font-weight:700;" id="de-operador"></span>
          </div>
          <div id="de-obs-wrap" style="padding:10px 14px;
                      background:var(--vl-bg-body); border-radius:var(--vl-radius);">
            <span style="font-size:.75rem; color:var(--vl-text-muted); display:block; margin-bottom:4px;">Observaciones</span>
            <span style="font-size:.8rem;" id="de-obs"></span>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="vl-btn vl-btn--outline" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
function vlVerEntrega(beneficiario, dni, fecha, hora, producto, cantidad, operador, obs) {
  document.getElementById('de-beneficiario').textContent = beneficiario;
  document.getElementById('de-dni').textContent          = dni;
  document.getElementById('de-fecha').textContent        = fecha;
  document.getElementById('de-hora').textContent         = hora || '—';
  document.getElementById('de-producto').textContent     = producto;
  document.getElementById('de-cantidad').textContent     = cantidad;
  document.getElementById('de-operador').textContent     = operador;
  const obsWrap = document.getElementById('de-obs-wrap');
  document.getElementById('de-obs').textContent = obs;
  obsWrap.style.display = obs ? '' : 'none';
  new bootstrap.Modal(document.getElementById('modalDetalleEntrega')).show();
}
</script>
@endpush