{{-- ============================================================
     web/bienvenida.blade.php
     Pantalla de confirmación post-marcado de asistencia.
     Ruta: GET /asistencia/bienvenida/{id}
     Variables: $beneficiario
     Layout: layouts/web.blade.php
     ============================================================ --}}

@extends('layouts.web')

@section('title', 'Bienvenido — ' . $beneficiario->nombre . ' ' . $beneficiario->apellido)

{{-- Sin footer en esta vista --}}
@section('footer', '')

@section('content')

  <div class="container main-wrapper-narrow">
    <div class="row g-4">

      {{-- ── COLUMNA IZQUIERDA: Confirmación ── --}}
      <div class="col-lg-5">
        <div class="card-bienvenida p-4 text-center h-100
                    d-flex flex-column justify-content-between">

          <div>
            {{-- Ícono de éxito --}}
            <div class="text-success mb-2" style="font-size: 3.5rem;">
              <i class="bi bi-check-circle-fill"></i>
            </div>

            <p class="text-uppercase fw-bold mb-1"
               style="font-size: .75rem; letter-spacing: .08em;
                      color: rgba(255,255,255,.45);">
              ¡Asistencia Confirmada!
            </p>

            <h2 class="fw-bold px-2 my-3"
                style="color: #fff; letter-spacing: -.025em; font-size: 1.5rem;">
              ¡Hola, bienvenido(a)! <br>
              <span style="color: var(--wp-sky);">
                {{ $beneficiario->nombre }}
                {{ $beneficiario->apellido }}
              </span>
            </h2>

            <p style="color: rgba(255,255,255,.55); font-size: .85rem; padding: 0 1rem;">
              Tu ración diaria correspondiente al Programa del Vaso de Leche
              ha sido registrada con éxito en el sistema municipal.
            </p>

            {{-- Pills de info --}}
            <div class="d-flex gap-2 justify-content-center flex-wrap mt-3">
              <span style="
                background: rgba(255,255,255,.08);
                border: 1px solid rgba(255,255,255,.12);
                border-radius: 99px; padding: 5px 14px;
                font-size: .72rem; color: rgba(255,255,255,.6);
              ">
                <i class="bi bi-calendar3 me-1"></i>
                {{ \Carbon\Carbon::now()->format('d/m/Y') }}
              </span>
              <span style="
                background: rgba(255,255,255,.08);
                border: 1px solid rgba(255,255,255,.12);
                border-radius: 99px; padding: 5px 14px;
                font-size: .72rem; color: rgba(255,255,255,.6);
              ">
                <i class="bi bi-clock-fill me-1"></i>
                {{ \Carbon\Carbon::now()->format('H:i') }}
              </span>
            </div>

          </div>

          {{-- Barra de redirección --}}
          <div style="margin-top: 28px;">
            <div style="
              background: rgba(255,255,255,.08);
              border-radius: 99px; height: 4px;
              overflow: hidden; margin-bottom: 10px;
            ">
              <div style="
                height: 100%;
                background: linear-gradient(90deg, #10b981, #059669);
                border-radius: 99px;
                animation: redirectFill 6s linear forwards;
              "></div>
            </div>
            <p style="font-size: .72rem; color: rgba(255,255,255,.3); margin: 0;">
              <i class="bi bi-arrow-repeat me-1"></i>
              Volviendo al inicio en 6 segundos…
            </p>
            <a href="{{ route('asistencia.index') }}"
               style="
                 display: inline-block; margin-top: 8px;
                 font-size: .72rem; color: rgba(255,255,255,.35);
                 text-decoration: none;
               ">
              <i class="bi bi-skip-forward-fill me-1"></i>
              Ir al inicio ahora
            </a>
          </div>

        </div>
      </div>

      {{-- ── COLUMNA DERECHA: Datos del beneficiario ── --}}
      <div class="col-lg-7">

        {{-- Card datos personales --}}
        <div class="card-bienvenida p-4 mb-4">
          <h5 style="color: #fff; font-weight: 700; margin-bottom: 16px; font-size: .95rem;">
            <i class="bi bi-person-lines-fill me-2 text-info"></i>
            Datos del Beneficiario
          </h5>

          @php
            $datos = [
              ['DNI',              $beneficiario->dni,                          'bi-card-text'],
              ['Tipo',             ucfirst($beneficiario->tipo_beneficiario ?? '—'), 'bi-person-fill'],
              ['Sector / Comité',  $beneficiario->sector_o_comite ?? '—',       'bi-building'],
              ['Dirección',        $beneficiario->direccion ?? '—',              'bi-geo-alt-fill'],
              ['Teléfono',         $beneficiario->telefono ?? '—',               'bi-telephone-fill'],
            ];
          @endphp

          @foreach($datos as [$label, $valor, $icon])
            <div style="
              display: flex; align-items: flex-start; gap: 12px;
              padding: 9px 0;
              {{ !$loop->last ? 'border-bottom: 1px solid rgba(255,255,255,.07);' : '' }}
            ">
              <i class="bi {{ $icon }}"
                 style="color: var(--wp-sky); font-size: .85rem;
                        margin-top: 2px; flex-shrink: 0;"></i>
              <div>
                <div style="font-size: .65rem; color: rgba(255,255,255,.35);
                            text-transform: uppercase; letter-spacing: .5px;">
                  {{ $label }}
                </div>
                <div style="font-size: .82rem; color: rgba(255,255,255,.8);
                            font-weight: 500; margin-top: 1px;">
                  {{ $valor }}
                </div>
              </div>
            </div>
          @endforeach

          {{-- Apoderado --}}
          @if($beneficiario->nombre_apoderado)
            <div style="
              margin-top: 12px;
              background: rgba(59,130,246,.08);
              border: 1px solid rgba(59,130,246,.15);
              border-radius: 10px; padding: 10px 14px;
              font-size: .78rem; color: rgba(147,197,253,.8);
            ">
              <i class="bi bi-person-fill me-1"></i>
              Apoderado: <strong>{{ $beneficiario->nombre_apoderado }}</strong>
              @if($beneficiario->dni_apoderado)
                — DNI: {{ $beneficiario->dni_apoderado }}
              @endif
            </div>
          @endif

          {{-- Observaciones médicas --}}
          @if($beneficiario->observaciones_medicas)
            <div style="
              margin-top: 10px;
              background: rgba(245,158,11,.08);
              border: 1px solid rgba(245,158,11,.15);
              border-radius: 10px; padding: 10px 14px;
              font-size: .78rem; color: rgba(251,191,36,.8);
            ">
              <i class="bi bi-exclamation-triangle-fill me-1"></i>
              {{ $beneficiario->observaciones_medicas }}
            </div>
          @endif

        </div>

        {{-- Mensaje institucional --}}
        <div style="
          background: rgba(16,185,129,.08);
          border: 1px solid rgba(16,185,129,.15);
          border-radius: 14px; padding: 18px 20px;
        ">
          <div style="display: flex; align-items: center; gap: 12px;">
            <i class="bi bi-cup-hot-fill"
               style="font-size: 1.6rem; color: #34d399; flex-shrink: 0;"></i>
            <div>
              <div style="color: #6ee7b7; font-weight: 700;
                          font-size: .85rem; margin-bottom: 3px;">
                Ración del día registrada correctamente
              </div>
              <div style="color: rgba(110,231,183,.6); font-size: .75rem; line-height: 1.5;">
                Gracias por participar en el Programa Vaso de Leche
                de la Municipalidad Provincial del Cusco.
                Tu bienestar es nuestra prioridad.
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>
  </div>

@endsection

@push('scripts')
<script>
  // Auto-redirigir después de 6 segundos
  setTimeout(() => {
    window.location.href = '{{ route('asistencia.index') }}';
  }, 6000);
</script>
<style>
  @keyframes redirectFill {
    from { width: 0%; }
    to   { width: 100%; }
  }
</style>
@endpush