@extends(str_contains(url()->previous(), '/dashboard') ? 'layouts.dashboard' : 'layouts.admin')
@section('title', 'Mi Cuenta — Vaso de Leche')
@section('breadcrumb', 'Mi Cuenta')
@php $activeModule = 'cuenta'; @endphp

@section('content')

{{-- ── Encabezado ── --}}
<div class="vl-section-header">
  <div>
    <h2 class="vl-section-title">
      <i class="bi bi-person-circle text-primary"></i>
      Mi Cuenta
    </h2>
    <p class="vl-section-sub">
      Ajustes personales y de seguridad de la cuenta
    </p>
  </div>
</div>

<div class="row g-4">

  {{-- ══════════════════════════════════════
       COLUMNA IZQUIERDA — Perfil + Contraseña
       ══════════════════════════════════════ --}}
  <div class="col-lg-7 mx-auto">

    {{-- Info del usuario --}}
    <div class="vl-card mb-4 vl-animate">
      <div class="vl-card__accent"></div>
      <div class="vl-card__header">
        <h5 class="vl-card__title">
          <i class="bi bi-person-badge-fill text-primary"></i>
          Información Personal
        </h5>
      </div>
      <div class="vl-card__body">

        {{-- Avatar --}}
        <div style="display:flex; align-items:center; gap:16px; margin-bottom:20px;
                    padding:16px; background:var(--vl-bg-body); border-radius:var(--vl-radius);">
          @include('admin._components.avatar', [
            'name' => auth()->user()->name,
            'size' => 'xl',
            'class' => '',
          ])
          <div>
            <div style="font-size:1rem; font-weight:800; color:var(--vl-text-main);">
              {{ auth()->user()->name }}
            </div>
            <div style="font-size:.75rem; color:var(--vl-text-muted); margin:3px 0;">
              {{ auth()->user()->email }}
            </div>
            <span class="vl-badge vl-badge--{{ auth()->user()->rol === 'maestro' ? 'violet' : 'blue' }} mt-1">
              <i class="bi bi-{{ auth()->user()->rol === 'maestro' ? 'shield-fill' : 'person-fill' }}"></i>
              {{ ucfirst(auth()->user()->rol) }}
            </span>
          </div>
        </div>

        {{-- Datos --}}
        @php
          $datos = [
            ['DNI',            auth()->user()->dni ?? '—',       'bi-card-text',          '#3b82f6'],
            ['Teléfono',       auth()->user()->telefono ?? '—',  'bi-telephone-fill',     '#10b981'],
            ['Último ingreso', auth()->user()->ultimo_ingreso
              ? \Carbon\Carbon::parse(auth()->user()->ultimo_ingreso)->format('d/m/Y H:i')
              : 'Primera sesión',                                'bi-clock-fill',         '#f59e0b'],
            ['Miembro desde',  \Carbon\Carbon::parse(auth()->user()->created_at)->format('d/m/Y'),
                                                                 'bi-calendar-check-fill','#7c3aed'],
          ];
        @endphp

        @foreach($datos as [$label, $valor, $icon, $color])
          <div style="display:flex; align-items:center; gap:10px; padding:10px 0;
                      {{ !$loop->last ? 'border-bottom:1px solid var(--vl-border);' : '' }}">
            <i class="bi {{ $icon }}"
               style="color:{{ $color }}; font-size:.85rem; width:18px; flex-shrink:0;"></i>
            <div>
              <div style="font-size:.65rem; color:var(--vl-text-muted);
                          text-transform:uppercase; letter-spacing:.5px;">
                {{ $label }}
              </div>
              <div style="font-size:.82rem; color:var(--vl-text-main); font-weight:500;">
                {{ $valor }}
              </div>
            </div>
          </div>
        @endforeach

      </div>
    </div>
  {{-- Cambiar correo electrónico --}}
    <div class="vl-card mb-4 vl-animate vl-animate--delay-1" id="cambiar-email">
      <div class="vl-card__accent"></div>
      <div class="vl-card__header">
        <h5 class="vl-card__title">
          <i class="bi bi-envelope-fill text-primary"></i>
          Cambiar Correo Electrónico
        </h5>
      </div>
      <div class="vl-card__body">

        <div class="vl-alert vl-alert--info mb-4">
          <i class="bi bi-info-circle-fill"></i>
          <div style="font-size:.8rem;">
            Por seguridad, confirmá tu contraseña actual para actualizar el correo.
          </div>
        </div>

        <form action="{{ route('perfil.actualizar-email') }}" method="POST">
          @csrf

          <div class="vl-form-group">
            <label class="vl-label">
              Nuevo correo electrónico <span class="req">*</span>
            </label>
            <div class="vl-input-icon-wrap">
              <i class="bi bi-envelope-fill"></i>
              <input type="email" name="email_nuevo"
                     class="vl-input" value="{{ auth()->user()->email }}"
                     placeholder="correo@ejemplo.com" required>
            </div>
          </div>

          <div class="vl-form-group" style="margin-bottom:0;">
            <label class="vl-label">
              Contraseña actual <span class="req">*</span>
            </label>
            <div class="vl-input-icon-wrap">
              <i class="bi bi-lock-fill"></i>
              <input type="password" name="password_actual"
                     class="vl-input" placeholder="••••••••" required>
            </div>
          </div>

          <div class="vl-divider"></div>

          <button type="submit" class="vl-btn vl-btn--primary">
            <i class="bi bi-check-lg"></i>
            Actualizar correo
          </button>
        </form>

      </div>
    </div>
    {{-- Cambiar contraseña --}}
    <div class="vl-card vl-animate vl-animate--delay-1" id="cambiar-password">
      <div class="vl-card__accent"></div>
      <div class="vl-card__header">
        <h5 class="vl-card__title">
          <i class="bi bi-key-fill text-primary"></i>
          Cambiar Contraseña
        </h5>
      </div>
      <div class="vl-card__body">

        <div class="vl-alert vl-alert--info mb-4">
          <i class="bi bi-info-circle-fill"></i>
          <div style="font-size:.8rem;">
            Mínimo <strong>8 caracteres</strong> con letras,
            números y símbolos para mayor seguridad.
          </div>
        </div>

        <form action="{{ route('perfil.cambiar-password') }}" method="POST">
          @csrf

          <div class="vl-form-group">
            <label class="vl-label">
              Contraseña actual <span class="req">*</span>
            </label>
            <div class="vl-input-icon-wrap">
              <i class="bi bi-lock-fill"></i>
              <input type="password" name="password_actual"
                     class="vl-input" placeholder="••••••••" required>
            </div>
          </div>

          <div class="vl-form-group">
            <label class="vl-label">
              Nueva contraseña <span class="req">*</span>
            </label>
            <div class="vl-input-icon-wrap">
              <i class="bi bi-lock-open-fill"></i>
              <input type="password" name="password_nuevo"
                     id="pwdNuevo" class="vl-input"
                     placeholder="Mínimo 8 caracteres"
                     minlength="8" required>
            </div>
            <div style="margin-top:6px;">
              <div style="background:var(--vl-border); border-radius:99px;
                          height:4px; overflow:hidden;">
                <div id="pwdBar"
                     style="height:100%; width:0%; border-radius:99px;
                            transition:all .3s; background:#f43f5e;"></div>
              </div>
              <span id="pwdTxt"
                    style="font-size:.68rem; color:var(--vl-text-muted);
                           margin-top:3px; display:block;">
                Ingresá una contraseña
              </span>
            </div>
          </div>

          <div class="vl-form-group" style="margin-bottom:0;">
            <label class="vl-label">
              Confirmar contraseña <span class="req">*</span>
            </label>
            <div class="vl-input-icon-wrap">
              <i class="bi bi-shield-lock-fill"></i>
              <input type="password" name="password_nuevo_confirmation"
                     id="pwdConfirm" class="vl-input"
                     placeholder="Repetir contraseña" required>
            </div>
            <span id="pwdMatch"
                  style="font-size:.68rem; display:none; margin-top:3px;"></span>
          </div>

          <div class="vl-divider"></div>

          <button type="submit" class="vl-btn vl-btn--primary">
            <i class="bi bi-check-lg"></i>
            Actualizar contraseña
          </button>
        </form>

      </div>
    </div>

  </div>


</div>

@endsection

@push('scripts')
<script>



// ── Fortaleza de contraseña ──
document.getElementById('pwdNuevo')?.addEventListener('input', function() {
  const bar = document.getElementById('pwdBar');
  const txt = document.getElementById('pwdTxt');
  if (!bar || !txt) return;
  let score = 0;
  if (this.value.length >= 8)          score++;
  if (/[A-Z]/.test(this.value))        score++;
  if (/[0-9]/.test(this.value))        score++;
  if (/[^A-Za-z0-9]/.test(this.value)) score++;
  const c = [
    {w:'0%',   c:'#f43f5e', t:'Ingresá una contraseña'},
    {w:'25%',  c:'#f43f5e', t:'Muy débil'},
    {w:'50%',  c:'#f59e0b', t:'Débil'},
    {w:'75%',  c:'#3b82f6', t:'Buena'},
    {w:'100%', c:'#10b981', t:'Muy fuerte ✓'},
  ];
  bar.style.width      = c[score].w;
  bar.style.background = c[score].c;
  txt.textContent      = c[score].t;
  txt.style.color      = c[score].c;
  txt.style.display    = 'block';
});

// ── Coincidencia de contraseñas ──
document.getElementById('pwdConfirm')?.addEventListener('input', function() {
  const nuevo = document.getElementById('pwdNuevo')?.value;
  const txt   = document.getElementById('pwdMatch');
  if (!txt) return;
  txt.style.display = 'block';
  if (this.value === nuevo) {
    txt.textContent = '✓ Las contraseñas coinciden';
    txt.style.color = '#10b981';
  } else {
    txt.textContent = '✗ No coinciden';
    txt.style.color = '#f43f5e';
  }
});
</script>
@endpush