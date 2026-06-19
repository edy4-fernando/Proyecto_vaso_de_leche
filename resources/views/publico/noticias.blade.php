@extends('layouts.web')

@section('title', 'Noticias — Vaso de Leche Cusco')

@section('content')

{{-- ── Hero ── --}}
<div style="background:rgba(0,0,0,.25); border-bottom:1px solid rgba(255,255,255,.06);
            padding:60px 0 40px;">
  <div class="container">
    <div style="max-width:680px;">
      <span class="vl-badge vl-badge--green mb-3" style="font-size:.75rem;">
        <i class="bi bi-newspaper me-1"></i>
        Noticias y Anuncios
      </span>
      <h1 style="font-size:2rem; font-weight:800; color:#fff;
                 line-height:1.2; letter-spacing:-.5px; margin:12px 0;">
        Últimas Noticias del Programa
      </h1>
      <p style="color:rgba(255,255,255,.55); font-size:.95rem; line-height:1.7; margin:0;">
        Entérate de los últimos avisos, cronogramas de distribución
        y novedades del Programa Vaso de Leche de la Municipalidad Provincial del Cusco.
      </p>
    </div>
  </div>
</div>

<div class="container" style="padding:48px 0 60px;">

  {{-- ── Aviso destacado ── --}}
  <div style="background:rgba(59,130,246,.10); border:1px solid rgba(59,130,246,.25);
              border-left:4px solid #3b82f6; border-radius:14px;
              padding:20px 24px; margin-bottom:40px;
              display:flex; align-items:flex-start; gap:16px;">
    <i class="bi bi-megaphone-fill"
       style="color:#3b82f6; font-size:1.4rem; flex-shrink:0; margin-top:2px;"></i>
    <div>
      <div style="font-size:.88rem; font-weight:800; color:#fff; margin-bottom:4px;">
        📢 Aviso Importante — Distribución Diaria
      </div>
      <div style="font-size:.8rem; color:rgba(255,255,255,.6); line-height:1.6;">
        La distribución de raciones se realiza <strong style="color:#fff;">de lunes a viernes</strong>
        en los comités de cada sector. Presentá tu DNI para registrar tu asistencia.
        Cualquier cambio en el cronograma será comunicado con anticipación.
      </div>
    </div>
  </div>

  <div class="row g-4">

    {{-- Columna principal --}}
    <div class="col-lg-8">

      @php
        $noticias = [
          [
            'fecha'    => now()->subDays(2)->format('d/m/Y'),
            'categoria'=> 'Distribución',
            'color'    => '#3b82f6',
            'icon'     => 'bi-cup-hot-fill',
            'titulo'   => 'Inicio del cronograma de distribución ' . now()->isoFormat('MMMM YYYY'),
            'resumen'  => 'El programa inicia el nuevo cronograma mensual de distribución de raciones para todos los beneficiarios registrados en el padrón oficial. Se atenderá según el orden de sectores establecido.',
            'detalle'  => 'Los beneficiarios deben acercarse a su comité correspondiente portando su DNI. La distribución se realiza de lunes a viernes de 7:00 am a 12:00 pm.',
          ],
          [
            'fecha'    => now()->subDays(7)->format('d/m/Y'),
            'categoria'=> 'Registro',
            'color'    => '#10b981',
            'icon'     => 'bi-person-plus-fill',
            'titulo'   => 'Apertura de inscripciones para nuevos beneficiarios',
            'resumen'  => 'La Gerencia de Desarrollo Social anuncia la apertura del período de inscripción para nuevos beneficiarios del Programa Vaso de Leche correspondiente al presente año fiscal.',
            'detalle'  => 'Los interesados deben acercarse a la oficina de Desarrollo Social de la Municipalidad Provincial del Cusco con su documentación completa.',
          ],
          [
            'fecha'    => now()->subDays(14)->format('d/m/Y'),
            'categoria'=> 'Inventario',
            'color'    => '#f59e0b',
            'icon'     => 'bi-box-seam-fill',
            'titulo'   => 'Reabastecimiento de productos en almacén municipal',
            'resumen'  => 'El almacén municipal ha recibido una nueva remesa de productos alimenticios para garantizar la continuidad del programa durante los próximos meses.',
            'detalle'  => 'Se han recibido productos lácteos, cereales y suplementos vitamínicos que cumplen con los estándares de calidad establecidos por el MINSA.',
          ],
          [
            'fecha'    => now()->subDays(21)->format('d/m/Y'),
            'categoria'=> 'Capacitación',
            'color'    => '#7c3aed',
            'icon'     => 'bi-mortarboard-fill',
            'titulo'   => 'Capacitación a coordinadores de comités sectoriales',
            'resumen'  => 'Se realizó una jornada de capacitación dirigida a los coordinadores de los comités sectoriales del Programa Vaso de Leche sobre el uso del nuevo sistema digital de registro.',
            'detalle'  => 'La capacitación incluyó el manejo del sistema de registro digital, procedimientos de control de asistencia y normativa vigente del programa.',
          ],
          [
            'fecha'    => now()->subDays(30)->format('d/m/Y'),
            'categoria'=> 'Normativa',
            'color'    => '#f43f5e',
            'icon'     => 'bi-file-earmark-text-fill',
            'titulo'   => 'Actualización del padrón de beneficiarios ' . now()->year,
            'resumen'  => 'La Municipalidad Provincial del Cusco informa que se ha completado la actualización del padrón de beneficiarios para el año en curso, depurando registros duplicados e incorporando nuevos beneficiarios.',
            'detalle'  => 'El padrón actualizado contempla beneficiarios de primera y segunda prioridad según lo establece la Ley N° 27470.',
          ],
        ];
      @endphp

      @foreach($noticias as $noticia)
        <div style="background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.08);
                    border-radius:16px; padding:24px; margin-bottom:20px;
                    transition:border-color .2s ease;"
             onmouseover="this.style.borderColor='rgba(255,255,255,.15)'"
             onmouseout="this.style.borderColor='rgba(255,255,255,.08)'">

          <div style="display:flex; align-items:center; gap:10px; margin-bottom:14px;
                      flex-wrap:wrap;">
            <span style="background:{{ $noticia['color'] }}20; color:{{ $noticia['color'] }};
                         border:1px solid {{ $noticia['color'] }}30; border-radius:99px;
                         font-size:.68rem; font-weight:700; padding:3px 10px;
                         display:flex; align-items:center; gap:5px;">
              <i class="bi {{ $noticia['icon'] }}"></i>
              {{ $noticia['categoria'] }}
            </span>
            <span style="font-size:.72rem; color:rgba(255,255,255,.3);">
              <i class="bi bi-calendar3 me-1"></i>
              {{ $noticia['fecha'] }}
            </span>
          </div>

          <h3 style="font-size:1rem; font-weight:800; color:#fff;
                     margin-bottom:10px; line-height:1.3;">
            {{ $noticia['titulo'] }}
          </h3>

          <p style="font-size:.82rem; color:rgba(255,255,255,.55);
                    line-height:1.7; margin-bottom:12px;">
            {{ $noticia['resumen'] }}
          </p>

          <div style="background:rgba(0,0,0,.2); border-radius:10px; padding:14px;
                      border-left:3px solid {{ $noticia['color'] }};">
            <p style="font-size:.78rem; color:rgba(255,255,255,.45);
                      line-height:1.6; margin:0;">
              <i class="bi bi-info-circle me-1" style="color:{{ $noticia['color'] }};"></i>
              {{ $noticia['detalle'] }}
            </p>
          </div>

        </div>
      @endforeach

    </div>

    {{-- Columna lateral --}}
    <div class="col-lg-4">

      {{-- Estadísticas rápidas --}}
      <div style="background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.08);
                  border-radius:16px; padding:20px; margin-bottom:20px;">
        <h5 style="font-size:.88rem; font-weight:800; color:#fff; margin-bottom:16px;">
          <i class="bi bi-bar-chart-fill me-2" style="color:#3b82f6;"></i>
          Programa en Números
        </h5>
        @php
          $statsNews = [
            [\App\Models\Beneficiario::where('estado',true)->count(),
             'Beneficiarios activos', '#3b82f6'],
            [\App\Models\Entrega::whereMonth('fecha_entrega',now()->month)->count(),
             'Raciones este mes', '#10b981'],
            [\App\Models\Entrega::whereDate('fecha_entrega',today())->count(),
             'Raciones hoy', '#f59e0b'],
            [\App\Models\Beneficiario::count(),
             'Total en padrón', '#7c3aed'],
          ];
        @endphp
        @foreach($statsNews as [$val, $label, $color])
          <div style="display:flex; align-items:center; justify-content:space-between;
                      padding:10px 0;
                      {{ !$loop->last ? 'border-bottom:1px solid rgba(255,255,255,.06);' : '' }}">
            <span style="font-size:.78rem; color:rgba(255,255,255,.5);">{{ $label }}</span>
            <span style="font-size:1rem; font-weight:800; color:{{ $color }};">{{ $val }}</span>
          </div>
        @endforeach
      </div>

      {{-- Cronograma semanal --}}
      <div style="background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.08);
                  border-radius:16px; padding:20px; margin-bottom:20px;">
        <h5 style="font-size:.88rem; font-weight:800; color:#fff; margin-bottom:16px;">
          <i class="bi bi-calendar-week-fill me-2" style="color:#10b981;"></i>
          Cronograma Semanal
        </h5>
        @php
          $dias = [
            ['Lunes',     '7:00 - 12:00', true],
            ['Martes',    '7:00 - 12:00', true],
            ['Miércoles', '7:00 - 12:00', true],
            ['Jueves',    '7:00 - 12:00', true],
            ['Viernes',   '7:00 - 12:00', true],
            ['Sábado',    'No hay atención', false],
            ['Domingo',   'No hay atención', false],
          ];
          $hoyDia = now()->dayOfWeek; // 0=Dom, 1=Lun...
          $diaMap = [1=>'Lunes',2=>'Martes',3=>'Miércoles',4=>'Jueves',5=>'Viernes',6=>'Sábado',0=>'Domingo'];
          $hoyNombre = $diaMap[$hoyDia] ?? '';
        @endphp
        @foreach($dias as [$dia, $horario, $activo])
          <div style="display:flex; align-items:center; justify-content:space-between;
                      padding:8px 10px; border-radius:8px; margin-bottom:4px;
                      background:{{ $dia===$hoyNombre ? 'rgba(16,185,129,.12)' : 'transparent' }};
                      border:{{ $dia===$hoyNombre ? '1px solid rgba(16,185,129,.2)' : '1px solid transparent' }};">
            <span style="font-size:.78rem; font-weight:{{ $dia===$hoyNombre ? '700' : '400' }};
                         color:{{ $activo ? '#fff' : 'rgba(255,255,255,.25)' }};">
              {{ $dia }}
              @if($dia === $hoyNombre)
                <span style="font-size:.65rem; color:#10b981; margin-left:4px;">← Hoy</span>
              @endif
            </span>
            <span style="font-size:.72rem;
                         color:{{ $activo ? 'rgba(255,255,255,.5)' : 'rgba(255,255,255,.2)' }};">
              {{ $horario }}
            </span>
          </div>
        @endforeach
      </div>

      {{-- Links útiles --}}
      <div style="background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.08);
                  border-radius:16px; padding:20px;">
        <h5 style="font-size:.88rem; font-weight:800; color:#fff; margin-bottom:16px;">
          <i class="bi bi-link-45deg me-2" style="color:#7c3aed;"></i>
          Links Útiles
        </h5>
        @foreach([
          ['Registrar asistencia',    route('asistencia.index'),   'bi-person-check-fill', '#10b981'],
          ['Servicios nutricionales', route('web.servicios'),      'bi-droplet-half',      '#3b82f6'],
          ['Contactar al programa',   route('web.contacto'),       'bi-envelope-fill',     '#7c3aed'],
        ] as [$label, $url, $icon, $color])
          <a href="{{ $url }}"
             style="display:flex; align-items:center; gap:10px; padding:10px;
                    border-radius:8px; text-decoration:none;
                    color:rgba(255,255,255,.6); font-size:.8rem;
                    transition:all .2s; margin-bottom:4px;"
             onmouseover="this.style.background='rgba(255,255,255,.07)'; this.style.color='#fff';"
             onmouseout="this.style.background='transparent'; this.style.color='rgba(255,255,255,.6)';">
            <i class="bi {{ $icon }}" style="color:{{ $color }}; width:16px;"></i>
            {{ $label }}
            <i class="bi bi-chevron-right ms-auto" style="font-size:.65rem; opacity:.4;"></i>
          </a>
        @endforeach
      </div>

    </div>

  </div>

</div>

@endsection