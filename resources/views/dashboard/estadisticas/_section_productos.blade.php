<div class="stat-section print-section" id="stat-section-productos">
  <div class="stat-section-card no-print">
    <div class="stat-section-header" style="display:flex; align-items:center; gap:10px; padding:14px 20px; border-bottom:1px solid var(--vl-border);">
      <div style="width:4px; height:22px; background:linear-gradient(135deg,#3b82f6,#6366f1); border-radius:99px;"></div>
      <h3 style="flex:1; font-size:.92rem; font-weight:800; color:var(--vl-text-main); margin:0;">
        <i class="bi bi-box-seam-fill me-2 text-warning"></i>Productos
      </h3>
      <button class="btn-collapse-section no-print" data-collapse-section="productos" title="Colapsar">
        <i class="bi bi-chevron-up"></i>
      </button>
    </div>
    <div class="stat-section-body">
      <div class="row g-4">
    {{-- Stock actual barras horizontales --}}
    <div class="col-lg-6">
      <div class="vl-card h-100">
        <div class="vl-card__accent"></div>
        <div class="vl-card__header">
          <h5 class="vl-card__title">
            <i class="bi bi-bar-chart-horizontal-fill text-warning"></i>
            Stock Actual por Producto
          </h5>
         
        </div>
        <div class="vl-card__body vl-collapsible" id="body-stock">
          @php $maxStock = $stockProductos->max('stock_actual') ?: 1; @endphp
          @forelse($stockProductos as $prod)
            @php
              $pct = ($prod->stock_actual / $maxStock) * 100;
              $critico = $prod->stock_actual <= $prod->stock_minimo;
              $color = $critico ? '#f43f5e' : '#10b981';
            @endphp
            <div style="margin-bottom:11px;">
              <div style="display:flex; justify-content:space-between; margin-bottom:3px;">
                <span style="font-size:.75rem; font-weight:600; color:var(--vl-text-main);
                             overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:70%;">
                  {{ $prod->nombre }}
                  @if($critico)
                    <i class="bi bi-exclamation-triangle-fill text-danger ms-1" style="font-size:.65rem;"></i>
                  @endif
                </span>
                <span style="font-size:.73rem; font-weight:700; color:{{ $color }};">
                  {{ $prod->stock_actual }} / {{ $prod->stock_minimo }}
                </span>
              </div>
              <div style="background:var(--vl-border); border-radius:99px; height:8px; overflow:hidden;">
                <div style="width:{{ $pct }}%; height:100%; background:{{ $color }};
                            border-radius:99px; transition:width .4s;"></div>
              </div>
            </div>
          @empty
            <p style="color:var(--vl-text-muted); font-size:.8rem;">Sin productos registrados</p>
          @endforelse
        </div>
      </div>
    </div>

    {{-- Top productos más distribuidos --}}
    <div class="col-lg-6">
      <div class="vl-card h-100">
        <div class="vl-card__accent"></div>
        <div class="vl-card__header">
          <h5 class="vl-card__title">
            <i class="bi bi-box-seam-fill text-primary"></i>
            Top Productos — Más Distribuidos
          </h5>
        </div>
        <div class="vl-card__body vl-collapsible" id="body-toprod2">
          @php
            $maxProd = $topProductos->max('total') ?: 1;
            $prodCols = ['#6366f1','#3b82f6','#10b981','#f59e0b','#f43f5e'];
          @endphp
          @forelse($topProductos as $i => $tp)
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:14px;">
              <div style="width:28px; height:28px; border-radius:8px; flex-shrink:0;
                          background:{{ $prodCols[$i%count($prodCols)] }}22;
                          display:flex; align-items:center; justify-content:center;
                          font-size:.72rem; font-weight:800; color:{{ $prodCols[$i%count($prodCols)] }};">
                {{ $i+1 }}
              </div>
              <div style="flex:1; min-width:0;">
                <div style="font-size:.78rem; font-weight:600; color:var(--vl-text-main);
                            white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                  {{ $tp->producto->nombre ?? '—' }}
                </div>
                <div style="background:var(--vl-border); border-radius:99px; height:5px;
                            overflow:hidden; margin-top:4px;">
                  <div style="width:{{ ($tp->total/$maxProd)*100 }}%; height:100%;
                              background:{{ $prodCols[$i%count($prodCols)] }};
                              border-radius:99px;"></div>
                </div>
              </div>
              <span style="font-size:.8rem; font-weight:700; color:{{ $prodCols[$i%count($prodCols)] }};
                           flex-shrink:0;">
                {{ $tp->total }}
              </span>
            </div>
          @empty
            <p style="color:var(--vl-text-muted); font-size:.8rem;">Sin datos</p>
          @endforelse
        </div>
      </div>
    </div>

    {{-- Vencimientos próximos --}}
    @if($vencimientoTimeline->count() > 0)
    <div class="col-12">
      <div class="vl-card">
        <div class="vl-card__accent" style="background:linear-gradient(90deg,#f43f5e,#f97316);"></div>
        <div class="vl-card__header">
          <h5 class="vl-card__title">
            <i class="bi bi-clock-history text-danger"></i>
            Vencimientos — Próximos 90 días
          </h5>
          
        </div>
        <div class="vl-card__body vl-collapsible" id="body-venc" style="padding:0;">
          <table class="vl-table">
            <thead>
              <tr>
                <th>Producto</th>
                <th>Vence</th>
                <th>Días restantes</th>
                <th>Stock actual</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              @foreach($vencimientoTimeline as $pv)
                @php
                  $dias = now()->diffInDays($pv->fecha_vencimiento, false);
                  $urgencia = $dias <= 15 ? 'rose' : ($dias <= 30 ? 'amber' : 'blue');
                @endphp
                <tr>
                  <td style="font-weight:600; font-size:.8rem;">{{ $pv->nombre }}</td>
                  <td style="font-size:.8rem;">{{ \Carbon\Carbon::parse($pv->fecha_vencimiento)->format('d/m/Y') }}</td>
                  <td>
                    <span style="font-weight:700; font-size:.85rem;
                                 color:{{ $dias<=15?'#f43f5e':($dias<=30?'#f59e0b':'#3b82f6') }};">
                      {{ $dias }} días
                    </span>
                  </td>
                  <td style="font-size:.8rem;">{{ $pv->stock_actual }}</td>
                  <td>
                    <span class="vl-badge vl-badge--{{ $urgencia }}" style="font-size:.65rem;">
                      {{ $dias<=15?'Urgente':($dias<=30?'Próximo':'OK') }}
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

    </div>{{-- /stat-section-body --}}
  </div>{{-- /stat-section-card --}}
</div>{{-- /stat-section --}}