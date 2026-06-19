<div class="stat-section print-section" id="stat-section-entregas">
  <div class="stat-section-card no-print">
    <div class="stat-section-header" style="display:flex; align-items:center; gap:10px; padding:14px 20px; border-bottom:1px solid var(--vl-border);">
      <div style="width:4px; height:22px; background:linear-gradient(135deg,#3b82f6,#6366f1); border-radius:99px;"></div>
      <h3 style="flex:1; font-size:.92rem; font-weight:800; color:var(--vl-text-main); margin:0;">
        <i class="bi bi-graph-up-arrow me-2 text-primary"></i>Entregas
      </h3>
      <button class="btn-collapse-section no-print" data-collapse-section="entregas" title="Colapsar">
        <i class="bi bi-chevron-up"></i>
      </button>
    </div>
    <div class="stat-section-body">
      <div class="row g-4">
    

    {{-- Línea: Últimos 7 días --}}
    <div class="col-lg-7">
      <div class="vl-card h-100">
        <div class="vl-card__accent"></div>
        <div class="vl-card__header">
          <h5 class="vl-card__title">
            <i class="bi bi-activity text-primary"></i>
            Entregas — Últimos 7 días
          </h5>
          
        </div>
        <div class="vl-card__body vl-collapsible" id="body-7dias">
          @php
            $max7 = $ultimos7Dias->max('total') ?: 1;
            $chart7H = 120;
          @endphp
          {{-- SVG Línea --}}
          <svg viewBox="0 0 420 {{ $chart7H + 30 }}" style="width:100%; max-width:100%;">
            {{-- Grid --}}
            @for($g=0;$g<=4;$g++)
              @php $gy = $chart7H - ($g/4)*$chart7H + 10; @endphp
              <line x1="30" y1="{{ $gy }}" x2="420" y2="{{ $gy }}"
                    stroke="var(--vl-border)" stroke-width="1" stroke-dasharray="4,4"/>
              <text x="24" y="{{ $gy+4 }}" font-size="9" fill="var(--vl-text-muted)"
                    text-anchor="end">{{ round(($g/4)*$max7) }}</text>
            @endfor
            {{-- Área + Línea --}}
            @php
              $pts = $ultimos7Dias->values();
              $n = $pts->count();
              $step = ($n > 1) ? (390 / ($n-1)) : 0;
              $coords = $pts->map(function($p, $i) use ($step, $chart7H, $max7) {
                return [
                  'x' => 30 + $i * $step,
                  'y' => $chart7H - (($p['total']/$max7)*($chart7H-10)) + 10,
                  'v' => $p['total'],
                  'l' => $p['fecha'],
                  'd' => $p['dia'],
                ];
              });
              $polyline = $coords->map(fn($c) => $c['x'].','.$c['y'])->join(' ');
              $area = '30,'.($chart7H+10).' '.$polyline.' '.($coords->last()['x']).','.($chart7H+10);
            @endphp
            <polygon points="{{ $area }}" fill="rgba(99,102,241,.12)"/>
            <polyline points="{{ $polyline }}" fill="none" stroke="#6366f1" stroke-width="2.5"
                      stroke-linejoin="round" stroke-linecap="round"/>
            {{-- Puntos + Labels --}}
            @foreach($coords as $c)
              <circle cx="{{ $c['x'] }}" cy="{{ $c['y'] }}" r="4" fill="#6366f1"
                      stroke="var(--vl-bg-card)" stroke-width="2"/>
              @if($c['v'] > 0)
                <text x="{{ $c['x'] }}" y="{{ $c['y']-9 }}" font-size="10" font-weight="700"
                      fill="#6366f1" text-anchor="middle">{{ $c['v'] }}</text>
              @endif
              <text x="{{ $c['x'] }}" y="{{ $chart7H+24 }}" font-size="9"
                    fill="var(--vl-text-muted)" text-anchor="middle">{{ $c['l'] }}</text>
              <text x="{{ $c['x'] }}" y="{{ $chart7H+35 }}" font-size="8"
                    fill="var(--vl-text-muted)" text-anchor="middle">{{ $c['d'] }}</text>
            @endforeach
          </svg>
        </div>
      </div>
    </div>

    {{-- Este mes vs anterior + Cobertura --}}
    <div class="col-lg-5">
      <div class="row g-4 h-100">

        {{-- Este mes vs anterior --}}
        <div class="col-12">
          <div class="vl-card">
            <div class="vl-card__accent"></div>
            <div class="vl-card__header">
              <h5 class="vl-card__title">
                <i class="bi bi-bar-chart-fill text-primary"></i>
                Comparativo Mensual
              </h5>
              
            </div>
            <div class="vl-card__body vl-collapsible" id="body-comp-mensual">
              @php
                $diff = $esteMes - $mesAnterior;
                $maxM = max($esteMes, $mesAnterior, 1);
              @endphp
              <div style="text-align:center; margin-bottom:16px;">
                <div style="font-size:2.2rem; font-weight:800;
                            color:{{ $diff>=0?'var(--vl-emerald)':'var(--vl-rose)' }};">
                  {{ $esteMes }}
                </div>
                <div style="font-size:.75rem; color:var(--vl-text-muted);">entregas este mes</div>
                <div style="font-size:.82rem; font-weight:700; margin-top:4px;
                            color:{{ $diff>=0?'var(--vl-emerald)':'var(--vl-rose)' }};">
                  <i class="bi bi-arrow-{{ $diff>=0?'up':'down' }}-circle-fill me-1"></i>
                  {{ $diff>=0?'+':'' }}{{ $diff }} vs mes anterior
                </div>
              </div>
              @foreach([['Este mes', $esteMes, '#6366f1'], ['Mes anterior', $mesAnterior, '#94a3b8']] as [$lbl,$val,$col])
                <div style="margin-bottom:10px;">
                  <div style="display:flex; justify-content:space-between; margin-bottom:4px;">
                    <span style="font-size:.75rem; color:var(--vl-text-sub);">{{ $lbl }}</span>
                    <span style="font-size:.75rem; font-weight:700; color:{{ $col }};">{{ $val }}</span>
                  </div>
                  <div style="background:var(--vl-border); border-radius:99px; height:8px; overflow:hidden;">
                    <div style="width:{{ ($maxM>0?($val/$maxM)*100:0) }}%; height:100%;
                                background:{{ $col }}; border-radius:99px; transition:width .4s;"></div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>

        {{-- Cobertura gauge --}}
        <div class="col-12">
          <div class="vl-card">
            <div class="vl-card__accent"></div>
            <div class="vl-card__header">
              <h5 class="vl-card__title">
                <i class="bi bi-speedometer2 text-primary"></i>
                Cobertura del Programa
              </h5>
              
            </div>
            <div class="vl-card__body vl-collapsible" id="body-cobertura" style="display:flex; flex-direction:column; align-items:center;">
              @php
                $gaugeVal = $coberturaActual;
                $gaugeAngle = min($gaugeVal / 100, 1) * 180;
                $cxG=100; $cyG=80; $rG=70; $rGI=50;
                $pxyS=fn($cx,$cy,$r,$deg)=>[$cx+$r*cos(deg2rad($deg)),$cy+$r*sin(deg2rad($deg))];
                [$gx1,$gy1]=$pxyS($cxG,$cyG,$rG,-180);
                [$gx2,$gy2]=$pxyS($cxG,$cyG,$rG,-180+$gaugeAngle);
                [$gix1,$giy1]=$pxyS($cxG,$cyG,$rGI,-180);
                [$gix2,$giy2]=$pxyS($cxG,$cyG,$rGI,-180+$gaugeAngle);
                $lgG=$gaugeAngle>180?1:0;
                $gaugeCol=$gaugeVal>=75?'#10b981':($gaugeVal>=50?'#f59e0b':'#f43f5e');
              @endphp
              <svg viewBox="0 0 200 95" style="width:180px;">
                @php
                  [$bgx1,$bgy1]=$pxyS($cxG,$cyG,$rG,-180);
                  [$bgx2,$bgy2]=$pxyS($cxG,$cyG,$rG,0);
                  [$bgix1,$bgiy1]=$pxyS($cxG,$cyG,$rGI,-180);
                  [$bgix2,$bgiy2]=$pxyS($cxG,$cyG,$rGI,0);
                @endphp
                <path d="M {{ $bgix1 }} {{ $bgy1 }} L {{ $bgx1 }} {{ $bgy1 }}
                         A {{ $rG }} {{ $rG }} 0 1 1 {{ $bgx2 }} {{ $bgy2 }}
                         L {{ $bgix2 }} {{ $bgiy2 }}
                         A {{ $rGI }} {{ $rGI }} 0 1 0 {{ $bgix1 }} {{ $bgy1 }} Z"
                      fill="var(--vl-border)"/>
                @if($gaugeAngle>0)
                  <path d="M {{ $gix1 }} {{ $giy1 }} L {{ $gx1 }} {{ $gy1 }}
                           A {{ $rG }} {{ $rG }} 0 {{ $lgG }} 1 {{ $gx2 }} {{ $gy2 }}
                           L {{ $gix2 }} {{ $giy2 }}
                           A {{ $rGI }} {{ $rGI }} 0 {{ $lgG }} 0 {{ $gix1 }} {{ $giy1 }} Z"
                        fill="{{ $gaugeCol }}"/>
                @endif
                <text x="{{ $cxG }}" y="{{ $cyG-6 }}" text-anchor="middle"
                      font-size="22" font-weight="800" fill="{{ $gaugeCol }}">
                  {{ $gaugeVal }}%
                </text>
                <text x="{{ $cxG }}" y="{{ $cyG+10 }}" text-anchor="middle"
                      font-size="9" fill="var(--vl-text-muted)">COBERTURA</text>
                <text x="14" y="{{ $cyG+4 }}" font-size="8" fill="var(--vl-text-muted)">0%</text>
                <text x="186" y="{{ $cyG+4 }}" font-size="8" fill="var(--vl-text-muted)"
                      text-anchor="end">100%</text>
              </svg>
              <div style="font-size:.72rem; color:var(--vl-text-muted); text-align:center; margin-top:4px;">
                {{ $esteMes }} de {{ $totalActivos }} beneficiarios este mes
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Barras: últimos 6 meses --}}
    <div class="col-lg-6">
      <div class="vl-card">
        <div class="vl-card__accent"></div>
        <div class="vl-card__header">
          <h5 class="vl-card__title">
            <i class="bi bi-bar-chart-steps text-primary"></i>
            Tendencia — Últimos 6 Meses
          </h5>
            
        </div>
        <div class="vl-card__body vl-collapsible" id="body-6m">
          @php $max6 = $ultimos6Meses->max('total') ?: 1; @endphp
          <div style="display:flex; align-items:flex-end; gap:8px; height:100px; padding:8px 0;">
            @foreach($ultimos6Meses as $m)
              @php $h = max(($m['total']/$max6)*90, 2); @endphp
              <div style="flex:1; display:flex; flex-direction:column; align-items:center; gap:4px;">
                <span style="font-size:.7rem; font-weight:700; color:#3b82f6;">
                  {{ $m['total'] ?: '' }}
                </span>
                <div style="width:100%; height:{{ $h }}px; background:linear-gradient(180deg,#6366f1,#3b82f6);
                            border-radius:6px 6px 2px 2px; transition:height .4s;"></div>
                <span style="font-size:.7rem; color:var(--vl-text-muted);">{{ $m['mes'] }}</span>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>

    {{-- Por día de semana --}}
    <div class="col-lg-6">
      <div class="vl-card">
        <div class="vl-card__accent"></div>
        <div class="vl-card__header">
          <h5 class="vl-card__title">
            <i class="bi bi-calendar-week text-primary"></i>
            Patrón por Día de Semana
          </h5>
          
        </div>
        <div class="vl-card__body vl-collapsible" id="body-semana">
          @php
            $maxSem = $porDiaSemana->max('total') ?: 1;
            $semCols = ['#3b82f6','#6366f1','#8b5cf6','#f59e0b','#10b981','#0891b2','#64748b'];
          @endphp
          <div style="display:flex; align-items:flex-end; gap:6px; height:100px; padding:8px 0;">
            @foreach($porDiaSemana as $i => $ds)
              @php $h = max(($ds['total']/$maxSem)*90, 2); $col = $semCols[$i % count($semCols)]; @endphp
              <div style="flex:1; display:flex; flex-direction:column; align-items:center; gap:4px;">
                <span style="font-size:.7rem; font-weight:700; color:{{ $col }};">
                  {{ $ds['total'] ?: '' }}
                </span>
                <div style="width:100%; height:{{ $h }}px; background:{{ $col }};
                            border-radius:6px 6px 2px 2px; opacity:.85;"></div>
                <span style="font-size:.68rem; color:var(--vl-text-muted);">{{ $ds['dia'] }}</span>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>{{-- /row --}}
    </div>{{-- /stat-section-body --}}
  </div>{{-- /stat-section-card --}}
</div>{{-- /stat-section --}}