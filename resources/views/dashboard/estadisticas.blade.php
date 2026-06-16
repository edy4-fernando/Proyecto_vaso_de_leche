@extends('layouts.dashboard')

@section('title', 'Estadísticas — Vaso de Leche')
@section('breadcrumb', 'Estadísticas')
@php $activeModule = 'estadisticas'; @endphp

@section('content')

{{-- ── FILTRO DE FECHAS ── --}}
<div class="vl-card mb-4 vl-animate">
  <div class="vl-card__body" style="padding:14px 20px;">
    <form method="GET" action="{{ route('dashboard.index') }}">
      <div class="vl-search-bar">
        <div style="display:flex; align-items:center; gap:8px; flex:1; flex-wrap:wrap;">
          <span style="font-size:.78rem; font-weight:600; color:var(--vl-text-sub); white-space:nowrap;">
            <i class="bi bi-calendar3 me-1"></i> Rango de fechas:
          </span>
          <input type="date" name="desde" class="vl-input"
                 value="{{ request('desde', now()->subDays(30)->format('Y-m-d')) }}"
                 style="width:150px;">
          <span style="color:var(--vl-text-muted); font-size:.8rem;">hasta</span>
          <input type="date" name="hasta" class="vl-input"
                 value="{{ request('hasta', now()->format('Y-m-d')) }}"
                 style="width:150px;">
        </div>
        <div style="display:flex; gap:8px; flex-wrap:wrap;">
          {{-- Atajos rápidos --}}
          <a href="{{ route('dashboard.index', ['desde' => now()->format('Y-m-d'), 'hasta' => now()->format('Y-m-d')]) }}"
             class="vl-btn vl-btn--outline vl-btn--sm">Hoy</a>
          <a href="{{ route('dashboard.index', ['desde' => now()->startOfWeek()->format('Y-m-d'), 'hasta' => now()->format('Y-m-d')]) }}"
             class="vl-btn vl-btn--outline vl-btn--sm">Esta semana</a>
          <a href="{{ route('dashboard.index', ['desde' => now()->startOfMonth()->format('Y-m-d'), 'hasta' => now()->format('Y-m-d')]) }}"
             class="vl-btn vl-btn--outline vl-btn--sm">Este mes</a>
          <a href="{{ route('dashboard.index', ['desde' => now()->subDays(30)->format('Y-m-d'), 'hasta' => now()->format('Y-m-d')]) }}"
             class="vl-btn vl-btn--outline vl-btn--sm">30 días</a>
          <button type="submit" class="vl-btn vl-btn--primary vl-btn--sm">
            <i class="bi bi-funnel-fill"></i> Aplicar
          </button>
          @if(request()->hasAny(['desde','hasta']))
            <a href="{{ route('dashboard.index') }}"
               class="vl-btn vl-btn--ghost vl-btn--sm">
              <i class="bi bi-x-circle"></i>
            </a>
          @endif
        </div>
      </div>
    </form>
  </div>
</div>

{{-- ── STAT CARDS ── --}}
<div class="row g-3 mb-4">
  <div class="col-6 col-lg-2 vl-animate">
    <div class="vl-stat">
      <div class="vl-stat__icon vl-stat__icon--blue">
        <i class="bi bi-people-fill"></i>
      </div>
      <div class="vl-stat__body">
        <div class="vl-stat__value">{{ $totalBeneficiarios }}</div>
        <div class="vl-stat__label">Total padrón</div>
      </div>
    </div>
  </div>
  <div class="col-6 col-lg-2 vl-animate vl-animate--delay-1">
    <div class="vl-stat">
      <div class="vl-stat__icon vl-stat__icon--green">
        <i class="bi bi-person-check-fill"></i>
      </div>
      <div class="vl-stat__body">
        <div class="vl-stat__value">{{ $totalActivos }}</div>
        <div class="vl-stat__label">Activos</div>
      </div>
    </div>
  </div>
  <div class="col-6 col-lg-2 vl-animate vl-animate--delay-2">
    <div class="vl-stat">
      <div class="vl-stat__icon vl-stat__icon--violet">
        <i class="bi bi-journal-check"></i>
      </div>
      <div class="vl-stat__body">
        <div class="vl-stat__value">{{ $totalEntregas }}</div>
        <div class="vl-stat__label">Total entregas</div>
      </div>
    </div>
  </div>
  <div class="col-6 col-lg-2 vl-animate vl-animate--delay-3">
    <div class="vl-stat">
      <div class="vl-stat__icon vl-stat__icon--amber">
        <i class="bi bi-calendar-day"></i>
      </div>
      <div class="vl-stat__body">
        <div class="vl-stat__value">{{ $entregasHoy }}</div>
        <div class="vl-stat__label">Hoy</div>
      </div>
    </div>
  </div>
  <div class="col-6 col-lg-2 vl-animate vl-animate--delay-1">
    <div class="vl-stat">
      <div class="vl-stat__icon vl-stat__icon--rose">
        <i class="bi bi-exclamation-triangle-fill"></i>
      </div>
      <div class="vl-stat__body">
        <div class="vl-stat__value">{{ $stockCritico }}</div>
        <div class="vl-stat__label">Stock crítico</div>
      </div>
    </div>
  </div>
  <div class="col-6 col-lg-2 vl-animate vl-animate--delay-2">
    <div class="vl-stat">
      <div class="vl-stat__icon vl-stat__icon--green">
        <i class="bi bi-percent"></i>
      </div>
      <div class="vl-stat__body">
        <div class="vl-stat__value">{{ $coberturaActual }}%</div>
        <div class="vl-stat__label">Cobertura mes</div>
      </div>
    </div>
  </div>
</div>

{{-- ══════════════════════════════════════════════════════
     SECCIÓN: ENTREGAS
     ══════════════════════════════════════════════════════ --}}
<div class="vl-seccion-titulo vl-animate">
  <div style="display:flex; align-items:center; gap:10px; margin-bottom:16px;">
    <div style="width:4px; height:24px; background:var(--db-accent-gradient, linear-gradient(135deg,#3b82f6,#7c3aed)); border-radius:99px;"></div>
    <h3 style="font-size:.95rem; font-weight:800; color:var(--vl-text-main); margin:0; letter-spacing:-.3px;">
      <i class="bi bi-journal-check me-2" style="color:var(--db-sidebar-accent, #3b82f6);"></i>
      Análisis de Entregas
    </h3>
    <span class="vl-badge vl-badge--blue">{{ $totalEntregas }} registros totales</span>
  </div>
</div>

<div class="row g-4 mb-4">

  {{-- Línea 7 días --}}
  <div class="col-lg-7 vl-animate">
    <div class="vl-card" id="card-linea7">
      <div class="vl-card__accent"></div>
      <div class="vl-card__header">
        <h5 class="vl-card__title">
          <i class="bi bi-graph-up text-primary"></i>
          Entregas — Últimos 7 Días
          <span class="vl-badge vl-badge--slate ms-2" style="font-size:.65rem;">Línea</span>
        </h5>
        <button class="vl-btn vl-btn--ghost vl-btn--icon vl-collapse-btn"
                data-target="body-linea7" title="Colapsar">
          <i class="bi bi-chevron-up"></i>
        </button>
      </div>
      <div class="vl-card__body vl-collapsible" id="body-linea7">
        @php
          $max7  = $ultimos7Dias->max('total') ?: 1;
          $w7    = 560; $h7 = 180; $pad7 = 40;
          $n7    = $ultimos7Dias->count();
          $step7 = $n7 > 1 ? ($w7 - $pad7 * 2) / ($n7 - 1) : 0;
          $pts7  = [];
          $area7 = [];
          foreach ($ultimos7Dias->values() as $i => $d) {
            $x = $pad7 + $i * $step7;
            $y = $h7 - $pad7 - (($d['total'] / $max7) * ($h7 - $pad7 * 1.5));
            $pts7[] = ['x'=>$x,'y'=>$y,'total'=>$d['total'],'fecha'=>$d['fecha'],'dia'=>$d['dia']];
            $area7[] = "L $x,$y";
          }
          $poly7Parts = [];
          foreach ($pts7 as $p) { $poly7Parts[] = $p['x'].','.$p['y']; }
          $poly7  = implode(' ', $poly7Parts);
          $first7 = $pts7[0] ?? ['x'=>$pad7,'y'=>$h7-$pad7];
          $last7  = $pts7[count($pts7)-1] ?? ['x'=>$pad7,'y'=>$h7-$pad7];
          $areaPath7 = 'M '.$first7['x'].','.($h7-$pad7).' '.implode(' ',$area7).' L '.$last7['x'].','.($h7-$pad7).' Z';
        @endphp
        <svg viewBox="0 0 {{ $w7 }} {{ $h7 }}" style="width:100%; overflow:visible;">
          <defs>
            <linearGradient id="gLine7" x1="0" y1="0" x2="0" y2="1">
              <stop offset="0%" stop-color="#3b82f6" stop-opacity=".3"/>
              <stop offset="100%" stop-color="#3b82f6" stop-opacity="0"/>
            </linearGradient>
          </defs>
          @for($i=0;$i<=4;$i++)
            @php $gy = $pad7 + ($i/4)*($h7-$pad7*1.5); @endphp
            <line x1="{{ $pad7 }}" y1="{{ $gy }}" x2="{{ $w7-$pad7 }}" y2="{{ $gy }}"
                  stroke="var(--vl-border)" stroke-width="1" stroke-dasharray="4,4"/>
            <text x="{{ $pad7-6 }}" y="{{ $gy+4 }}" font-size="9"
                  fill="var(--vl-text-muted)" text-anchor="end">
              {{ round($max7 - ($i/4)*$max7) }}
            </text>
          @endfor
          <path d="{{ $areaPath7 }}" fill="url(#gLine7)"/>
          <polyline points="{{ $poly7 }}" fill="none" stroke="#3b82f6"
                    stroke-width="2.5" stroke-linejoin="round" stroke-linecap="round"/>
          @foreach($pts7 as $p)
            <circle cx="{{ $p['x'] }}" cy="{{ $p['y'] }}" r="5"
                    fill="#3b82f6" stroke="var(--vl-bg-card)" stroke-width="2"/>
            <text x="{{ $p['x'] }}" y="{{ $h7-$pad7+14 }}" font-size="9"
                  fill="var(--vl-text-muted)" text-anchor="middle">{{ $p['dia'] }}</text>
            <text x="{{ $p['x'] }}" y="{{ $h7-$pad7+24 }}" font-size="8"
                  fill="var(--vl-text-muted)" text-anchor="middle">{{ $p['fecha'] }}</text>
            @if($p['total']>0)
              <text x="{{ $p['x'] }}" y="{{ $p['y']-10 }}" font-size="9"
                    fill="#3b82f6" text-anchor="middle" font-weight="700">
                {{ $p['total'] }}
              </text>
            @endif
          @endforeach
        </svg>
      </div>
    </div>
  </div>

  {{-- Barras 6 meses --}}
  <div class="col-lg-5 vl-animate vl-animate--delay-1">
    <div class="vl-card" id="card-barras6">
      <div class="vl-card__accent"></div>
      <div class="vl-card__header">
        <h5 class="vl-card__title">
          <i class="bi bi-bar-chart-fill text-primary"></i>
          Últimos 6 Meses
          <span class="vl-badge vl-badge--slate ms-2" style="font-size:.65rem;">Barras</span>
        </h5>
        <button class="vl-btn vl-btn--ghost vl-btn--icon vl-collapse-btn"
                data-target="body-barras6" title="Colapsar">
          <i class="bi bi-chevron-up"></i>
        </button>
      </div>
      <div class="vl-card__body vl-collapsible" id="body-barras6">
        @php
          $max6  = $ultimos6Meses->max('total') ?: 1;
          $w6=360; $h6=180; $pad6=40; $n6=$ultimos6Meses->count();
          $gap6  = $n6 > 0 ? ($w6-$pad6*2)/$n6 : 1;
          $bw6   = $gap6*0.55;
        @endphp
        <svg viewBox="0 0 {{ $w6 }} {{ $h6 }}" style="width:100%; overflow:visible;">
          <defs>
            <linearGradient id="gBar6" x1="0" y1="0" x2="0" y2="1">
              <stop offset="0%" stop-color="#7c3aed"/>
              <stop offset="100%" stop-color="#3b82f6"/>
            </linearGradient>
          </defs>
          @for($i=0;$i<=4;$i++)
            @php $gy6=$pad6+($i/4)*($h6-$pad6*1.5); @endphp
            <line x1="{{ $pad6 }}" y1="{{ $gy6 }}" x2="{{ $w6-$pad6 }}" y2="{{ $gy6 }}"
                  stroke="var(--vl-border)" stroke-width="1" stroke-dasharray="4,4"/>
          @endfor
          @foreach($ultimos6Meses->values() as $i => $m)
            @php
              $cx6 = $pad6+$i*$gap6+$gap6/2;
              $bh6 = ($m['total']/$max6)*($h6-$pad6*1.5);
              $by6 = $h6-$pad6-$bh6;
              $bx6 = $cx6-$bw6/2;
            @endphp
            <rect x="{{ $bx6 }}" y="{{ $by6 }}" width="{{ $bw6 }}" height="{{ $bh6 }}"
                  fill="url(#gBar6)" rx="4"/>
            <text x="{{ $cx6 }}" y="{{ $h6-$pad6+14 }}" font-size="9"
                  fill="var(--vl-text-muted)" text-anchor="middle">{{ $m['mes'] }}</text>
            @if($m['total']>0)
              <text x="{{ $cx6 }}" y="{{ $by6-5 }}" font-size="9"
                    fill="#7c3aed" text-anchor="middle" font-weight="700">
                {{ $m['total'] }}
              </text>
            @endif
          @endforeach
          <line x1="{{ $pad6 }}" y1="{{ $h6-$pad6 }}" x2="{{ $w6-$pad6 }}" y2="{{ $h6-$pad6 }}"
                stroke="var(--vl-border)" stroke-width="1"/>
        </svg>
      </div>
    </div>
  </div>

  {{-- Área acumulada 12 meses --}}
  <div class="col-lg-6 vl-animate">
    <div class="vl-card">
      <div class="vl-card__accent"></div>
      <div class="vl-card__header">
        <h5 class="vl-card__title">
          <i class="bi bi-graph-up-arrow text-primary"></i>
          Tendencia Anual — 12 Meses
          <span class="vl-badge vl-badge--slate ms-2" style="font-size:.65rem;">Área</span>
        </h5>
        <button class="vl-btn vl-btn--ghost vl-btn--icon vl-collapse-btn"
                data-target="body-acum" title="Colapsar">
          <i class="bi bi-chevron-up"></i>
        </button>
      </div>
      <div class="vl-card__body vl-collapsible" id="body-acum">
        @php
          $maxAc = $acumulado->max('total') ?: 1;
          $wAc=540; $hAc=160; $padAc=35;
          $nAc=$acumulado->count();
          $stepAc=$nAc>1?($wAc-$padAc*2)/($nAc-1):0;
          $ptsAc=[]; $areaAc=[];
          foreach($acumulado->values() as $i=>$d){
            $x=$padAc+$i*$stepAc;
            $y=$hAc-$padAc-(($d['total']/$maxAc)*($hAc-$padAc*1.5));
            $ptsAc[]=['x'=>$x,'y'=>$y,'total'=>$d['total'],'mes'=>$d['mes']];
            $areaAc[]="L $x,$y";
          }
          $polyAcParts=[];
          foreach($ptsAc as $p){$polyAcParts[]=$p['x'].','.$p['y'];}
          $polyAc=implode(' ',$polyAcParts);
          $f=$ptsAc[0]??['x'=>$padAc,'y'=>$hAc-$padAc];
          $l=$ptsAc[count($ptsAc)-1]??['x'=>$padAc,'y'=>$hAc-$padAc];
          $areaPathAc='M '.$f['x'].','.($hAc-$padAc).' '.implode(' ',$areaAc).' L '.$l['x'].','.($hAc-$padAc).' Z';
        @endphp
        <svg viewBox="0 0 {{ $wAc }} {{ $hAc }}" style="width:100%; overflow:visible;">
          <defs>
            <linearGradient id="gAcum" x1="0" y1="0" x2="0" y2="1">
              <stop offset="0%" stop-color="#10b981" stop-opacity=".35"/>
              <stop offset="100%" stop-color="#10b981" stop-opacity="0"/>
            </linearGradient>
          </defs>
          @for($i=0;$i<=3;$i++)
            @php $gyA=$padAc+($i/3)*($hAc-$padAc*1.5); @endphp
            <line x1="{{ $padAc }}" y1="{{ $gyA }}" x2="{{ $wAc-$padAc }}" y2="{{ $gyA }}"
                  stroke="var(--vl-border)" stroke-width="1" stroke-dasharray="3,3"/>
          @endfor
          <path d="{{ $areaPathAc }}" fill="url(#gAcum)"/>
          <polyline points="{{ $polyAc }}" fill="none" stroke="#10b981"
                    stroke-width="2" stroke-linejoin="round" stroke-linecap="round"/>
          @foreach($ptsAc as $p)
            <circle cx="{{ $p['x'] }}" cy="{{ $p['y'] }}" r="3"
                    fill="#10b981" stroke="var(--vl-bg-card)" stroke-width="1.5"/>
            <text x="{{ $p['x'] }}" y="{{ $hAc-$padAc+12 }}" font-size="8"
                  fill="var(--vl-text-muted)" text-anchor="middle">{{ $p['mes'] }}</text>
          @endforeach
        </svg>
      </div>
    </div>
  </div>

  {{-- Entregas por día de la semana --}}
  <div class="col-lg-6 vl-animate vl-animate--delay-1">
    <div class="vl-card">
      <div class="vl-card__accent"></div>
      <div class="vl-card__header">
        <h5 class="vl-card__title">
          <i class="bi bi-calendar-week text-primary"></i>
          Entregas por Día de la Semana
          <span class="vl-badge vl-badge--slate ms-2" style="font-size:.65rem;">Barras</span>
        </h5>
        <button class="vl-btn vl-btn--ghost vl-btn--icon vl-collapse-btn"
                data-target="body-semana" title="Colapsar">
          <i class="bi bi-chevron-up"></i>
        </button>
      </div>
      <div class="vl-card__body vl-collapsible" id="body-semana">
        @php
          $maxSem=$porDiaSemana->max('total')?: 1;
          $wSem=420; $hSem=160; $padSem=35;
          $nSem=$porDiaSemana->count();
          $gapSem=($wSem-$padSem*2)/$nSem;
          $bwSem=$gapSem*0.6;
          $semColors=['#3b82f6','#10b981','#f59e0b','#7c3aed','#f43f5e','#0891b2','#64748b'];
        @endphp
        <svg viewBox="0 0 {{ $wSem }} {{ $hSem }}" style="width:100%; overflow:visible;">
          @for($i=0;$i<=3;$i++)
            @php $gySem=$padSem+($i/3)*($hSem-$padSem*1.5); @endphp
            <line x1="{{ $padSem }}" y1="{{ $gySem }}"
                  x2="{{ $wSem-$padSem }}" y2="{{ $gySem }}"
                  stroke="var(--vl-border)" stroke-width="1" stroke-dasharray="3,3"/>
          @endfor
          @foreach($porDiaSemana as $i=>$d)
            @php
              $cx=$padSem+$i*$gapSem+$gapSem/2;
              $bh=($d['total']/$maxSem)*($hSem-$padSem*1.5);
              $by=$hSem-$padSem-$bh;
              $bx=$cx-$bwSem/2;
              $col=$semColors[$i%count($semColors)];
            @endphp
            <rect x="{{ $bx }}" y="{{ $by }}" width="{{ $bwSem }}" height="{{ $bh }}"
                  fill="{{ $col }}" rx="4" opacity=".85"/>
            <text x="{{ $cx }}" y="{{ $hSem-$padSem+13 }}" font-size="9"
                  fill="var(--vl-text-muted)" text-anchor="middle">{{ $d['dia'] }}</text>
            @if($d['total']>0)
              <text x="{{ $cx }}" y="{{ $by-4 }}" font-size="9"
                    fill="{{ $col }}" text-anchor="middle" font-weight="700">
                {{ $d['total'] }}
              </text>
            @endif
          @endforeach
          <line x1="{{ $padSem }}" y1="{{ $hSem-$padSem }}"
                x2="{{ $wSem-$padSem }}" y2="{{ $hSem-$padSem }}"
                stroke="var(--vl-border)" stroke-width="1"/>
        </svg>
      </div>
    </div>
  </div>

  {{-- Comparativa mensual --}}
  <div class="col-lg-4 vl-animate">
    <div class="vl-card h-100">
      <div class="vl-card__accent"></div>
      <div class="vl-card__header">
        <h5 class="vl-card__title">
          <i class="bi bi-arrow-left-right text-primary"></i>
          Comparativa Mensual
        </h5>
        <button class="vl-btn vl-btn--ghost vl-btn--icon vl-collapse-btn"
                data-target="body-comp" title="Colapsar">
          <i class="bi bi-chevron-up"></i>
        </button>
      </div>
      <div class="vl-card__body vl-collapsible" id="body-comp">
        @php
          $maxM=max($esteMes,$mesAnterior,1);
          $diff=$esteMes-$mesAnterior;
        @endphp
        <div style="text-align:center; padding:16px; background:var(--vl-bg-body);
                    border-radius:var(--vl-radius); margin-bottom:16px;">
          <div style="font-size:2.2rem; font-weight:800;
                      color:{{ $diff>=0?'var(--vl-emerald)':'var(--vl-rose)' }};">
            {{ $esteMes }}
          </div>
          <div style="font-size:.72rem; color:var(--vl-text-muted);">este mes</div>
          <div style="font-size:.82rem; font-weight:700; margin-top:6px;
                      color:{{ $diff>=0?'var(--vl-emerald)':'var(--vl-rose)' }};">
            <i class="bi bi-arrow-{{ $diff>=0?'up':'down' }}-circle-fill me-1"></i>
            {{ $diff>=0?'+':'' }}{{ $diff }} vs mes anterior
          </div>
        </div>
        @foreach([['Este mes',$esteMes,'#3b82f6'],['Mes anterior',$mesAnterior,'#94a3b8']] as [$lbl,$val,$col])
          <div style="margin-bottom:12px;">
            <div style="display:flex; justify-content:space-between; margin-bottom:4px;">
              <span style="font-size:.75rem; color:var(--vl-text-sub);">{{ $lbl }}</span>
              <span style="font-size:.75rem; font-weight:700; color:{{ $col }};">{{ $val }}</span>
            </div>
            <div style="background:var(--vl-border); border-radius:99px; height:8px; overflow:hidden;">
              <div style="width:{{ ($val/$maxM)*100 }}%; height:100%;
                          background:{{ $col }}; border-radius:99px;"></div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>

</div>

{{-- ══════════════════════════════════════════════════════
     SECCIÓN: BENEFICIARIOS
     ══════════════════════════════════════════════════════ --}}
<div class="vl-animate" style="margin-bottom:16px;">
  <div style="display:flex; align-items:center; gap:10px;">
    <div style="width:4px; height:24px; background:linear-gradient(135deg,#10b981,#059669); border-radius:99px;"></div>
    <h3 style="font-size:.95rem; font-weight:800; color:var(--vl-text-main); margin:0;">
      <i class="bi bi-people-fill me-2" style="color:#10b981;"></i>
      Análisis de Beneficiarios
    </h3>
    <span class="vl-badge vl-badge--green">{{ $totalBeneficiarios }} en padrón</span>
  </div>
</div>

<div class="row g-4 mb-4">

  {{-- Torta por tipo --}}
  <div class="col-lg-4 vl-animate">
    <div class="vl-card h-100">
      <div class="vl-card__accent"></div>
      <div class="vl-card__header">
        <h5 class="vl-card__title">
          <i class="bi bi-pie-chart-fill text-primary"></i>
          Por Tipo de Beneficiario
          <span class="vl-badge vl-badge--slate ms-2" style="font-size:.65rem;">Torta</span>
        </h5>
        <button class="vl-btn vl-btn--ghost vl-btn--icon vl-collapse-btn"
                data-target="body-torta" title="Colapsar">
          <i class="bi bi-chevron-up"></i>
        </button>
      </div>
      <div class="vl-card__body vl-collapsible" id="body-torta"
           style="display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
        @php
          $colTorta=['#3b82f6','#10b981','#f59e0b','#f43f5e','#7c3aed','#0891b2','#64748b'];
          $totalTipo=$porTipo->sum('total')?: 1;
          $cxT=85; $cyT=85; $rT=70; $rTI=38;
          $startT=-90; $slicesT=[];
          foreach($porTipo as $i=>$t){
            $pct=$t->total/$totalTipo;
            $ang=$pct*360;
            $slicesT[]=['pct'=>$pct,'angle'=>$ang,'start'=>$startT,
              'label'=>$t->tipo_beneficiario,'total'=>$t->total,
              'color'=>$colTorta[$i%count($colTorta)]];
            $startT+=$ang;
          }
          function pxy($cx,$cy,$r,$deg){
            $rad=deg2rad($deg);
            return [$cx+$r*cos($rad),$cy+$r*sin($rad)];
          }
        @endphp
        <svg viewBox="0 0 170 170" style="width:150px; flex-shrink:0;">
          @foreach($slicesT as $s)
            @php
              [$x1,$y1]=pxy($cxT,$cyT,$rT,$s['start']);
              [$x2,$y2]=pxy($cxT,$cyT,$rT,$s['start']+$s['angle']);
              [$ix1,$iy1]=pxy($cxT,$cyT,$rTI,$s['start']);
              [$ix2,$iy2]=pxy($cxT,$cyT,$rTI,$s['start']+$s['angle']);
              $lg=$s['angle']>180?1:0;
            @endphp
            <path d="M {{ $ix1 }} {{ $iy1 }} L {{ $x1 }} {{ $y1 }}
                     A {{ $rT }} {{ $rT }} 0 {{ $lg }} 1 {{ $x2 }} {{ $y2 }}
                     L {{ $ix2 }} {{ $iy2 }}
                     A {{ $rTI }} {{ $rTI }} 0 {{ $lg }} 0 {{ $ix1 }} {{ $iy1 }} Z"
                  fill="{{ $s['color'] }}" stroke="var(--vl-bg-card)" stroke-width="2"/>
          @endforeach
          <text x="{{ $cxT }}" y="{{ $cyT-5 }}" text-anchor="middle"
                font-size="16" font-weight="800" fill="var(--vl-text-main)">
            {{ $totalBeneficiarios }}
          </text>
          <text x="{{ $cxT }}" y="{{ $cyT+10 }}" text-anchor="middle"
                font-size="8" fill="var(--vl-text-muted)">TOTAL</text>
        </svg>
        <div style="flex:1; min-width:0;">
          @foreach($slicesT as $s)
            <div style="display:flex; align-items:center; gap:6px; margin-bottom:7px;">
              <div style="width:8px; height:8px; border-radius:50%;
                          background:{{ $s['color'] }}; flex-shrink:0;"></div>
              <div style="flex:1; min-width:0;">
                <div style="font-size:.72rem; font-weight:600; color:var(--vl-text-main);
                            white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                  {{ ucfirst($s['label'] ?? 'Sin tipo') }}
                </div>
                <div style="font-size:.65rem; color:var(--vl-text-muted);">
                  {{ $s['total'] }} ({{ round($s['pct']*100) }}%)
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>

  {{-- Semicírculo activos/baja --}}
  <div class="col-lg-4 vl-animate vl-animate--delay-1">
    <div class="vl-card h-100">
      <div class="vl-card__accent"></div>
      <div class="vl-card__header">
        <h5 class="vl-card__title">
          <i class="bi bi-person-check-fill text-primary"></i>
          Estado del Padrón
          <span class="vl-badge vl-badge--slate ms-2" style="font-size:.65rem;">Semicírculo</span>
        </h5>
        <button class="vl-btn vl-btn--ghost vl-btn--icon vl-collapse-btn"
                data-target="body-semi" title="Colapsar">
          <i class="bi bi-chevron-up"></i>
        </button>
      </div>
      <div class="vl-card__body vl-collapsible" id="body-semi" style="text-align:center;">
        @php
          $pctSemi=$totalBeneficiarios>0?($totalActivos/$totalBeneficiarios)*180:0;
          $cxS=100; $cyS=100; $rS=75; $rSI=50;
          [$sax1,$say1]=pxy($cxS,$cyS,$rS,180);
          [$sax2,$say2]=pxy($cxS,$cyS,$rS,180+$pctSemi);
          [$saix1,$saiy1]=pxy($cxS,$cyS,$rSI,180);
          [$saix2,$saiy2]=pxy($cxS,$cyS,$rSI,180+$pctSemi);
          $laS=$pctSemi>180?1:0;
          [$six1,$siy1]=pxy($cxS,$cyS,$rS,180+$pctSemi);
          [$six2,$siy2]=pxy($cxS,$cyS,$rS,360);
          [$siix1,$siiy1]=pxy($cxS,$cyS,$rSI,180+$pctSemi);
          [$siix2,$siiy2]=pxy($cxS,$cyS,$rSI,360);
          $liS=(180-$pctSemi)>180?1:0;
        @endphp
        <svg viewBox="0 0 200 115" style="width:190px;">
          @if($totalInactivos>0)
            <path d="M {{ $siix1 }} {{ $siiy1 }} L {{ $six1 }} {{ $siy1 }}
                     A {{ $rS }} {{ $rS }} 0 {{ $liS }} 1 {{ $six2 }} {{ $siy2 }}
                     L {{ $siix2 }} {{ $siiy2 }}
                     A {{ $rSI }} {{ $rSI }} 0 {{ $liS }} 0 {{ $siix1 }} {{ $siiy1 }} Z"
                  fill="#e2e8f0"/>
          @endif
          @if($totalActivos>0)
            <path d="M {{ $saix1 }} {{ $saiy1 }} L {{ $sax1 }} {{ $say1 }}
                     A {{ $rS }} {{ $rS }} 0 {{ $laS }} 1 {{ $sax2 }} {{ $say2 }}
                     L {{ $saix2 }} {{ $saiy2 }}
                     A {{ $rSI }} {{ $rSI }} 0 {{ $laS }} 0 {{ $saix1 }} {{ $saiy1 }} Z"
                  fill="#10b981"/>
          @endif
          <text x="{{ $cxS }}" y="{{ $cyS-5 }}" text-anchor="middle"
                font-size="18" font-weight="800" fill="var(--vl-text-main)">
            {{ $totalBeneficiarios>0?round(($totalActivos/$totalBeneficiarios)*100):0 }}%
          </text>
          <text x="{{ $cxS }}" y="{{ $cyS+10 }}" text-anchor="middle"
                font-size="9" fill="var(--vl-text-muted)">ACTIVOS</text>
        </svg>
        <div style="display:flex; justify-content:center; gap:24px; margin-top:12px;">
          <div style="text-align:center;">
            <div style="font-size:1.3rem; font-weight:800; color:#10b981;">{{ $totalActivos }}</div>
            <div style="font-size:.7rem; color:var(--vl-text-muted);">Activos</div>
          </div>
          <div style="width:1px; background:var(--vl-border);"></div>
          <div style="text-align:center;">
            <div style="font-size:1.3rem; font-weight:800; color:var(--vl-text-muted);">{{ $totalInactivos }}</div>
            <div style="font-size:.7rem; color:var(--vl-text-muted);">De baja</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Barras por sector --}}
  <div class="col-lg-4 vl-animate vl-animate--delay-2">
    <div class="vl-card h-100">
      <div class="vl-card__accent"></div>
      <div class="vl-card__header">
        <h5 class="vl-card__title">
          <i class="bi bi-building text-primary"></i>
          Por Sector / Comité
          <span class="vl-badge vl-badge--slate ms-2" style="font-size:.65rem;">Barras</span>
        </h5>
        <button class="vl-btn vl-btn--ghost vl-btn--icon vl-collapse-btn"
                data-target="body-sector" title="Colapsar">
          <i class="bi bi-chevron-up"></i>
        </button>
      </div>
      <div class="vl-card__body vl-collapsible" id="body-sector">
        @php
          $maxSec=$porSector->max('total')?: 1;
          $secCols=['#3b82f6','#10b981','#f59e0b','#7c3aed','#f43f5e','#0891b2'];
        @endphp
        @forelse($porSector as $i=>$sec)
          <div style="margin-bottom:12px;">
            <div style="display:flex; justify-content:space-between; margin-bottom:4px;">
              <span style="font-size:.75rem; font-weight:600; color:var(--vl-text-main);
                           overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:70%;">
                {{ $sec->sector_o_comite ?? 'Sin sector' }}
              </span>
              <span style="font-size:.75rem; font-weight:700;
                           color:{{ $secCols[$i%count($secCols)] }};">
                {{ $sec->total }}
              </span>
            </div>
            <div style="background:var(--vl-border); border-radius:99px; height:7px; overflow:hidden;">
              <div style="width:{{ ($sec->total/$maxSec)*100 }}%; height:100%;
                          background:{{ $secCols[$i%count($secCols)] }};
                          border-radius:99px;"></div>
            </div>
          </div>
        @empty
          <div class="vl-table__empty">
            <i class="bi bi-building"></i>
            <p>Sin datos de sectores.</p>
          </div>
        @endforelse
      </div>
    </div>
  </div>

</div>

{{-- ══════════════════════════════════════════════════════
     SECCIÓN: INVENTARIO
     ══════════════════════════════════════════════════════ --}}
<div class="vl-animate" style="margin-bottom:16px;">
  <div style="display:flex; align-items:center; gap:10px;">
    <div style="width:4px; height:24px; background:linear-gradient(135deg,#f59e0b,#d97706); border-radius:99px;"></div>
    <h3 style="font-size:.95rem; font-weight:800; color:var(--vl-text-main); margin:0;">
      <i class="bi bi-box-seam-fill me-2" style="color:#f59e0b;"></i>
      Análisis de Inventario
    </h3>
    <span class="vl-badge vl-badge--amber">{{ $totalProductos }} productos</span>
  </div>
</div>

<div class="row g-4 mb-4">

  {{-- Barras horizontales stock --}}
  <div class="col-lg-7 vl-animate">
    <div class="vl-card">
      <div class="vl-card__accent"></div>
      <div class="vl-card__header">
        <h5 class="vl-card__title">
          <i class="bi bi-box-seam text-primary"></i>
          Stock Actual por Producto
          <span class="vl-badge vl-badge--slate ms-2" style="font-size:.65rem;">Barras horiz.</span>
        </h5>
        <button class="vl-btn vl-btn--ghost vl-btn--icon vl-collapse-btn"
                data-target="body-stock" title="Colapsar">
          <i class="bi bi-chevron-up"></i>
        </button>
      </div>
      <div class="vl-card__body vl-collapsible" id="body-stock">
        @php $maxStock=$stockProductos->max('stock_actual')?: 1; @endphp
        @forelse($stockProductos as $prod)
          @php
            $pctP=($prod->stock_actual/$maxStock)*100;
            $critico=$prod->stock_actual<=$prod->stock_minimo;
            $colP=$critico?'#f43f5e':'#10b981';
          @endphp
          <div style="margin-bottom:14px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
              <span style="font-size:.78rem; font-weight:600; color:var(--vl-text-main);">
                {{ $prod->nombre }}
                @if($prod->marca)
                  <span style="color:var(--vl-text-muted); font-weight:400;">— {{ $prod->marca }}</span>
                @endif
              </span>
              <span style="font-size:.75rem; font-weight:700; color:{{ $colP }};">
                {{ $prod->stock_actual }} {{ $prod->unidad_medida }}
                @if($critico) <i class="bi bi-exclamation-triangle-fill ms-1"></i> @endif
              </span>
            </div>
            <div style="background:var(--vl-border); border-radius:99px; height:8px; overflow:hidden;">
              <div style="width:{{ $pctP }}%; height:100%;
                          background:{{ $critico?'linear-gradient(90deg,#f43f5e,#e11d48)':'linear-gradient(90deg,#10b981,#059669)' }};
                          border-radius:99px;"></div>
            </div>
            <div style="display:flex; justify-content:space-between; margin-top:2px;">
              <span style="font-size:.65rem; color:var(--vl-text-muted);">Mín: {{ $prod->stock_minimo }}</span>
              <span style="font-size:.65rem; color:var(--vl-text-muted);">{{ round($pctP) }}%</span>
            </div>
          </div>
        @empty
          <div class="vl-table__empty">
            <i class="bi bi-box-seam"></i>
            <p>Sin productos en inventario.</p>
          </div>
        @endforelse
      </div>
    </div>
  </div>

  {{-- Timeline vencimientos --}}
  <div class="col-lg-5 vl-animate vl-animate--delay-1">
    <div class="vl-card h-100">
      <div class="vl-card__accent"></div>
      <div class="vl-card__header">
        <h5 class="vl-card__title">
          <i class="bi bi-calendar-x-fill text-warning"></i>
          Próximos Vencimientos
          <span class="vl-badge vl-badge--slate ms-2" style="font-size:.65rem;">Timeline</span>
        </h5>
        <button class="vl-btn vl-btn--ghost vl-btn--icon vl-collapse-btn"
                data-target="body-venc" title="Colapsar">
          <i class="bi bi-chevron-up"></i>
        </button>
      </div>
      <div class="vl-card__body vl-collapsible" id="body-venc">
        @forelse($vencimientoTimeline as $prod)
          @php
            $dias=\Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($prod->fecha_vencimiento));
            $urgente=$dias<=7;
            $pronto=$dias<=30;
            $colV=$urgente?'#f43f5e':($pronto?'#f59e0b':'#10b981');
          @endphp
          <div style="display:flex; align-items:center; gap:12px; padding:10px 0;
                      {{ !$loop->last?'border-bottom:1px solid var(--vl-border);':'' }}">
            <div style="width:36px; height:36px; border-radius:var(--vl-radius-sm);
                        background:{{ $colV }}20; display:flex; align-items:center;
                        justify-content:center; flex-shrink:0;">
              <i class="bi bi-calendar3" style="color:{{ $colV }}; font-size:.85rem;"></i>
            </div>
            <div style="flex:1; min-width:0;">
              <div style="font-size:.78rem; font-weight:600; color:var(--vl-text-main);
                          white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                {{ $prod->nombre }}
              </div>
              <div style="font-size:.68rem; color:var(--vl-text-muted);">
                Vence: {{ \Carbon\Carbon::parse($prod->fecha_vencimiento)->format('d/m/Y') }}
              </div>
            </div>
            <span class="vl-badge" style="background:{{ $colV }}20; color:{{ $colV }}; white-space:nowrap;">
              {{ $dias }}d
            </span>
          </div>
        @empty
          <div class="vl-table__empty">
            <i class="bi bi-calendar-check"></i>
            <p>Sin vencimientos próximos.</p>
          </div>
        @endforelse
      </div>
    </div>
  </div>

</div>

{{-- ══════════════════════════════════════════════════════
     SECCIÓN: RENDIMIENTO Y OPERADORES
     ══════════════════════════════════════════════════════ --}}
<div class="vl-animate" style="margin-bottom:16px;">
  <div style="display:flex; align-items:center; gap:10px;">
    <div style="width:4px; height:24px; background:linear-gradient(135deg,#7c3aed,#a855f7); border-radius:99px;"></div>
    <h3 style="font-size:.95rem; font-weight:800; color:var(--vl-text-main); margin:0;">
      <i class="bi bi-people-fill me-2" style="color:#7c3aed;"></i>
      Rendimiento del Personal
    </h3>
  </div>
</div>

<div class="row g-4 mb-4">

  {{-- Torta operadores --}}
  <div class="col-lg-5 vl-animate">
    <div class="vl-card h-100">
      <div class="vl-card__accent"></div>
      <div class="vl-card__header">
        <h5 class="vl-card__title">
          <i class="bi bi-person-badge-fill text-primary"></i>
          Entregas por Operador
          <span class="vl-badge vl-badge--slate ms-2" style="font-size:.65rem;">Torta</span>
        </h5>
        <button class="vl-btn vl-btn--ghost vl-btn--icon vl-collapse-btn"
                data-target="body-op" title="Colapsar">
          <i class="bi bi-chevron-up"></i>
        </button>
      </div>
      <div class="vl-card__body vl-collapsible" id="body-op"
           style="display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
        @php
          $opCols=['#3b82f6','#7c3aed','#10b981','#f59e0b','#f43f5e'];
          $opTotal=$porOperador->sum('total')?: 1;
          $startOp=0; $cxOp=80; $cyOp=80; $rOp=65; $rOpI=45;
        @endphp
        <svg viewBox="0 0 160 160" style="width:140px; flex-shrink:0;">
          @foreach($porOperador as $i=>$op)
            @php
              $pctOp=$op->total/$opTotal;
              $angOp=$pctOp*360;
              [$ox1,$oy1]=pxy($cxOp,$cyOp,$rOp,$startOp-90);
              [$ox2,$oy2]=pxy($cxOp,$cyOp,$rOp,$startOp+$angOp-90);
              [$oix1,$oiy1]=pxy($cxOp,$cyOp,$rOpI,$startOp-90);
              [$oix2,$oiy2]=pxy($cxOp,$cyOp,$rOpI,$startOp+$angOp-90);
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
          <text x="{{ $cxOp }}" y="{{ $cyOp-5 }}" text-anchor="middle"
                font-size="15" font-weight="800" fill="var(--vl-text-main)">
            {{ $porOperador->sum('total') }}
          </text>
          <text x="{{ $cxOp }}" y="{{ $cyOp+9 }}" text-anchor="middle"
                font-size="8" fill="var(--vl-text-muted)">TOTAL</text>
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

  {{-- Top 5 beneficiarios --}}
  <div class="col-lg-7 vl-animate vl-animate--delay-1">
    <div class="vl-card h-100">
      <div class="vl-card__accent"></div>
      <div class="vl-card__header">
        <h5 class="vl-card__title">
          <i class="bi bi-trophy-fill text-warning"></i>
          Top 5 — Más Raciones Recibidas
        </h5>
        <button class="vl-btn vl-btn--ghost vl-btn--icon vl-collapse-btn"
                data-target="body-top5" title="Colapsar">
          <i class="bi bi-chevron-up"></i>
        </button>
      </div>
      <div class="vl-card__body vl-collapsible" id="body-top5" style="padding:0;">
        @php $maxTop=$topBeneficiarios->max('total')?: 1; @endphp
        <table class="vl-table">
          <thead>
            <tr>
              <th>#</th>
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
                        {{ strtoupper($tb->beneficiario->apellido??'') }},
                        {{ $tb->beneficiario->nombre??'—' }}
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
                <td>
                  <span class="vl-badge vl-badge--violet">{{ $tb->total }}</span>
                </td>
                <td>
                  <div style="background:var(--vl-border); border-radius:99px; height:6px; overflow:hidden;">
                    <div style="width:{{ ($tb->total/$maxTop)*100 }}%; height:100%;
                                background:linear-gradient(90deg,#3b82f6,#7c3aed);
                                border-radius:99px;"></div>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5">
                  <div class="vl-table__empty">
                    <i class="bi bi-inbox"></i>
                    <p>Sin datos de entregas.</p>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- Gauge cobertura --}}
  <div class="col-lg-4 vl-animate">
    <div class="vl-card h-100">
      <div class="vl-card__accent"></div>
      <div class="vl-card__header">
        <h5 class="vl-card__title">
          <i class="bi bi-speedometer text-primary"></i>
          Cobertura del Programa
          <span class="vl-badge vl-badge--slate ms-2" style="font-size:.65rem;">Gauge</span>
        </h5>
        <button class="vl-btn vl-btn--ghost vl-btn--icon vl-collapse-btn"
                data-target="body-gauge" title="Colapsar">
          <i class="bi bi-chevron-up"></i>
        </button>
      </div>
      <div class="vl-card__body vl-collapsible" id="body-gauge" style="text-align:center;">
        @php
          $gaugeVal=min($coberturaActual,100);
          $gaugeAngle=$gaugeVal*1.8;
          $cxG=100; $cyG=100; $rG=80; $rGI=55;
          [$gx1,$gy1]=pxy($cxG,$cyG,$rG,180);
          [$gx2,$gy2]=pxy($cxG,$cyG,$rG,180+$gaugeAngle);
          [$gix1,$giy1]=pxy($cxG,$cyG,$rGI,180);
          [$gix2,$giy2]=pxy($cxG,$cyG,$rGI,180+$gaugeAngle);
          $lgG=$gaugeAngle>180?1:0;
          $gaugeCol=$gaugeVal>=75?'#10b981':($gaugeVal>=50?'#f59e0b':'#f43f5e');
        @endphp
        <svg viewBox="0 0 200 115" style="width:200px;">
          {{-- Fondo gris --}}
          @php
            [$bgx1,$bgy1]=pxy($cxG,$cyG,$rG,180);
            [$bgx2,$bgy2]=pxy($cxG,$cyG,$rG,360);
            [$bgix1,$bgiy1]=pxy($cxG,$cyG,$rGI,180);
            [$bgix2,$bgiy2]=pxy($cxG,$cyG,$rGI,360);
          @endphp
          <path d="M {{ $bgix1 }} {{ $bgy1 }} L {{ $bgx1 }} {{ $bgy1 }}
                   A {{ $rG }} {{ $rG }} 0 1 1 {{ $bgx2 }} {{ $bgy2 }}
                   L {{ $bgix2 }} {{ $bgiy2 }}
                   A {{ $rGI }} {{ $rGI }} 0 1 0 {{ $bgix1 }} {{ $bgy1 }} Z"
                fill="var(--vl-border)"/>
          {{-- Valor --}}
          @if($gaugeAngle>0)
            <path d="M {{ $gix1 }} {{ $giy1 }} L {{ $gx1 }} {{ $gy1 }}
                     A {{ $rG }} {{ $rG }} 0 {{ $lgG }} 1 {{ $gx2 }} {{ $gy2 }}
                     L {{ $gix2 }} {{ $giy2 }}
                     A {{ $rGI }} {{ $rGI }} 0 {{ $lgG }} 0 {{ $gix1 }} {{ $giy1 }} Z"
                  fill="{{ $gaugeCol }}"/>
          @endif
          <text x="{{ $cxG }}" y="{{ $cyG-8 }}" text-anchor="middle"
                font-size="20" font-weight="800" fill="{{ $gaugeCol }}">
            {{ $gaugeVal }}%
          </text>
          <text x="{{ $cxG }}" y="{{ $cyG+8 }}" text-anchor="middle"
                font-size="8" fill="var(--vl-text-muted)">COBERTURA</text>
          <text x="28" y="{{ $cyG+5 }}" font-size="8" fill="var(--vl-text-muted)">0%</text>
          <text x="{{ $cxG*2-28 }}" y="{{ $cyG+5 }}" font-size="8"
                fill="var(--vl-text-muted)" text-anchor="end">100%</text>
        </svg>
        <div style="margin-top:8px;">
          <div style="font-size:.72rem; color:var(--vl-text-muted);">
            {{ $esteMes }} entregas de {{ $totalActivos }} beneficiarios activos este mes
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Top productos --}}
  <div class="col-lg-8 vl-animate vl-animate--delay-1">
    <div class="vl-card h-100">
      <div class="vl-card__accent"></div>
      <div class="vl-card__header">
        <h5 class="vl-card__title">
          <i class="bi bi-box-seam-fill text-primary"></i>
          Top Productos — Más Distribuidos
        </h5>
        <button class="vl-btn vl-btn--ghost vl-btn--icon vl-collapse-btn"
                data-target="body-toprod" title="Colapsar">
          <i class="bi bi-chevron-up"></i>
        </button>
      </div>
      <div class="vl-card__body vl-collapsible" id="body-toprod">
        @php
          $maxProd=$topProductos->max('total')?: 1;
          $prodCols=['#3b82f6','#10b981','#f59e0b','#7c3aed','#f43f5e'];
        @endphp
        @forelse($topProductos as $i=>$tp)
          @php $pctP2=($tp->total/$maxProd)*100; $colP2=$prodCols[$i%count($prodCols)]; @endphp
          <div style="margin-bottom:16px;">
            <div style="display:flex; justify-content:space-between;
                        align-items:center; margin-bottom:5px;">
              <div style="display:flex; align-items:center; gap:8px;">
                <div style="width:28px; height:28px; border-radius:6px;
                            background:{{ $colP2 }}20; display:flex; align-items:center;
                            justify-content:center;">
                  <i class="bi bi-box-seam" style="color:{{ $colP2 }}; font-size:.75rem;"></i>
                </div>
                <div>
                  <div style="font-size:.78rem; font-weight:600; color:var(--vl-text-main);">
                    {{ $tp->producto->nombre??'Sin nombre' }}
                  </div>
                  @if($tp->producto?->marca)
                    <div style="font-size:.65rem; color:var(--vl-text-muted);">{{ $tp->producto->marca }}</div>
                  @endif
                </div>
              </div>
              <span style="font-size:.82rem; font-weight:800; color:{{ $colP2 }};">
                {{ $tp->total }}
              </span>
            </div>
            <div style="background:var(--vl-border); border-radius:99px; height:8px; overflow:hidden;">
              <div style="width:{{ $pctP2 }}%; height:100%; background:{{ $colP2 }};
                          border-radius:99px; opacity:.85;"></div>
            </div>
          </div>
        @empty
          <div class="vl-table__empty">
            <i class="bi bi-box-seam"></i>
            <p>Sin datos de productos distribuidos.</p>
          </div>
        @endforelse
      </div>
    </div>
  </div>

</div>

@endsection

@push('scripts')
<script>
// ── Cards colapsables ──
document.querySelectorAll('.vl-collapse-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    const targetId = btn.dataset.target;
    const body     = document.getElementById(targetId);
    if (!body) return;

    const isCollapsed = body.style.display === 'none';
    body.style.display = isCollapsed ? '' : 'none';

    const icon = btn.querySelector('i');
    if (icon) {
      icon.className = isCollapsed ? 'bi bi-chevron-up' : 'bi bi-chevron-down';
    }
  });
});
</script>
@endpush