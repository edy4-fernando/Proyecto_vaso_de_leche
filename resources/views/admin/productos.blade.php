{{-- ============================================================
     admin/productos.blade.php
     Ruta: GET /admin/productos → AdminController@listaProductos
     Variables: $productos (Collection)
     ============================================================ --}}

@extends('layouts.admin')

@section('title', 'Inventario — Vaso de Leche')
@section('breadcrumb', 'Inventario de Productos')
@php $activeModule = 'productos'; @endphp

@section('content')

{{-- ── Encabezado ── --}}
<div class="vl-section-header">
  <div>
    <h2 class="vl-section-title">
      <i class="bi bi-box-seam text-primary"></i>
      Inventario de Productos
    </h2>
    <p class="vl-section-sub">
      {{ $productos->count() }} productos registrados
    </p>
  </div>
  @if(auth()->user()->rol === 'maestro')
    <button class="vl-btn vl-btn--primary vl-btn--sm"
            data-bs-toggle="modal"
            data-bs-target="#modalNuevoProducto">
      <i class="bi bi-plus-circle-fill"></i>
      Nuevo Producto
    </button>
  @endif
</div>

{{-- ── Stat cards stock ── --}}
@php
  $criticos   = $productos->filter(fn($p) => $p->stock_actual <= $p->stock_minimo)->count();
  $porVencer  = $productos->filter(fn($p) =>
    $p->fecha_vencimiento &&
    \Carbon\Carbon::parse($p->fecha_vencimiento)->between(now(), now()->addDays(30))
  )->count();
  $vencidos   = $productos->filter(fn($p) =>
    $p->fecha_vencimiento &&
    \Carbon\Carbon::parse($p->fecha_vencimiento)->isPast()
  )->count();
@endphp

<div class="row g-3 mb-4">
  <div class="col-6 col-lg-3 vl-animate">
    <div class="vl-stat">
      <div class="vl-stat__icon vl-stat__icon--blue">
        <i class="bi bi-box-seam"></i>
      </div>
      <div class="vl-stat__body">
        <div class="vl-stat__value">{{ $productos->count() }}</div>
        <div class="vl-stat__label">Total productos</div>
      </div>
    </div>
  </div>
  <div class="col-6 col-lg-3 vl-animate vl-animate--delay-1">
    <div class="vl-stat">
      <div class="vl-stat__icon vl-stat__icon--rose">
        <i class="bi bi-exclamation-triangle-fill"></i>
      </div>
      <div class="vl-stat__body">
        <div class="vl-stat__value">{{ $criticos }}</div>
        <div class="vl-stat__label">Stock crítico</div>
      </div>
    </div>
  </div>
  <div class="col-6 col-lg-3 vl-animate vl-animate--delay-2">
    <div class="vl-stat">
      <div class="vl-stat__icon vl-stat__icon--amber">
        <i class="bi bi-calendar-x-fill"></i>
      </div>
      <div class="vl-stat__body">
        <div class="vl-stat__value">{{ $porVencer }}</div>
        <div class="vl-stat__label">Por vencer (30d)</div>
      </div>
    </div>
  </div>
  <div class="col-6 col-lg-3 vl-animate vl-animate--delay-3">
    <div class="vl-stat">
      <div class="vl-stat__icon vl-stat__icon--slate">
        <i class="bi bi-slash-circle-fill"></i>
      </div>
      <div class="vl-stat__body">
        <div class="vl-stat__value">{{ $vencidos }}</div>
        <div class="vl-stat__label">Vencidos</div>
      </div>
    </div>
  </div>
</div>

{{-- ── Buscador ── --}}
<div class="vl-card mb-4 vl-animate">
  <div class="vl-card__body" style="padding: 14px 20px;">
    <div class="vl-search-bar">
      <div class="vl-input-icon-wrap" style="flex: 1; min-width: 220px;">
        <i class="bi bi-search"></i>
        <input type="text"
               class="vl-input"
               id="vlTableSearch"
               data-table="tblProductos"
               data-cols="0,1,2"
               placeholder="Buscar por nombre, marca o lote…">
      </div>
      <select class="vl-select"
              id="vlSelectFilter"
              data-table="tblProductos"
              data-col="6"
              style="width: auto; min-width: 150px;">
        <option value="">Todos los estados</option>
        <option value="crítico">Stock crítico</option>
        <option value="vencido">Vencido</option>
        <option value="ok">Normal</option>
      </select>
    </div>
  </div>
</div>

{{-- ── Tabla ── --}}
<div class="vl-card vl-animate vl-animate--delay-1">
  <div class="vl-card__accent"></div>
  <div class="vl-card__body" style="padding: 0;">
    <div class="vl-table-wrap">
      <table class="vl-table" id="tblProductos">
        <thead>
          <tr>
            <th>Nombre / Marca</th>
            <th>Tipo</th>
            <th>Lote</th>
            <th>Vencimiento</th>
            <th>Stock actual</th>
            <th>Stock mínimo</th>
            <th>Estado</th>
            @if(auth()->user()->rol === 'maestro')
              <th>Acciones</th>
            @endif
          </tr>
        </thead>
        <tbody>
          @forelse($productos as $p)
            @php
              $esVencido  = $p->fecha_vencimiento && \Carbon\Carbon::parse($p->fecha_vencimiento)->isPast();
              $esCritico  = $p->stock_actual <= $p->stock_minimo;
              $porVencerP = $p->fecha_vencimiento &&
                \Carbon\Carbon::parse($p->fecha_vencimiento)->between(now(), now()->addDays(30));

              $estadoLabel = $esVencido ? 'Vencido' : ($esCritico ? 'Crítico' : 'OK');
            @endphp
            <tr>

              {{-- Nombre / Marca --}}
              <td>
                <span class="vl-table__cell-name">{{ $p->nombre }}</span>
                @if($p->marca)
                  <span class="vl-table__cell-sub">{{ $p->marca }}</span>
                @endif
              </td>

              {{-- Tipo --}}
              <td>
                <span class="vl-badge vl-badge--slate">
                  {{ ucfirst($p->tipo_insumo ?? '—') }}
                </span>
              </td>

              {{-- Lote --}}
              <td>
                <span style="font-family: var(--vl-font-mono); font-size: .75rem;">
                  {{ $p->numero_lote ?? '—' }}
                </span>
              </td>

              {{-- Vencimiento --}}
              <td>
                @if($p->fecha_vencimiento)
                  <span style="font-size: .78rem; {{ $esVencido ? 'color: var(--vl-rose);' : ($porVencerP ? 'color: var(--vl-amber);' : '') }}">
                    <i class="bi bi-calendar3 me-1"></i>
                    {{ \Carbon\Carbon::parse($p->fecha_vencimiento)->format('d/m/Y') }}
                    @if($porVencerP && !$esVencido)
                      <span class="vl-badge vl-badge--amber" style="margin-left: 4px;">
                        {{ \Carbon\Carbon::parse($p->fecha_vencimiento)->diffInDays(now()) }}d
                      </span>
                    @endif
                  </span>
                @else
                  <span style="color: var(--vl-text-muted); font-size: .75rem;">—</span>
                @endif
              </td>

              {{-- Stock actual --}}
              <td>
                <span style="
                  font-size: .88rem;
                  font-weight: 700;
                  color: {{ $esCritico ? 'var(--vl-rose)' : 'var(--vl-emerald)' }};
                ">
                  {{ $p->stock_actual }}
                  <span style="font-size: .7rem; font-weight: 400;
                               color: var(--vl-text-muted);">
                    {{ $p->unidad_medida }}
                  </span>
                </span>
              </td>

              {{-- Stock mínimo --}}
              <td>
                <span style="font-size: .78rem; color: var(--vl-text-muted);">
                  {{ $p->stock_minimo }} {{ $p->unidad_medida }}
                </span>
              </td>

              {{-- Estado --}}
              <td>
                @if($esVencido)
                  <span class="vl-badge vl-badge--rose">
                    <i class="bi bi-slash-circle-fill"></i> Vencido
                  </span>
                @elseif($esCritico)
                  <span class="vl-badge vl-badge--amber">
                    <i class="bi bi-exclamation-triangle-fill"></i> Crítico
                  </span>
                @else
                  <span class="vl-badge vl-badge--green">
                    <i class="bi bi-check-circle-fill"></i> OK
                  </span>
                @endif
              </td>

              {{-- Acciones (solo maestro) --}}
              @if(auth()->user()->rol === 'maestro')
                <td>
                  <div class="vl-actions">

                    {{-- Reabastecer --}}
                    <button class="vl-btn vl-btn--ghost vl-btn--icon text-success"
                            data-bs-toggle="modal"
                            data-bs-target="#modalReabastecer"
                            data-id="{{ $p->id }}"
                            data-nombre="{{ $p->nombre }}"
                            data-stock="{{ $p->stock_actual }}"
                            data-unidad="{{ $p->unidad_medida }}"
                            title="Reabastecer"
                            data-bs-toggle-tip="tooltip">
                      <i class="bi bi-plus-circle-fill"></i>
                    </button>

                    {{-- Eliminar --}}
                    <form action="{{ route('admin.productos.eliminar', $p->id) }}"
                          method="POST"
                          class="m-0"
                          data-confirm-delete="¿Eliminar el producto {{ $p->nombre }}?">
                      @csrf
                      @method('DELETE')
                      <button type="submit"
                              class="vl-btn vl-btn--ghost vl-btn--icon text-danger"
                              data-bs-toggle="tooltip"
                              title="Eliminar producto">
                        <i class="bi bi-trash3-fill"></i>
                      </button>
                    </form>

                  </div>
                </td>
              @endif

            </tr>
          @empty
            <tr id="vlTableEmpty">
              <td colspan="{{ auth()->user()->rol === 'maestro' ? 8 : 7 }}">
                <div class="vl-table__empty">
                  <i class="bi bi-box-seam"></i>
                  <p>No hay productos registrados en el inventario.</p>
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

{{-- ============================================================
     MODALES
     ============================================================ --}}
@section('modals')

{{-- Modal: Nuevo Producto --}}
@if(auth()->user()->rol === 'maestro')
<div class="modal fade vl-modal" id="modalNuevoProducto" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">
          <i class="bi bi-plus-circle-fill text-primary"></i>
          Nuevo Producto
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form action="{{ route('admin.productos.guardar') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="row g-3">

            <div class="col-md-6">
              <label class="vl-label">Nombre <span class="req">*</span></label>
              <input type="text" name="nombre" class="vl-input"
                     placeholder="Ej: Leche en polvo" required data-uppercase>
            </div>

            <div class="col-md-6">
              <label class="vl-label">Marca</label>
              <input type="text" name="marca" class="vl-input"
                     placeholder="Ej: Gloria" data-uppercase>
            </div>

            <div class="col-md-6">
              <label class="vl-label">Tipo de insumo <span class="req">*</span></label>
              <select name="tipo_insumo" class="vl-select" required>
                <option value="">— Seleccionar —</option>
                <option value="lacteo">Lácteo</option>
                <option value="cereal">Cereal</option>
                <option value="legumbre">Legumbre</option>
                <option value="vitamina">Vitamina / Suplemento</option>
                <option value="otro">Otro</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="vl-label">Número de lote</label>
              <input type="text" name="numero_lote" class="vl-input"
                     placeholder="Ej: LOT-2024-001">
            </div>

            <div class="col-md-4">
              <label class="vl-label">Fecha de vencimiento</label>
              <input type="date" name="fecha_vencimiento" class="vl-input">
            </div>

            <div class="col-md-4">
              <label class="vl-label">Unidad de medida <span class="req">*</span></label>
              <select name="unidad_medida" class="vl-select" required>
                <option value="kg">Kilogramos (kg)</option>
                <option value="g">Gramos (g)</option>
                <option value="l">Litros (l)</option>
                <option value="ml">Mililitros (ml)</option>
                <option value="unidad">Unidades</option>
                <option value="caja">Cajas</option>
                <option value="bolsa">Bolsas</option>
              </select>
            </div>

            <div class="col-md-2">
              <label class="vl-label">Stock inicial <span class="req">*</span></label>
              <input type="number" name="stock_actual" class="vl-input"
                     placeholder="0" min="0" required>
            </div>

            <div class="col-md-2">
              <label class="vl-label">Stock mínimo <span class="req">*</span></label>
              <input type="number" name="stock_minimo" class="vl-input"
                     placeholder="5" min="0" required>
            </div>

          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="vl-btn vl-btn--outline"
                  data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="vl-btn vl-btn--primary">
            <i class="bi bi-check-lg"></i> Guardar producto
          </button>
        </div>
      </form>

    </div>
  </div>
</div>

{{-- Modal: Reabastecer --}}
<div class="modal fade vl-modal" id="modalReabastecer" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">
          <i class="bi bi-plus-circle-fill text-success"></i>
          Reabastecer Producto
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form id="formReabastecer" method="POST">
        @csrf
        <div class="modal-body">

          <div class="vl-alert vl-alert--info mb-4" style="margin-bottom: 0;">
            <i class="bi bi-info-circle-fill"></i>
            <div>
              Producto: <strong id="reabNombre">—</strong><br>
              Stock actual: <strong id="reabStock">—</strong>
              <span id="reabUnidad"></span>
            </div>
          </div>

          <div class="vl-form-group mt-3">
            <label class="vl-label">
              Cantidad a agregar <span class="req">*</span>
            </label>
            <input type="number"
                   name="cantidad"
                   class="vl-input"
                   placeholder="Ej: 50"
                   min="1"
                   required>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="vl-btn vl-btn--outline"
                  data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="vl-btn vl-btn--success">
            <i class="bi bi-check-lg"></i> Confirmar reabastecimiento
          </button>
        </div>
      </form>

    </div>
  </div>
</div>

@endif
@endsection

@push('scripts')
<script>
// Cargar datos en modal de reabastecimiento
document.getElementById('modalReabastecer')?.addEventListener('show.bs.modal', function (e) {
  const btn    = e.relatedTarget;
  const id     = btn?.dataset.id;
  const nombre = btn?.dataset.nombre || '—';
  const stock  = btn?.dataset.stock  || '0';
  const unidad = btn?.dataset.unidad || '';

  document.getElementById('reabNombre').textContent = nombre;
  document.getElementById('reabStock').textContent  = stock;
  document.getElementById('reabUnidad').textContent = unidad;

  const form = document.getElementById('formReabastecer');
  if (form && id) {
    form.action = `/admin/productos/${id}/reabastecer`;
  }
});
</script>
@endpush