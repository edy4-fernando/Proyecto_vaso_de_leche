@extends(request()->routeIs('admin.*') || request()->is('admin*') ? 'layouts.admin' : 'layouts.dashboard')
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
      Ajustes personales, contraseña y apariencia del sistema
    </p>
  </div>
</div>

<div class="row g-4">

  {{-- ══════════════════════════════════════
       COLUMNA IZQUIERDA — Perfil + Contraseña
       ══════════════════════════════════════ --}}
  <div class="col-lg-5">

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

  {{-- ══════════════════════════════════════
       COLUMNA DERECHA — Temas
       ══════════════════════════════════════ --}}
  <div class="col-lg-7">

    <div class="vl-card vl-animate">
      <div class="vl-card__accent"></div>
      <div class="vl-card__header">
        <h5 class="vl-card__title">
          <i class="bi bi-palette-fill text-primary"></i>
          Tema del Dashboard
        </h5>
        <span class="vl-badge vl-badge--blue" id="temaActualBadge">
          Cargando…
        </span>
      </div>
      <div class="vl-card__body">

        <p class="vl-label mb-3">Temas predefinidos</p>
        <div class="row g-3 mb-4">

          @php
            $temas = [
              ['oceano',    '🌊 Océano',    '#0f172a', '#3b82f6', '#6366f1', 'Azul — Índigo'],
              ['atardecer', '🔥 Atardecer', '#1a0a0a', '#ef4444', '#f97316', 'Rojo — Naranja'],
              ['bosque',    '🌿 Bosque',    '#0a1a14', '#10b981', '#0d9488', 'Verde — Teal'],
              ['cosmico',   '🌌 Cósmico',   '#0e0f1a', '#6366f1', '#a855f7', 'Índigo — Violeta'],
              ['artico',    '❄️ Ártico',    '#0a1520', '#06b6d4', '#38bdf8', 'Cian — Celeste'],
              ['fusion',    '🌺 Fusión',    '#180a1a', '#ec4899', '#a855f7', 'Rosa — Violeta'],
            ];
          @endphp

          @foreach($temas as [$key, $nombre, $bg, $accent, $accent2, $desc])
            <div class="col-6 col-md-4">
              <div class="theme-preview"
                   data-preview-theme="{{ $key }}"
                   data-set-theme="{{ $key }}"
                   data-name="{{ $nombre }}">
                <div class="theme-preview__sidebar"
                     style="background:{{ $bg }}; position:relative; overflow:hidden;">
                  <div style="position:absolute; top:0; right:0; width:4px; height:100%;
                              background:linear-gradient(180deg,{{ $accent }},{{ $accent2 }});"></div>
                  <div class="theme-preview__bar active" style="background:{{ $accent }};"></div>
                  <div class="theme-preview__bar md"     style="background:{{ $accent }}; opacity:.6;"></div>
                  <div class="theme-preview__bar sm"     style="background:{{ $accent }}; opacity:.35;"></div>
                </div>
                <div class="theme-preview__content">
                  <div class="theme-preview__dot" style="background:{{ $accent }};"></div>
                  <div class="theme-preview__dot" style="background:{{ $accent2 }};"></div>
                  <div class="theme-preview__line"></div>
                </div>
              </div>
              <div style="text-align:center; margin-top:8px;">
                <div style="font-size:.78rem; font-weight:700; color:var(--vl-text-main);">
                  {{ $nombre }}
                </div>
                <div style="font-size:.65rem; color:var(--vl-text-muted);">{{ $desc }}</div>
              </div>
            </div>
          @endforeach

        </div>

        <div class="vl-divider"></div>
        <p class="vl-label mb-3 mt-3">
          <i class="bi bi-sliders me-1"></i>
          Personalizar colores
        </p>

        <div class="row g-3 mb-3">
          @foreach([
            ['pickDbBg',      'Fondo del sidebar',    '#0f172a'],
            ['pickDbAccent',  'Acento principal',     '#3b82f6'],
            ['pickDbAccent2', 'Acento secundario',    '#6366f1'],
          ] as [$id, $label, $default])
            <div class="col-md-4">
              <label class="vl-label">{{ $label }}</label>
              <div style="display:flex; align-items:center; gap:10px;">
                <input type="color" id="{{ $id }}" value="{{ $default }}"
                       style="width:44px; height:36px;
                              border:1px solid var(--vl-border);
                              border-radius:var(--vl-radius-sm);
                              cursor:pointer; padding:2px;">
                <span id="{{ $id }}Val"
                      style="font-size:.75rem; font-family:var(--vl-font-mono);
                             color:var(--vl-text-muted);">
                  {{ $default }}
                </span>
              </div>
            </div>
          @endforeach
        </div>

        <div style="padding:14px; background:var(--vl-bg-body);
                    border-radius:var(--vl-radius); margin-bottom:16px;">
          <div style="font-size:.72rem; color:var(--vl-text-muted); margin-bottom:8px;">
            Preview del gradiente
          </div>
          <div id="livePreviewBar"
               style="height:10px; border-radius:99px;
                      background:linear-gradient(90deg,#3b82f6,#6366f1);
                      transition:background .3s ease;">
          </div>
        </div>

        <div style="display:flex; gap:8px; flex-wrap:wrap;">
          <button type="button" class="vl-btn vl-btn--primary"
                  id="btnApplyCustom">
            <i class="bi bi-check-lg"></i>
            Aplicar personalizado
          </button>
          <button type="button" class="vl-btn vl-btn--outline"
                  onclick="vlDbSetTheme('oceano')">
            <i class="bi bi-arrow-counterclockwise"></i>
            Restablecer
          </button>
        </div>

      </div>
    </div>

  </div>

</div>

@endsection

@push('scripts')
<script>
// ── Pickers ──
['pickDbBg','pickDbAccent','pickDbAccent2'].forEach(id => {
  const el  = document.getElementById(id);
  const val = document.getElementById(id + 'Val');
  if (!el) return;
  el.addEventListener('input', () => {
    if (val) val.textContent = el.value;
    const a  = document.getElementById('pickDbAccent')?.value  || '#3b82f6';
    const a2 = document.getElementById('pickDbAccent2')?.value || '#6366f1';
    const bar = document.getElementById('livePreviewBar');
    if (bar) bar.style.background = `linear-gradient(90deg,${a},${a2})`;
  });
});

// ── Cargar valores guardados ──
document.addEventListener('DOMContentLoaded', () => {
  const saved   = JSON.parse(localStorage.getItem('vl_db_custom') || 'null');
  const current = localStorage.getItem('vl_db_theme') || 'oceano';
  const badge   = document.getElementById('temaActualBadge');

  if (saved) {
    [['pickDbBg','bg'],['pickDbAccent','accent'],['pickDbAccent2','accent2']].forEach(([id,key]) => {
      const el  = document.getElementById(id);
      const val = document.getElementById(id+'Val');
      if (el && saved[key]) {
        el.value = saved[key];
        if (val) val.textContent = saved[key];
      }
    });
  }

  document.querySelectorAll('[data-preview-theme]').forEach(card => {
    const isActive = card.dataset.previewTheme === current;
    card.classList.toggle('active', isActive);
    if (isActive && badge) badge.textContent = card.dataset.name || current;
  });

  if (current === 'custom' && badge) badge.textContent = '🎨 Personalizado';
});

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