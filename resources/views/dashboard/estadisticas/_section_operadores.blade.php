<div class="stat-section print-section" id="stat-section-operadores">
  <div class="stat-section-card no-print">
    <div class="stat-section-header" style="display:flex; align-items:center; gap:10px; padding:14px 20px; border-bottom:1px solid var(--vl-border);">
      <div style="width:4px; height:22px; background:linear-gradient(135deg,#3b82f6,#6366f1); border-radius:99px;"></div>
      <h3 style="flex:1; font-size:.92rem; font-weight:800; color:var(--vl-text-main); margin:0;">
        <i class="bi bi-person-gear me-2 text-violet"></i>Operadores
      </h3>
      <button class="btn-collapse-section no-print" data-collapse-section="operadores" title="Colapsar">
        <i class="bi bi-chevron-up"></i>
      </button>
    </div>
    <div class="stat-section-body">
      <div class="row g-4">

    {{-- Donut operadores --}}
    <div class="col-lg-4">
      <div class="vl-card h-100">
        <div class="vl-card__accent"></div>
        <div class="vl-card__header">
          <h5 class="vl-card__title">
            <i class="bi bi-pie-chart-fill text-violet"></i> Distribución
          </h5>
          
        </div>
        <div class="vl-card__body vl-collapsible" id="body-op-dist" style="display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
          @php
            $opTotal = $porOperador->sum('total') ?: 1;
            $opCols = ['#6366f1','#3b82f6','#10b981','#f59e0b','#f43f5e','#0891b2'];
            $startOp=0; $cxOp=75; $cyOp=75; $rOp=62; $rOpI=42;
            $pxyOp=fn($cx,$cy,$r,$deg)=>[$cx+$r*cos(deg2rad($deg-90)),$cy+$r*sin(deg2rad($deg-90))];
          @endphp
          <svg viewBox="0 0 150 150" style="width:130px; flex-shrink:0;">
            @foreach($porOperador as $i=>$op)
              @php
                $pctOp=$op->total/$opTotal;
                $angOp=$pctOp*360;
                [$ox1,$oy1]=$pxyOp($cxOp,$cyOp,$rOp,$startOp);
                [$ox2,$oy2]=$pxyOp($cxOp,$cyOp,$rOp,$startOp+$angOp);
                [$oix1,$oiy1]=$pxyOp($cxOp,$cyOp,$rOpI,$startOp);
                [$oix2,$oiy2]=$pxyOp($cxOp,$cyOp,$rOpI,$startOp+$angOp);
                $lgOp=$angOp>180?1:0;
                $colOp=$opCols[$i%count($opCols)];
                $startOp+=$angOp;
              @endphp
              <path d="M {{ $oix1 }} {{ $oiy1 }} L {{ $ox1 }} {{ $oy1 }}
                       A {{ $rOp }} {{ $rOp }} 0 {{ $lgOp }} 1 {{ $ox2 }} {{ $oy2 }}
                       L {{ $oix2 }} {{ $oiy2 }}
                       A {{ $rOpI }} {{ $rOpI }} 0 {{ $lgOp }} 0 {{ $oix1 }} {{ $oiy1 }} Z"
                    fill="{{ $colOp }}" stroke="var(--vl-bg-card)" stroke-width="2"/>
            @endforeach
            <text x="{{ $cxOp }}" y="{{ $cyOp-4 }}" text-anchor="middle"
                  font-size="18" font-weight="800" fill="var(--vl-text-main)">
              {{ $opTotal }}
            </text>
            <text x="{{ $cxOp }}" y="{{ $cyOp+10 }}" text-anchor="middle"
                  font-size="8" fill="var(--vl-text-muted)">ENTREGAS</text>
          </svg>
          <div style="flex:1; min-width:0;">
            @foreach($porOperador as $i=>$op)
              <div style="display:flex; align-items:center; gap:6px; margin-bottom:8px;">
                <div style="width:8px; height:8px; border-radius:50%;
                            background:{{ $opCols[$i%count($opCols)] }}; flex-shrink:0;"></div>
                <div style="flex:1; min-width:0;">
                  <div style="font-size:.72rem; font-weight:600; color:var(--vl-text-main);
                              white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                    {{ $op->user->name ?? 'Sistema' }}
                  </div>
                  <div style="font-size:.65rem; color:var(--vl-text-muted);">
                    {{ $op->total }} ({{ round(($op->total/$opTotal)*100) }}%)
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>

    {{-- Tabla ranking --}}
    <div class="col-lg-8">
      <div class="vl-card h-100">
        <div class="vl-card__accent"></div>
        <div class="vl-card__header">
          <h5 class="vl-card__title">
            <i class="bi bi-table text-primary"></i> Ranking de Operadores
          </h5>
          
        </div>
        <div class="vl-card__body vl-collapsible" id="body-oprank" style="padding:0;">
          @php $maxOp = $porOperador->max('total') ?: 1; @endphp
          <table class="vl-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Operador</th>
                <th>Entregas</th>
                <th>%</th>
                <th style="width:30%;">Rendimiento</th>
              </tr>
            </thead>
            <tbody>
              @forelse($porOperador as $i=>$op)
                <tr>
                  <td>
                    @if($i===0) <i class="bi bi-trophy-fill text-warning"></i>
                    @elseif($i===1) <i class="bi bi-trophy-fill text-secondary"></i>
                    @elseif($i===2) <i class="bi bi-trophy-fill" style="color:#cd7f32;"></i>
                    @else <span style="color:var(--vl-text-muted); font-size:.75rem;">{{ $i+1 }}</span>
                    @endif
                  </td>
                  <td>
                    <div class="vl-table__cell-user">
                      <div class="vl-table__mini-avatar"
                           style="background:{{ $opCols[$i%count($opCols)] }}22;
                                  color:{{ $opCols[$i%count($opCols)] }};">
                        {{ strtoupper(substr($op->user->name??'S',0,1)) }}
                      </div>
                      <span class="vl-table__cell-name" style="font-size:.8rem;">
                        {{ $op->user->name ?? 'Sistema' }}
                      </span>
                    </div>
                  </td>
                  <td style="font-weight:700; font-size:.88rem;">{{ $op->total }}</td>
                  <td style="font-size:.8rem; color:var(--vl-text-sub);">
                    {{ round(($op->total/$opTotal)*100) }}%
                  </td>
                  <td>
                    <div style="background:var(--vl-border); border-radius:99px; height:6px; overflow:hidden;">
                      <div style="width:{{ ($op->total/$maxOp)*100 }}%; height:100%;
                                  background:{{ $opCols[$i%count($opCols)] }};
                                  border-radius:99px;"></div>
                    </div>
                  </td>
                </tr>
              @empty
                <tr><td colspan="5" style="text-align:center; color:var(--vl-text-muted); padding:20px;">
                  Sin datos
                </td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>{{-- /row --}}
    </div>{{-- /stat-section-body --}}
  </div>{{-- /stat-section-card --}}
</div>{{-- /stat-section --}}