@extends('layouts.web')

@section('title', 'Asistencia Confirmada — Vaso de Leche Cusco')

@section('content')

{{-- ── Hero confirmación ── --}}
<div style="background:rgba(0,0,0,.25); border-bottom:1px solid rgba(255,255,255,.06);
            padding:48px 0 32px;">
  <div class="container">
    <div style="display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
      <div style="width:56px; height:56px; border-radius:50%;
                  background:rgba(16,185,129,.2); border:2px solid rgba(16,185,129,.4);
                  display:flex; align-items:center; justify-content:center; flex-shrink:0;">
        <i class="bi bi-check-lg" style="color:#10b981; font-size:1.5rem;"></i>
      </div>
      <div>
        <div style="font-size:.75rem; color:rgba(255,255,255,.4);
                    text-transform:uppercase; letter-spacing:1px; margin-bottom:4px;">
          Asistencia Confirmada
        </div>
        <h1 style="font-size:1.6rem; font-weight:800; color:#fff;
                   margin:0; letter-spacing:-.3px; line-height:1.2;">
          ¡Bienvenido/a,
          <span style="color:#10b981;">
            {{ $beneficiario->nombre }} {{ $beneficiario->apellido }}
          </span>!
        </h1>
      </div>
    </div>
  </div>
</div>

<div class="container" style="padding:40px 0 60px;">

  <div class="row g-4">

    {{-- ── Columna izquierda — Confirmación ── --}}
    <div class="col-lg-5">

      {{-- Card principal --}}
      <div style="background:rgba(16,185,129,.08); border:1px solid rgba(16,185,129,.2);
                  border-radius:20px; padding:28px; margin-bottom:20px;">

        {{-- Info del beneficiario --}}
        <div style="display:flex; align-items:center; gap:14px; margin-bottom:24px;
                    padding-bottom:20px; border-bottom:1px solid rgba(255,255,255,.08);">
          <div style="width:52px; height:52px; border-radius:50%; flex-shrink:0;
                      background:linear-gradient(135deg,#10b981,#059669);
                      display:flex; align-items:center; justify-content:center;
                      font-size:1.1rem; font-weight:800; color:#fff;">
            {{ strtoupper(substr($beneficiario->nombre, 0, 1)) }}
          </div>
          <div>
            <div style="font-size:.95rem; font-weight:800; color:#fff;">
              {{ strtoupper($beneficiario->apellido) }}, {{ $beneficiario->nombre }}
            </div>
            <div style="font-size:.75rem; color:rgba(255,255,255,.45); margin-top:2px;">
              <i class="bi bi-card-text me-1"></i>
              DNI: {{ $beneficiario->dni }}
            </div>
            <span style="font-size:.68rem; background:rgba(16,185,129,.2);
                         color:#34d399; padding:2px 8px; border-radius:99px;
                         display:inline-block; margin-top:4px;">
              {{ ucfirst($beneficiario->tipo_beneficiario ?? 'Beneficiario') }}
            </span>
          </div>
        </div>

        {{-- Ración confirmada --}}
        <div style="text-align:center; padding:20px;
                    background:rgba(0,0,0,.2); border-radius:14px; margin-bottom:20px;">
          <div style="font-size:.68rem; color:rgba(255,255,255,.4);
                      text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">
            Ración registrada hoy
          </div>
          <div style="font-size:3rem; font-weight:900; color:#10b981;
                      line-height:1; margin-bottom:4px;">
            ✓
          </div>
          <div style="font-size:.85rem; color:rgba(255,255,255,.6);">
            {{ now()->format('d/m/Y') }} —
            {{ now()->format('H:i') }} hrs
          </div>
        </div>

        {{-- Total raciones --}}
        <div style="display:flex; align-items:center; justify-content:space-between;
                    padding:14px 16px; background:rgba(255,255,255,.05);
                    border-radius:12px; margin-bottom:20px;">
          <div>
            <div style="font-size:.68rem; color:rgba(255,255,255,.4);
                        text-transform:uppercase; letter-spacing:.5px;">
              Total raciones recibidas
            </div>
            <div style="font-size:1.8rem; font-weight:900; color:#f472b6; line-height:1.1;">
              {{ $totalRaciones }}
            </div>
          </div>
          <i class="bi bi-journal-check"
             style="font-size:2rem; color:rgba(244,114,182,.3);"></i>
        </div>

        {{-- Sector --}}
        @if($beneficiario->sector_o_comite)
          <div style="display:flex; align-items:center; gap:8px; padding:10px 14px;
                      background:rgba(255,255,255,.05); border-radius:10px;
                      margin-bottom:20px;">
            <i class="bi bi-geo-alt-fill" style="color:#f59e0b; flex-shrink:0;"></i>
            <div>
              <div style="font-size:.65rem; color:rgba(255,255,255,.35);
                          text-transform:uppercase; letter-spacing:.5px;">
                Sector / Comité
              </div>
              <div style="font-size:.8rem; color:#fff; font-weight:600;">
                {{ $beneficiario->sector_o_comite }}
              </div>
            </div>
          </div>
        @endif

        {{-- Auto-redirect --}}
        <div style="text-align:center; margin-bottom:16px;">
          <div style="font-size:.72rem; color:rgba(255,255,255,.3); margin-bottom:8px;">
            <i class="bi bi-clock me-1"></i>
            Redirigiendo automáticamente en
            <span id="countdown" style="color:#10b981; font-weight:700;">15</span>s
          </div>
          <div style="background:rgba(255,255,255,.1); border-radius:99px;
                      height:3px; overflow:hidden;">
            <div id="progressBar"
                 style="height:100%; width:100%; background:#10b981;
                        border-radius:99px; transition:width 1s linear;"></div>
          </div>
        </div>

        {{-- Botón salir --}}
        <a href="{{ route('asistencia.index') }}"
           style="display:flex; align-items:center; justify-content:center; gap:8px;
                  padding:13px; border-radius:12px; text-decoration:none;
                  background:rgba(244,63,94,.15); border:1px solid rgba(244,63,94,.25);
                  color:#fca5a5; font-size:.85rem; font-weight:700;
                  transition:all .2s;"
           onmouseover="this.style.background='rgba(244,63,94,.25)'"
           onmouseout="this.style.background='rgba(244,63,94,.15)'">
          <i class="bi bi-box-arrow-left"></i>
          Finalizar y Salir
        </a>

      </div>

    </div>

    {{-- ── Columna derecha — Info ── --}}
    <div class="col-lg-7">

      {{-- Misión del programa --}}
      <div style="background:rgba(59,130,246,.08); border:1px solid rgba(59,130,246,.15);
                  border-radius:16px; padding:22px; margin-bottom:16px;">
        <h5 style="font-size:.9rem; font-weight:800; color:#fff; margin-bottom:10px;">
          <i class="bi bi-journal-bookmark-fill me-2" style="color:#3b82f6;"></i>
          Nuestra Misión
        </h5>
        <p style="font-size:.82rem; color:rgba(255,255,255,.55); line-height:1.7; margin:0;">
          El Programa del Vaso de Leche (PVL) de la Municipalidad Provincial del Cusco
          busca mejorar el estado nutricional de los sectores con mayor vulnerabilidad
          económica, proveyendo un complemento alimentario diario de alta calidad
          láctea y cereales nativos andinos.
        </p>
      </div>

      <div class="row g-3 mb-4">

        {{-- Noticias --}}
        <div class="col-md-6">
          <div style="background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.08);
                      border-left:3px solid #f472b6; border-radius:14px; padding:18px;
                      height:100%;">
            <h6 style="font-size:.82rem; font-weight:800; color:#fff; margin-bottom:8px;">
              <i class="bi bi-building-fill-add me-2" style="color:#f472b6;"></i>
              Obras en Marcha
            </h6>
            <p style="font-size:.75rem; color:rgba(255,255,255,.45);
                      line-height:1.6; margin:0;">
              Se completó la refacción de 12 nuevos centros de distribución comunal
              equipados con sistemas de refrigeración para garantizar la calidad
              de los productos.
            </p>
          </div>
        </div>

        {{-- Salud --}}
        <div class="col-md-6">
          <div style="background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.08);
                      border-left:3px solid #fbbf24; border-radius:14px; padding:18px;
                      height:100%;">
            <h6 style="font-size:.82rem; font-weight:800; color:#fff; margin-bottom:8px;">
              <i class="bi bi-heart-pulse-fill me-2" style="color:#fbbf24;"></i>
              Campaña de Salud
            </h6>
            <p style="font-size:.75rem; color:rgba(255,255,255,.45);
                      line-height:1.6; margin:0;">
              Control gratuito de descarte de anemia infantil y monitoreo de peso
              este fin de semana en la Plaza de Armas. Sin costo, sin cita previa.
            </p>
          </div>
        </div>

      </div>

      {{-- Estadísticas del programa --}}
      <div style="background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.08);
                  border-radius:16px; padding:20px; margin-bottom:16px;">
        <h6 style="font-size:.82rem; font-weight:800; color:#fff; margin-bottom:16px;">
          <i class="bi bi-bar-chart-fill me-2" style="color:#7c3aed;"></i>
          Programa en Números
        </h6>
        <div class="row g-3">
          @php
            $statsB = [
              [\App\Models\Beneficiario::where('estado',true)->count(),
               'Beneficiarios activos', '#3b82f6'],
              [\App\Models\Entrega::whereDate('fecha_entrega',today())->count(),
               'Raciones hoy', '#10b981'],
              [\App\Models\Entrega::whereMonth('fecha_entrega',now()->month)->count(),
               'Este mes', '#f59e0b'],
              [\App\Models\Producto::where('stock_actual','>',0)->count(),
               'Productos disponibles', '#7c3aed'],
            ];
          @endphp
          @foreach($statsB as [$val, $label, $color])
            <div class="col-6 col-md-3">
              <div style="text-align:center; padding:12px 8px;
                          background:rgba(0,0,0,.15); border-radius:10px;">
                <div style="font-size:1.4rem; font-weight:900; color:{{ $color }};
                            line-height:1;">
                  {{ $val }}
                </div>
                <div style="font-size:.62rem; color:rgba(255,255,255,.35);
                            text-transform:uppercase; letter-spacing:.4px; margin-top:3px;">
                  {{ $label }}
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      {{-- Contacto --}}
      <div style="background:rgba(0,0,0,.2); border:1px solid rgba(255,255,255,.06);
                  border-radius:14px; padding:16px 20px;
                  display:flex; align-items:center; justify-content:space-between;
                  flex-wrap:wrap; gap:12px;">
        <div>
          <h6 style="font-size:.82rem; font-weight:800; color:#06b6d4; margin:0 0 3px;">
            <i class="bi bi-telephone-inbound-fill me-2"></i>
            ¿Dudas o reclamos?
          </h6>
          <p style="font-size:.72rem; color:rgba(255,255,255,.4); margin:0;">
            Comunícate con la Gerencia de Desarrollo Social
          </p>
        </div>
        <div style="font-size:1.1rem; font-weight:900; color:#fbbf24;
                    font-family:monospace; letter-spacing:1px;">
          ALÓ MUNI: #9933
        </div>
      </div>

      {{-- Links útiles --}}
      <div style="display:flex; gap:10px; margin-top:16px; flex-wrap:wrap;">
        <a href="{{ route('web.servicios') }}"
           style="flex:1; display:flex; align-items:center; justify-content:center;
                  gap:6px; padding:10px; border-radius:10px;
                  border:1px solid rgba(255,255,255,.1); text-decoration:none;
                  color:rgba(255,255,255,.5); font-size:.75rem; font-weight:600;
                  transition:all .2s;"
           onmouseover="this.style.background='rgba(255,255,255,.07)';
                        this.style.color='#fff'"
           onmouseout="this.style.background='transparent';
                       this.style.color='rgba(255,255,255,.5)'">
          <i class="bi bi-droplet-half" style="color:#3b82f6;"></i>
          Servicios
        </a>
        <a href="{{ route('web.noticias') }}"
           style="flex:1; display:flex; align-items:center; justify-content:center;
                  gap:6px; padding:10px; border-radius:10px;
                  border:1px solid rgba(255,255,255,.1); text-decoration:none;
                  color:rgba(255,255,255,.5); font-size:.75rem; font-weight:600;
                  transition:all .2s;"
           onmouseover="this.style.background='rgba(255,255,255,.07)';
                        this.style.color='#fff'"
           onmouseout="this.style.background='transparent';
                       this.style.color='rgba(255,255,255,.5)'">
          <i class="bi bi-newspaper" style="color:#10b981;"></i>
          Noticias
        </a>
        <a href="{{ route('web.contacto') }}"
           style="flex:1; display:flex; align-items:center; justify-content:center;
                  gap:6px; padding:10px; border-radius:10px;
                  border:1px solid rgba(255,255,255,.1); text-decoration:none;
                  color:rgba(255,255,255,.5); font-size:.75rem; font-weight:600;
                  transition:all .2s;"
           onmouseover="this.style.background='rgba(255,255,255,.07)';
                        this.style.color='#fff'"
           onmouseout="this.style.background='transparent';
                       this.style.color='rgba(255,255,255,.5)'">
          <i class="bi bi-envelope-fill" style="color:#7c3aed;"></i>
          Contacto
        </a>
      </div>

    </div>

  </div>

</div>

@endsection

@push('scripts')
<script>
// ── Auto-redirect con countdown ──
let segundos = 15;
const countdown   = document.getElementById('countdown');
const progressBar = document.getElementById('progressBar');

const timer = setInterval(() => {
  segundos--;
  if (countdown) countdown.textContent = segundos;
  if (progressBar) {
    progressBar.style.width = ((segundos / 15) * 100) + '%';
  }
  if (segundos <= 0) {
    clearInterval(timer);
    window.location.href = '{{ route("asistencia.index") }}';
  }
}, 1000);
</script>
@endpush