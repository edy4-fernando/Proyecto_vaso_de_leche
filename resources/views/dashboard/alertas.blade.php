{{-- ============================================================
     admin/alertas.blade.php
     Ruta: GET /dashboard/alertas → AdminController@dashboardAlertas
     Variables: $stockCritico, $porVencer, $vencidos
     ============================================================ --}}

@extends('layouts.dashboard')

@section('title', 'Alertas del Sistema — Vaso de Leche')
@section('breadcrumb', 'Centro de Alertas')
@php $activeModule = 'alertas'; @endphp

@section('content')

{{-- ── Encabezado ── --}}
<div class="vl-section-header">
  <div>
    <h2 class="vl-section-title">
      <i class="bi bi-exclamation-triangle-fill text-warning"></i>
      Centro de Alertas
    </h2>
    <p class="vl-section-sub">
      Monitoreo de stock crítico, vencimientos y anomalías del sistema
    </p>
  </div>
  <span class="vl-badge vl-badge--amber" style="font-size: .8rem; padding: 6px 14px;">
    @php
      $totalAlertas = ($stockCritico->count() ?? 0)
        + ($porVencer->count() ?? 0)
        + ($vencidos->count() ?? 0);
    @endphp
    {{ $totalAlertas }} alerta(s) activa(s)
  </span>
</div>

{{-- ── Sin alertas ── --}}
@if($totalAlertas === 0)
  <div class="vl-card vl-animate">
    <div class="vl-card__body" style="text-align: center; padding: 60px 20px;">
      <i class="bi bi-shield-check-fill"
         style="font-size: 3rem; color: var(--vl-emerald); display: block; margin-bottom: 16px;"></i>
      <h4 style="font-size: 1rem; font-weight: 700; color: var(--vl-text-main); margin-bottom: 6px;">
        Todo en orden
      </h4>
      <p style="font-size: .82rem; color: var(--vl-text-muted); margin: 0;">
        No hay alertas activas en este momento. El sistema funciona con normalidad.
      </p>
    </div>
  </div>
@else

  {{-- ══════════════════════════════════════════
       STOCK CRÍTICO
       ══════════════════════════════════════════ --}}
  @if(isset($stockCritico) && $stockCritico->count() > 0)
    <div class="vl-card mb-4 vl-animate">
      <div class="vl-card__accent" style="background: linear-gradient(90deg, var(--vl-rose), #e11d48);"></div>
      <div class="vl-card__header">
        <h5 class="vl-card__title">
          <i class="bi bi-exclamation-triangle-fill text-danger"></i>
          Stock Crítico
        </h5>
        <span class="vl-badge vl-badge--rose">
          {{ $stockCritico->count() }} producto(s)
        </span>
      </div>

      <div class="vl-alert vl-alert--danger"
           style="margin: 0 20px 0; border-radius: var(--vl-radius-sm);">
        <i class="bi bi-exclamation-octagon-fill"></i>
        <div style="font-size: .8rem;">
          Los siguientes productos tienen stock igual o por debajo del mínimo configurado.
          Se recomienda reabastecer a la brevedad para no interrumpir el programa.
        </div>
      </div>

      <div class="vl-card__body" style="padding: 12px 0 0;">
        <div class="vl-table-wrap">
          <table class="vl-table">
            <thead>
              <tr>
                <th>Producto</th>
                <th>Tipo</th>
                <th>Stock actual</th>
                <th>Stock mínimo</th>
                <th>Déficit</th>
                @if(auth()->user()->rol === 'maestro')
                  <th>Acción</th>
                @endif
              </tr>
            </thead>
            <tbody>
              @foreach($stockCritico as $p)
                <tr>
                  <td>
                    <span class="vl-table__cell-name">{{ $p->nombre }}</span>
                    @if($p->marca)
                      <span class="vl-table__cell-sub">{{ $p->marca }}</span>
                    @endif
                  </td>
                  <td>
                    <span class="vl-badge vl-badge--slate">
                      {{ ucfirst($p->tipo_insumo ?? '—') }}
                    </span>
                  </td>
                  <td>
                    <span style="font-size: .88rem; font-weight: 700; color: var(--vl-rose);">
                      {{ $p->stock_actual }} {{ $p->unidad_medida }}
                    </span>
                  </td>
                  <td>
                    <span style="font-size: .78rem; color: var(--vl-text-muted);">
                      {{ $p->stock_minimo }} {{ $p->unidad_medida }}
                    </span>
                  </td>
                  <td>
                    <span class="vl-badge vl-badge--rose">
                      -{{ max(0, $p->stock_minimo - $p->stock_actual) }} {{ $p->unidad_medida }}
                    </span>
                  </td>
                  @if(auth()->user()->rol === 'maestro')
                    <td>
                      <a href="{{ route('admin.productos') }}"
                         class="vl-btn vl-btn--primary vl-btn--sm">
                        <i class="bi bi-plus-circle-fill"></i>
                        Reabastecer
                      </a>
                    </td>
                  @endif
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  @endif

  {{-- ══════════════════════════════════════════
       POR VENCER (próximos 30 días)
       ══════════════════════════════════════════ --}}
  @if(isset($porVencer) && $porVencer->count() > 0)
    <div class="vl-card mb-4 vl-animate vl-animate--delay-1">
      <div class="vl-card__accent"
           style="background: linear-gradient(90deg, var(--vl-amber), #d97706);"></div>
      <div class="vl-card__header">
        <h5 class="vl-card__title">
          <i class="bi bi-calendar-x-fill text-warning"></i>
          Próximos a Vencer
        </h5>
        <span class="vl-badge vl-badge--amber">
          {{ $porVencer->count() }} producto(s)
        </span>
      </div>

      <div class="vl-alert vl-alert--warning"
           style="margin: 0 20px 0; border-radius: var(--vl-radius-sm);">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div style="font-size: .8rem;">
          Estos productos vencen en los próximos <strong>30 días</strong>.
          Priorice su uso en las próximas distribuciones.
        </div>
      </div>

      <div class="vl-card__body" style="padding: 12px 0 0;">
        <div class="vl-table-wrap">
          <table class="vl-table">
            <thead>
              <tr>
                <th>Producto</th>
                <th>Lote</th>
                <th>Vence el</th>
                <th>Días restantes</th>
                <th>Stock disponible</th>
              </tr>
            </thead>
            <tbody>
              @foreach($porVencer->sortBy('fecha_vencimiento') as $p)
                @php
                  $diasRestantes = \Carbon\Carbon::now()
                    ->diffInDays(\Carbon\Carbon::parse($p->fecha_vencimiento));
                @endphp
                <tr>
                  <td>
                    <span class="vl-table__cell-name">{{ $p->nombre }}</span>
                    @if($p->marca)
                      <span class="vl-table__cell-sub">{{ $p->marca }}</span>
                    @endif
                  </td>
                  <td>
                    <span style="font-family: var(--vl-font-mono); font-size: .75rem;">
                      {{ $p->numero_lote ?? '—' }}
                    </span>
                  </td>
                  <td>
                    <span style="font-size: .8rem; color: var(--vl-amber); font-weight: 600;">
                      <i class="bi bi-calendar3 me-1"></i>
                      {{ \Carbon\Carbon::parse($p->fecha_vencimiento)->format('d/m/Y') }}
                    </span>
                  </td>
                  <td>
                    <span class="vl-badge {{ $diasRestantes <= 7 ? 'vl-badge--rose' : 'vl-badge--amber' }}">
                      {{ $diasRestantes }} días
                    </span>
                  </td>
                  <td>
                    <span style="font-size: .82rem; font-weight: 600;
                                 color: var(--vl-text-main);">
                      {{ $p->stock_actual }} {{ $p->unidad_medida }}
                    </span>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  @endif

  {{-- ══════════════════════════════════════════
       VENCIDOS
       ══════════════════════════════════════════ --}}
  @if(isset($vencidos) && $vencidos->count() > 0)
    <div class="vl-card mb-4 vl-animate vl-animate--delay-2">
      <div class="vl-card__accent"
           style="background: linear-gradient(90deg, #64748b, #475569);"></div>
      <div class="vl-card__header">
        <h5 class="vl-card__title">
          <i class="bi bi-slash-circle-fill text-muted"></i>
          Productos Vencidos
        </h5>
        <span class="vl-badge vl-badge--slate">
          {{ $vencidos->count() }} producto(s)
        </span>
      </div>

      <div class="vl-alert vl-alert--danger"
           style="margin: 0 20px 0; border-radius: var(--vl-radius-sm);">
        <i class="bi bi-x-octagon-fill"></i>
        <div style="font-size: .8rem;">
          <strong>¡Atención!</strong> Estos productos están vencidos y
          <strong>NO deben distribuirse</strong>. Proceda a su baja y disposición
          según protocolo municipal.
        </div>
      </div>

      <div class="vl-card__body" style="padding: 12px 0 0;">
        <div class="vl-table-wrap">
          <table class="vl-table">
            <thead>
              <tr>
                <th>Producto</th>
                <th>Lote</th>
                <th>Venció el</th>
                <th>Días vencido</th>
                <th>Stock</th>
                @if(auth()->user()->rol === 'maestro')
                  <th>Acción</th>
                @endif
              </tr>
            </thead>
            <tbody>
              @foreach($vencidos as $p)
                @php
                  $diasVencido = \Carbon\Carbon::parse($p->fecha_vencimiento)
                    ->diffInDays(\Carbon\Carbon::now());
                @endphp
                <tr>
                  <td>
                    <span class="vl-table__cell-name"
                          style="color: var(--vl-text-muted);
                                 text-decoration: line-through;">
                      {{ $p->nombre }}
                    </span>
                    @if($p->marca)
                      <span class="vl-table__cell-sub">{{ $p->marca }}</span>
                    @endif
                  </td>
                  <td>
                    <span style="font-family: var(--vl-font-mono); font-size: .75rem;">
                      {{ $p->numero_lote ?? '—' }}
                    </span>
                  </td>
                  <td>
                    <span style="font-size: .8rem; color: var(--vl-rose); font-weight: 600;">
                      {{ \Carbon\Carbon::parse($p->fecha_vencimiento)->format('d/m/Y') }}
                    </span>
                  </td>
                  <td>
                    <span class="vl-badge vl-badge--rose">
                      Hace {{ $diasVencido }} días
                    </span>
                  </td>
                  <td>
                    <span style="font-size: .82rem; color: var(--vl-text-muted);">
                      {{ $p->stock_actual }} {{ $p->unidad_medida }}
                    </span>
                  </td>
                  @if(auth()->user()->rol === 'maestro')
                    <td>
                      <form action="{{ route('admin.productos.eliminar', $p->id) }}"
                            method="POST"
                            class="m-0"
                            data-confirm-delete="¿Dar de baja y eliminar el producto vencido {{ $p->nombre }}?">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="vl-btn vl-btn--outline vl-btn--sm text-danger">
                          <i class="bi bi-trash3-fill"></i>
                          Dar de baja
                        </button>
                      </form>
                    </td>
                  @endif
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  @endif

@endif {{-- /if $totalAlertas > 0 --}}

@endsection