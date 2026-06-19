@extends('layouts.admin')

@section('title', 'Papelera — Vaso de Leche')
@section('breadcrumb', 'Papelera')
@php $activeModule = 'papelera'; @endphp

@section('content')

<div class="vl-section-header">
  <div>
    <h2 class="vl-section-title">
      <i class="bi bi-trash3-fill text-danger"></i>
      Papelera de Registros
    </h2>
    <p class="vl-section-sub">
      Registros eliminados — solo el maestro puede restaurarlos
    </p>
  </div>
</div>

{{-- ── Tabs ── --}}
<div style="display:flex; gap:8px; margin-bottom:20px; flex-wrap:wrap;">
  @foreach([
    ['tab-beneficiarios', 'bi-people-fill',    'Beneficiarios', $beneficiarios->count()],
    ['tab-productos',     'bi-box-seam',        'Productos',     $productos->count()],
    ['tab-entregas',      'bi-journal-check',   'Entregas',      $entregas->count()],
  ] as [$tab, $icon, $label, $count])
    <button type="button"
            onclick="vlPapeleraTab('{{ $tab }}')"
            id="btn-{{ $tab }}"
            class="vl-btn {{ $tab === 'tab-beneficiarios' ? 'vl-btn--primary' : 'vl-btn--outline' }}">
      <i class="bi {{ $icon }}"></i>
      {{ $label }}
      @if($count > 0)
        <span class="vl-badge vl-badge--rose" style="margin-left:4px;">{{ $count }}</span>
      @endif
    </button>
  @endforeach
</div>

{{-- ═══════════════ BENEFICIARIOS ═══════════════ --}}
<div id="tab-beneficiarios">
  <div class="vl-card vl-animate">
    <div class="vl-card__accent" style="background:var(--vl-rose);"></div>
    <div class="vl-card__header">
      <h5 class="vl-card__title">
        <i class="bi bi-people-fill text-danger"></i>
        Beneficiarios eliminados
      </h5>
    </div>
    <div class="vl-card__body" style="padding:12px 0 0;">
      @if($beneficiarios->isEmpty())
        <div class="vl-table__empty"><i class="bi bi-check-circle text-success"></i><p>Sin registros en la papelera.</p></div>
      @else
        <div class="vl-table-wrap">
          <table class="vl-table">
            <thead>
              <tr>
                <th>Beneficiario</th>
                <th>DNI</th>
                <th>Tipo</th>
                <th>Eliminado</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody>
              @foreach($beneficiarios as $b)
                <tr>
                  <td style="font-weight:600;">{{ $b->nombre }} {{ $b->apellido }}</td>
                  <td><span class="vl-badge vl-badge--slate">{{ $b->dni }}</span></td>
                  <td>{{ $b->tipo_beneficiario ?? '—' }}</td>
                  <td style="font-size:.75rem; color:var(--vl-text-muted);">
                    {{ \Carbon\Carbon::parse($b->deleted_at)->format('d/m/Y H:i') }}
                  </td>
                  <td>
                    <form action="{{ route('admin.beneficiarios.restaurar', $b->id) }}" method="POST">
                      @csrf @method('PATCH')
                      <button type="submit" class="vl-btn vl-btn--success vl-btn--sm">
                        <i class="bi bi-arrow-counterclockwise"></i> Restaurar
                      </button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>
</div>

{{-- ═══════════════ PRODUCTOS ═══════════════ --}}
<div id="tab-productos" style="display:none;">
  <div class="vl-card vl-animate">
    <div class="vl-card__accent" style="background:var(--vl-rose);"></div>
    <div class="vl-card__header">
      <h5 class="vl-card__title">
        <i class="bi bi-box-seam text-danger"></i>
        Productos eliminados
      </h5>
    </div>
    <div class="vl-card__body" style="padding:12px 0 0;">
      @if($productos->isEmpty())
        <div class="vl-table__empty"><i class="bi bi-check-circle text-success"></i><p>Sin registros en la papelera.</p></div>
      @else
        <div class="vl-table-wrap">
          <table class="vl-table">
            <thead>
              <tr>
                <th>Producto</th>
                <th>Tipo</th>
                <th>Stock al eliminar</th>
                <th>Eliminado</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody>
              @foreach($productos as $p)
                <tr>
                  <td style="font-weight:600;">{{ $p->nombre }}</td>
                  <td><span class="vl-badge vl-badge--slate">{{ $p->tipo_insumo }}</span></td>
                  <td>{{ $p->stock_actual }} {{ $p->unidad_medida }}</td>
                  <td style="font-size:.75rem; color:var(--vl-text-muted);">
                    {{ \Carbon\Carbon::parse($p->deleted_at)->format('d/m/Y H:i') }}
                  </td>
                  <td>
                    <form action="{{ route('admin.productos.restaurar', $p->id) }}" method="POST">
                      @csrf @method('PATCH')
                      <button type="submit" class="vl-btn vl-btn--success vl-btn--sm">
                        <i class="bi bi-arrow-counterclockwise"></i> Restaurar
                      </button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>
</div>

{{-- ═══════════════ ENTREGAS ═══════════════ --}}
<div id="tab-entregas" style="display:none;">
  <div class="vl-card vl-animate">
    <div class="vl-card__accent" style="background:var(--vl-rose);"></div>
    <div class="vl-card__header">
      <h5 class="vl-card__title">
        <i class="bi bi-journal-check text-danger"></i>
        Entregas eliminadas
      </h5>
    </div>
    <div class="vl-card__body" style="padding:12px 0 0;">
      @if($entregas->isEmpty())
        <div class="vl-table__empty"><i class="bi bi-check-circle text-success"></i><p>Sin registros en la papelera.</p></div>
      @else
        <div class="vl-table-wrap">
          <table class="vl-table">
            <thead>
              <tr>
                <th>Beneficiario</th>
                <th>Producto</th>
                <th>Fecha entrega</th>
                <th>Registrado por</th>
                <th>Eliminado</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody>
              @foreach($entregas as $e)
                <tr>
                  <td>{{ $e->beneficiario?->nombre }} {{ $e->beneficiario?->apellido }}</td>
                  <td>{{ $e->producto?->nombre ?? '—' }}</td>
                  <td>{{ \Carbon\Carbon::parse($e->fecha_entrega)->format('d/m/Y') }}</td>
                  <td>{{ $e->user?->name ?? '—' }}</td>
                  <td style="font-size:.75rem; color:var(--vl-text-muted);">
                    {{ \Carbon\Carbon::parse($e->deleted_at)->format('d/m/Y H:i') }}
                  </td>
                  <td>
                    <form action="{{ route('admin.entregas.restaurar', $e->id) }}" method="POST">
                      @csrf @method('PATCH')
                      <button type="submit" class="vl-btn vl-btn--success vl-btn--sm">
                        <i class="bi bi-arrow-counterclockwise"></i> Restaurar
                      </button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
function vlPapeleraTab(tabId) {
  ['tab-beneficiarios','tab-productos','tab-entregas'].forEach(id => {
    document.getElementById(id).style.display = 'none';
    document.getElementById('btn-' + id).className = 'vl-btn vl-btn--outline';
  });
  document.getElementById(tabId).style.display = 'block';
  document.getElementById('btn-' + tabId).className = 'vl-btn vl-btn--primary';
}
</script>
@endpush