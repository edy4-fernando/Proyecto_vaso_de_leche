<div class="stat-section print-section" id="stat-section-beneficiarios">
  <div class="stat-section-card no-print">
    <div class="stat-section-header" style="display:flex; align-items:center; gap:10px; padding:14px 20px; border-bottom:1px solid var(--vl-border);">
      <div style="width:4px; height:22px; background:linear-gradient(135deg,#3b82f6,#6366f1); border-radius:99px;"></div>
      <h3 style="flex:1; font-size:.92rem; font-weight:800; color:var(--vl-text-main); margin:0;">
        <i class="bi bi-people-fill me-2 text-success"></i>Beneficiarios
      </h3>
      <button class="btn-collapse-section no-print" data-collapse-section="beneficiarios" title="Colapsar">
        <i class="bi bi-chevron-up"></i>
      </button>
    </div>
    <div class="stat-section-body">
      <div class="row g-4">

    {{-- Donut activos/inactivos --}}
    <div class="col-lg-3">
      <div class="vl-card h-100">
        <div class="vl-card__accent"></div>
        <div class="vl-card__header">
          <h5 class="vl-card__title"><i class="bi bi-person-check-fill text-success"></i> Estado</h5>
          
        </div>
        <div class="vl-card__body vl-collapsible" id="body-estado-ben" style="display:flex; flex-direction:column; align-items:center;">
          @php
            $totalB2 = $totalBeneficiarios ?: 1;
            $pctActivos = $totalActivos / $totalB2;
            $angActivos = $pctActivos * 360;
            $cxS=80; $cyS=80; $rS=65; $rSI=45;
            $pxyB=fn($cx,$cy,$r,$deg)=>[$cx+$r*cos(deg2rad($deg-90)),$cy+$r*sin(deg2rad($deg-90))];
            [$sx1,$sy1]=$pxyB($cxS,$cyS,$rS,0);
            [$sx2,$sy2]=$pxyB($cxS,$cyS,$rS,$angActivos);
            [$six1,$siy1]=$pxyB($cxS,$cyS,$rSI,0);
            [$six2,$siy2]=$pxyB($cxS,$cyS,$rSI,$angActivos);
            $laS=$angActivos>180?1:0;
          @endphp
          <svg viewBox="0 0 160 160" style="width:130px;">
            @if($pctActivos < 1)
              <path d="M {{ $six2 }} {{ $siy2 }} L {{ $sx2 }} {{ $sy2 }}
                       A {{ $rS }} {{ $rS }} 0 {{ $angActivos<=180?1:0 }} 1 {{ $sx1 }} {{ $sy1 }}
                       L {{ $six1 }} {{ $siy1 }}
                       A {{ $rSI }} {{ $rSI }} 0 {{ $angActivos<=180?1:0 }} 0 {{ $six2 }} {{ $siy2 }} Z"
                    fill="var(--vl-border)"/>
            @endif
            @if($angActivos > 0)
              <path d="M {{ $six1 }} {{ $siy1 }} L {{ $sx1 }} {{ $sy1 }}
                       A {{ $rS }} {{ $rS }} 0 {{ $laS }} 1 {{ $sx2 }} {{ $sy2 }}
                       L {{ $six2 }} {{ $siy2 }}
                       A {{ $rSI }} {{ $rSI }} 0 {{ $laS }} 0 {{ $six1 }} {{ $siy1 }} Z"
                    fill="#10b981"/>
            @endif
            <text x="{{ $cxS }}" y="{{ $cyS-5 }}" text-anchor="middle"
                  font-size="20" font-weight="800" fill="var(--vl-text-main)">
              {{ $totalBeneficiarios>0?round(($totalActivos/$totalBeneficiarios)*100):0 }}%
            </text>
            <text x="{{ $cxS }}" y="{{ $cyS+10 }}" text-anchor="middle"
                  font-size="9" fill="var(--vl-text-muted)">ACTIVOS</text>
          </svg>
          <div style="display:flex; justify-content:center; gap:20px; margin-top:12px;">
            <div style="text-align:center;">
              <div style="font-size:1.4rem; font-weight:800; color:#10b981;">{{ $totalActivos }}</div>
              <div style="font-size:.7rem; color:var(--vl-text-muted);">Activos</div>
            </div>
            <div style="width:1px; background:var(--vl-border);"></div>
            <div style="text-align:center;">
              <div style="font-size:1.4rem; font-weight:800; color:var(--vl-text-muted);">{{ $totalInactivos }}</div>
              <div style="font-size:.7rem; color:var(--vl-text-muted);">De baja</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Por tipo (torta) --}}
    <div class="col-lg-4">
      <div class="vl-card h-100">
        <div class="vl-card__accent"></div>
        <div class="vl-card__header">
          <h5 class="vl-card__title"><i class="bi bi-pie-chart-fill text-primary"></i> Por Tipo</h5>

        </div>
        <div class="vl-card__body vl-collapsible" id="body-torta2"
             style="display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
          @php
            $colTorta=['#3b82f6','#10b981','#f59e0b','#f43f5e','#7c3aed','#0891b2','#64748b'];
            $totalTipo = $porTipo->sum('total') ?: 1;
            $startTorta=0; $cxT=70; $cyT=70; $rT=60; $rTI=38;
            $pxyT=fn($cx,$cy,$r,$deg)=>[$cx+$r*cos(deg2rad($deg-90)),$cy+$r*sin(deg2rad($deg-90))];
          @endphp
          <svg viewBox="0 0 140 140" style="width:120px; flex-shrink:0;">
            @foreach($porTipo as $i=>$tp)
              @php
                $pct=$tp->total/$totalTipo;
                $ang=$pct*360;
                [$tx1,$ty1]=$pxyT($cxT,$cyT,$rT,$startTorta);
                  [$tx2,$ty2]=$pxyT($cxT,$cyT,$rT,$startTorta+$ang);
                  [$tix1,$tiy1]=$pxyT($cxT,$cyT,$rTI,$startTorta);
                  [$tix2,$tiy2]=$pxyT($cxT,$cyT,$rTI,$startTorta+$ang);
                $lgT=$ang>180?1:0;
                $colT=$colTorta[$i%count($colTorta)];
                $startTorta+=$ang;
              @endphp
              <path d="M {{ $tix1 }} {{ $tiy1 }} L {{ $tx1 }} {{ $ty1 }}
                       A {{ $rT }} {{ $rT }} 0 {{ $lgT }} 1 {{ $tx2 }} {{ $ty2 }}
                       L {{ $tix2 }} {{ $tiy2 }}
                       A {{ $rTI }} {{ $rTI }} 0 {{ $lgT }} 0 {{ $tix1 }} {{ $tiy1 }} Z"
                    fill="{{ $colT }}" stroke="var(--vl-bg-card)" stroke-width="2"/>
            @endforeach
            <text x="{{ $cxT }}" y="{{ $cyT-4 }}" text-anchor="middle"
                  font-size="16" font-weight="800" fill="var(--vl-text-main)">
              {{ $totalBeneficiarios }}
            </text>
            <text x="{{ $cxT }}" y="{{ $cyT+11 }}" text-anchor="middle"
                  font-size="8" fill="var(--vl-text-muted)">TOTAL</text>
          </svg>
          <div style="flex:1; min-width:0;">
            @foreach($porTipo as $i=>$tp)
              <div style="display:flex; align-items:center; gap:6px; margin-bottom:7px;">
                <div style="width:8px; height:8px; border-radius:50%;
                            background:{{ $colTorta[$i%count($colTorta)] }}; flex-shrink:0;"></div>
                <span style="font-size:.73rem; flex:1; color:var(--vl-text-sub);
                             overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                  {{ ucfirst($tp->tipo_beneficiario ?? 'Sin tipo') }}
                </span>
                <span style="font-size:.73rem; font-weight:700; color:var(--vl-text-main);">
                  {{ $tp->total }}
                </span>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>

    {{-- Por sector --}}
    <div class="col-lg-5">
      <div class="vl-card h-100">
        <div class="vl-card__accent"></div>
        <div class="vl-card__header">
          <h5 class="vl-card__title"><i class="bi bi-building text-primary"></i> Por Sector / Comité</h5>
          
        </div>
        <div class="vl-card__body vl-collapsible" id="body-sector2">
          @php
            $maxSec=$porSector->max('total')?: 1;
            $secCols=['#3b82f6','#10b981','#f59e0b','#7c3aed','#f43f5e','#0891b2'];
          @endphp
          @forelse($porSector as $i=>$sec)
            <div style="margin-bottom:11px;">
              <div style="display:flex; justify-content:space-between; margin-bottom:3px;">
                <span style="font-size:.75rem; font-weight:600; color:var(--vl-text-main);
                             overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:72%;">
                  {{ $sec->sector_o_comite ?? 'Sin sector' }}
                </span>
                <span style="font-size:.75rem; font-weight:700; color:{{ $secCols[$i%count($secCols)] }};">
                  {{ $sec->total }}
                </span>
              </div>
              <div style="background:var(--vl-border); border-radius:99px; height:7px; overflow:hidden;">
                <div style="width:{{ ($sec->total/$maxSec)*100 }}%; height:100%;
                            background:{{ $secCols[$i%count($secCols)] }};
                            border-radius:99px; transition:width .4s;"></div>
              </div>
            </div>
          @empty
            <p style="color:var(--vl-text-muted); font-size:.8rem;">Sin datos de sectores</p>
          @endforelse
        </div>
      </div>
    </div>

    {{-- Top 5 beneficiarios --}}
    <div class="col-12">
      <div class="vl-card">
        <div class="vl-card__accent"></div>
        <div class="vl-card__header">
          <h5 class="vl-card__title">
            <i class="bi bi-trophy-fill text-warning"></i> Top 5 — Más Raciones Recibidas
          </h5>
         
        </div>
        <div class="vl-card__body vl-collapsible" id="body-top5b" style="padding:0;">
          @php $maxTop=$topBeneficiarios->max('total') ?: 1; @endphp
          <table class="vl-table">
            <thead>
              <tr>
                <th style="width:40px;">#</th>
                <th>Beneficiario</th>
                <th>Tipo</th>
                <th>Raciones</th>
                <th style="width:30%;">Progreso</th>
              </tr>
            </thead>
            <tbody>
              @forelse($topBeneficiarios as $i=>$tb)
                <tr>
                  <td>
                    @if($i===0) <i class="bi bi-trophy-fill text-warning"></i>
                    @elseif($i===1) <i class="bi bi-trophy-fill text-secondary"></i>
                    @elseif($i===2) <i class="bi bi-trophy-fill" style="color:#cd7f32;"></i>
                    @else <span style="font-size:.75rem; color:var(--vl-text-muted);">{{ $i+1 }}</span>
                    @endif
                  </td>
                  <td>
                    <div class="vl-table__cell-user">
                      <div class="vl-table__mini-avatar">
                        {{ strtoupper(substr($tb->beneficiario->nombre??'B',0,1)) }}
                      </div>
                      <div>
                        <span class="vl-table__cell-name" style="font-size:.78rem;">
                          {{ strtoupper($tb->beneficiario->apellido??'') }}, {{ $tb->beneficiario->nombre??'—' }}
                        </span>
                        <span class="vl-table__cell-sub">{{ $tb->beneficiario->dni??'—' }}</span>
                      </div>
                    </div>
                  </td>
                  <td>
                    <span class="vl-badge vl-badge--blue" style="font-size:.62rem;">
                      {{ ucfirst($tb->beneficiario->tipo_beneficiario??'—') }}
                    </span>
                  </td>
                  <td style="font-weight:700; font-size:.85rem;">{{ $tb->total }}</td>
                  <td>
                    <div style="background:var(--vl-border); border-radius:99px; height:6px; overflow:hidden;">
                      <div style="width:{{ ($tb->total/$maxTop)*100 }}%; height:100%;
                                  background:linear-gradient(90deg,#f59e0b,#f97316);
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