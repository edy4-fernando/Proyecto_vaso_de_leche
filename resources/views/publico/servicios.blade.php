@extends('layouts.web')

@section('title', 'Servicios Nutricionales — Vaso de Leche Cusco')

@section('content')

{{-- ── Hero ── --}}
<div style="background:rgba(0,0,0,.25); border-bottom:1px solid rgba(255,255,255,.06);
            padding:60px 0 40px;">
  <div class="container">
    <div style="max-width:680px;">
      <span class="vl-badge vl-badge--blue mb-3" style="font-size:.75rem;">
        <i class="bi bi-droplet-half me-1"></i>
        Programa Vaso de Leche — Cusco
      </span>
      <h1 style="font-size:2rem; font-weight:800; color:#fff;
                 line-height:1.2; letter-spacing:-.5px; margin:12px 0;">
        Servicios Nutricionales
      </h1>
      <p style="color:rgba(255,255,255,.55); font-size:.95rem; line-height:1.7; margin:0;">
        Conocé todos los servicios que brinda el programa de apoyo alimentario
        de la Municipalidad Provincial del Cusco a la población más vulnerable.
      </p>
    </div>
  </div>
</div>

<div class="container" style="padding:48px 0 60px;">

  {{-- ── Qué es el programa ── --}}
  <div class="row align-items-center g-5 mb-5">
    <div class="col-lg-6">
      <h2 style="font-size:1.5rem; font-weight:800; color:#fff;
                 margin-bottom:16px; letter-spacing:-.3px;">
        ¿Qué es el Programa Vaso de Leche?
      </h2>
      <p style="color:rgba(255,255,255,.6); font-size:.9rem; line-height:1.8; margin-bottom:16px;">
        El Programa del Vaso de Leche (PVL) es un programa social del Estado Peruano
        creado por la Ley N° 24059, regulado por la Ley N° 27470 y sus modificaciones.
        Tiene como objetivo mejorar el nivel nutricional de la población más vulnerable
        del país.
      </p>
      <p style="color:rgba(255,255,255,.6); font-size:.9rem; line-height:1.8; margin-bottom:24px;">
        En la Municipalidad Provincial del Cusco, este programa es administrado por
        la Gerencia de Desarrollo Social, brindando raciones diarias a beneficiarios
        clasificados según los criterios establecidos por ley.
      </p>
      <div style="display:flex; gap:12px; flex-wrap:wrap;">
        <a href="{{ route('asistencia.index') }}"
           class="btn-portal-primary"
           style="width:auto; padding:10px 20px;">
          <i class="bi bi-person-check-fill me-2"></i>
          Registrar mi asistencia
        </a>
        <a href="{{ route('web.contacto') }}"
           style="display:inline-flex; align-items:center; gap:6px;
                  padding:10px 20px; border:1px solid rgba(255,255,255,.2);
                  border-radius:10px; color:rgba(255,255,255,.7);
                  text-decoration:none; font-size:.88rem; font-weight:600;
                  transition:all .2s;">
          <i class="bi bi-envelope-fill"></i>
          Contactarnos
        </a>
      </div>
    </div>
    <div class="col-lg-6">
      <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
        @php
          $stats = [
            ['bi-people-fill',    '#3b82f6', \App\Models\Beneficiario::where('estado',true)->count(), 'Beneficiarios activos'],
            ['bi-journal-check',  '#10b981', \App\Models\Entrega::count(),                            'Total raciones entregadas'],
            ['bi-calendar-month', '#f59e0b', \App\Models\Entrega::whereMonth('fecha_entrega',now()->month)->count(), 'Raciones este mes'],
            ['bi-box-seam',       '#7c3aed', \App\Models\Producto::where('stock_actual','>',0)->count(), 'Productos disponibles'],
          ];
        @endphp
        @foreach($stats as [$icon, $color, $valor, $label])
          <div style="background:rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.08);
                      border-radius:16px; padding:20px; text-align:center;">
            <i class="bi {{ $icon }}"
               style="font-size:1.5rem; color:{{ $color }}; display:block; margin-bottom:10px;"></i>
            <div style="font-size:1.6rem; font-weight:800; color:#fff; line-height:1;">
              {{ $valor }}
            </div>
            <div style="font-size:.7rem; color:rgba(255,255,255,.4);
                        text-transform:uppercase; letter-spacing:.5px; margin-top:4px;">
              {{ $label }}
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>

  {{-- ── Población beneficiaria ── --}}
  <div style="margin-bottom:48px;">
    <h2 style="font-size:1.3rem; font-weight:800; color:#fff;
               margin-bottom:6px; letter-spacing:-.3px;">
      ¿Quiénes pueden acceder al programa?
    </h2>
    <p style="color:rgba(255,255,255,.45); font-size:.82rem; margin-bottom:24px;">
      Según la Ley N° 27470 y sus modificaciones vigentes.
    </p>
    <div class="row g-4">

      {{-- Primera prioridad --}}
      <div class="col-md-6">
        <div style="background:rgba(59,130,246,.08); border:1px solid rgba(59,130,246,.2);
                    border-radius:16px; padding:24px; height:100%;">
          <div style="display:flex; align-items:center; gap:10px; margin-bottom:16px;">
            <div style="width:36px; height:36px; border-radius:10px;
                        background:rgba(59,130,246,.2); display:flex; align-items:center;
                        justify-content:center; flex-shrink:0;">
              <i class="bi bi-1-circle-fill" style="color:#3b82f6; font-size:1.1rem;"></i>
            </div>
            <div>
              <div style="font-size:.88rem; font-weight:800; color:#fff;">Primera Prioridad</div>
              <div style="font-size:.7rem; color:rgba(255,255,255,.4);">Atención obligatoria</div>
            </div>
          </div>
          @foreach([
            ['bi-emoji-smile-fill', '#3b82f6', 'Niños de 0 a 6 años', 'Población infantil en etapa de crecimiento'],
            ['bi-heart-fill',       '#3b82f6', 'Madres gestantes',     'Durante todo el período de embarazo'],
            ['bi-droplet-fill',     '#3b82f6', 'Madres en lactancia',  'Período puerperal post-parto'],
          ] as [$icon, $color, $titulo, $desc])
            <div style="display:flex; gap:12px; padding:10px 0;
                        {{ !$loop->last ? 'border-bottom:1px solid rgba(255,255,255,.06);' : '' }}">
              <i class="bi {{ $icon }}" style="color:{{ $color }}; font-size:.9rem; margin-top:2px; flex-shrink:0;"></i>
              <div>
                <div style="font-size:.82rem; font-weight:700; color:#fff;">{{ $titulo }}</div>
                <div style="font-size:.72rem; color:rgba(255,255,255,.4);">{{ $desc }}</div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      {{-- Segunda prioridad --}}
      <div class="col-md-6">
        <div style="background:rgba(16,185,129,.06); border:1px solid rgba(16,185,129,.15);
                    border-radius:16px; padding:24px; height:100%;">
          <div style="display:flex; align-items:center; gap:10px; margin-bottom:16px;">
            <div style="width:36px; height:36px; border-radius:10px;
                        background:rgba(16,185,129,.15); display:flex; align-items:center;
                        justify-content:center; flex-shrink:0;">
              <i class="bi bi-2-circle-fill" style="color:#10b981; font-size:1.1rem;"></i>
            </div>
            <div>
              <div style="font-size:.88rem; font-weight:800; color:#fff;">Segunda Prioridad</div>
              <div style="font-size:.7rem; color:rgba(255,255,255,.4);">Según disponibilidad presupuestal</div>
            </div>
          </div>
          @foreach([
            ['bi-balloon-fill',    '#10b981', 'Niños de 7 a 13 años',    'Etapa escolar primaria'],
            ['bi-person-fill',     '#10b981', 'Adultos mayores',          'Personas de la tercera edad'],
            ['bi-heart-pulse-fill','#10b981', 'Personas con discapacidad','Con carnet CONADIS vigente'],
            ['bi-bandaid-fill',    '#10b981', 'Personas con TBC',         'En tratamiento activo contra tuberculosis'],
          ] as [$icon, $color, $titulo, $desc])
            <div style="display:flex; gap:12px; padding:10px 0;
                        {{ !$loop->last ? 'border-bottom:1px solid rgba(255,255,255,.06);' : '' }}">
              <i class="bi {{ $icon }}" style="color:{{ $color }}; font-size:.9rem; margin-top:2px; flex-shrink:0;"></i>
              <div>
                <div style="font-size:.82rem; font-weight:700; color:#fff;">{{ $titulo }}</div>
                <div style="font-size:.72rem; color:rgba(255,255,255,.4);">{{ $desc }}</div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

    </div>
  </div>

  {{-- ── Proceso de inscripción ── --}}
  <div style="margin-bottom:48px;">
    <h2 style="font-size:1.3rem; font-weight:800; color:#fff;
               margin-bottom:6px; letter-spacing:-.3px;">
      ¿Cómo inscribirse?
    </h2>
    <p style="color:rgba(255,255,255,.45); font-size:.82rem; margin-bottom:24px;">
      Sigue estos pasos para registrarte como beneficiario del programa.
    </p>
    <div class="row g-3">
      @foreach([
        ['1', 'bi-geo-alt-fill',       '#3b82f6', 'Acercate al comité de tu sector',
         'Identifica el comité Vaso de Leche más cercano a tu domicilio en el distrito de Cusco.'],
        ['2', 'bi-card-text',          '#7c3aed', 'Presenta tu documentación',
         'DNI del beneficiario, DNI del apoderado (si aplica), y documentos que acrediten tu condición (carnet CONADIS, constancia médica, etc.).'],
        ['3', 'bi-person-check-fill',  '#10b981', 'Evaluación y registro',
         'El personal autorizado evaluará tu caso y, si cumples los requisitos, te registrará en el padrón oficial del sistema.'],
        ['4', 'bi-cup-hot-fill',       '#f59e0b', 'Recepción de raciones',
         'Una vez registrado, podrás acercarte diariamente a recoger tu ración presentando tu DNI en el puesto de asistencia.'],
      ] as [$num, $icon, $color, $titulo, $desc])
        <div class="col-md-6 col-lg-3">
          <div style="background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.08);
                      border-radius:16px; padding:20px; height:100%; position:relative;">
            <div style="position:absolute; top:16px; right:16px; font-size:2rem;
                        font-weight:900; color:rgba(255,255,255,.05); line-height:1;">
              {{ $num }}
            </div>
            <div style="width:40px; height:40px; border-radius:10px;
                        background:{{ $color }}20; display:flex; align-items:center;
                        justify-content:center; margin-bottom:12px;">
              <i class="bi {{ $icon }}" style="color:{{ $color }}; font-size:1rem;"></i>
            </div>
            <div style="font-size:.85rem; font-weight:700; color:#fff; margin-bottom:6px;">
              {{ $titulo }}
            </div>
            <div style="font-size:.75rem; color:rgba(255,255,255,.45); line-height:1.6;">
              {{ $desc }}
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  {{-- ── Productos que se distribuyen ── --}}
  <div>
    <h2 style="font-size:1.3rem; font-weight:800; color:#fff;
               margin-bottom:6px; letter-spacing:-.3px;">
      Productos que distribuimos
    </h2>
    <p style="color:rgba(255,255,255,.45); font-size:.82rem; margin-bottom:24px;">
      Alimentos de alto valor nutricional aprobados por el programa.
    </p>
    <div class="row g-3">
      @php
        $productos = \App\Models\Producto::where('stock_actual', '>', 0)
          ->orderBy('nombre')->get();
      @endphp
      @forelse($productos as $prod)
        <div class="col-6 col-md-4 col-lg-3">
          <div style="background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.08);
                      border-radius:14px; padding:16px; text-align:center;">
            <i class="bi bi-box-seam-fill"
               style="font-size:1.5rem; color:#f59e0b; display:block; margin-bottom:10px;"></i>
            <div style="font-size:.82rem; font-weight:700; color:#fff;">
              {{ $prod->nombre }}
            </div>
            @if($prod->marca)
              <div style="font-size:.7rem; color:rgba(255,255,255,.4);">{{ $prod->marca }}</div>
            @endif
            <div style="margin-top:8px;">
              <span style="font-size:.68rem; background:rgba(16,185,129,.15);
                           color:#34d399; padding:2px 8px; border-radius:99px;">
                Disponible
              </span>
            </div>
          </div>
        </div>
      @empty
        <div class="col-12">
          <div style="background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.08);
                      border-radius:14px; padding:32px; text-align:center;
                      color:rgba(255,255,255,.3);">
            <i class="bi bi-box-seam" style="font-size:2rem; display:block; margin-bottom:8px;"></i>
            <p style="margin:0; font-size:.85rem;">
              Información de productos no disponible en este momento.
            </p>
          </div>
        </div>
      @endforelse
    </div>
  </div>

</div>

@endsection