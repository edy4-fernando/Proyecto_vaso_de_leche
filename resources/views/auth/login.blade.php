<!DOCTYPE html>
<html lang="es" class="">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar Sesión — Vaso de Leche Cusco</title>

  <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">

  <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

  <style>
    /* ── Fondo animado ── */
    .vl-login-wrap {
      position: relative;
      overflow: hidden;
    }

    /* Partículas decorativas */
    .vl-login-wrap::before {
      content: '';
      position: absolute;
      width: 600px; height: 600px;
      border-radius: 50%;
      background: radial-gradient(circle,
        rgba(37,99,235,.15) 0%, transparent 70%);
      top: -200px; right: -150px;
      animation: floatOrb 8s ease-in-out infinite alternate;
      pointer-events: none;
    }

    .vl-login-wrap::after {
      content: '';
      position: absolute;
      width: 400px; height: 400px;
      border-radius: 50%;
      background: radial-gradient(circle,
        rgba(124,58,237,.10) 0%, transparent 70%);
      bottom: -100px; left: -100px;
      animation: floatOrb 10s ease-in-out infinite alternate-reverse;
      pointer-events: none;
    }

    @keyframes floatOrb {
      from { transform: scale(1) translate(0,0); }
      to   { transform: scale(1.15) translate(30px,-20px); }
    }

    /* ── Grid de fondo ── */
    .vl-login-bg-grid {
      position: absolute;
      inset: 0;
      background-image:
        linear-gradient(rgba(255,255,255,.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.03) 1px, transparent 1px);
      background-size: 40px 40px;
      pointer-events: none;
    }

    /* ── Card mejorada ── */
    .vl-login-card {
      position: relative;
      z-index: 1;
    }

    /* ── Separador ── */
    .vl-login-sep {
      display: flex;
      align-items: center;
      gap: 12px;
      margin: 20px 0;
    }

    .vl-login-sep::before,
    .vl-login-sep::after {
      content: '';
      flex: 1;
      height: 1px;
      background: rgba(255,255,255,.08);
    }

    .vl-login-sep span {
      font-size: .65rem;
      color: rgba(255,255,255,.25);
      text-transform: uppercase;
      letter-spacing: .5px;
      white-space: nowrap;
    }

    /* ── Indicador de Caps Lock ── */
    .vl-caps-warning {
      display: none;
      font-size: .7rem;
      color: #f59e0b;
      margin-top: 4px;
      align-items: center;
      gap: 4px;
    }

    .vl-caps-warning.show { display: flex; }

    /* ── Checkbox recordar ── */
    .vl-login-check {
      display: flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
    }

    .vl-login-check input[type="checkbox"] {
      width: 16px; height: 16px;
      accent-color: #3b82f6;
      cursor: pointer;
    }

    .vl-login-check span {
      font-size: .75rem;
      color: rgba(255,255,255,.45);
    }

    /* ── Info del sistema (esquina) ── */
    .vl-login-sys-info {
      position: fixed;
      bottom: 16px;
      right: 16px;
      font-size: .65rem;
      color: rgba(255,255,255,.15);
      text-align: right;
      z-index: 0;
    }

    /* ── Botón de submit con loading ── */
    .vl-login-btn {
      position: relative;
      overflow: hidden;
    }

    .vl-login-btn::after {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(90deg,
        transparent 0%, rgba(255,255,255,.1) 50%, transparent 100%);
      transform: translateX(-100%);
      transition: transform .6s ease;
    }

    .vl-login-btn:hover::after {
      transform: translateX(100%);
    }

    /* ── Animación de entrada ── */
    @keyframes slideUp {
      from { opacity:0; transform:translateY(24px); }
      to   { opacity:1; transform:translateY(0); }
    }

    .vl-login-card { animation: slideUp .4s ease both; }

    /* ── Input focus mejorado ── */
    .vl-login-card .vl-input:focus {
      border-color: rgba(59,130,246,.7);
      box-shadow: 0 0 0 3px rgba(59,130,246,.12),
                  0 0 0 1px rgba(59,130,246,.3);
    }

    /* ── Error mejorado ── */
    .vl-login-error {
      background: rgba(244,63,94,.10);
      border: 1px solid rgba(244,63,94,.25);
      border-left: 3px solid #f43f5e;
      border-radius: var(--vl-radius-sm);
      padding: 10px 14px;
      margin-bottom: 18px;
      font-size: .78rem;
      color: #fca5a5;
      display: flex;
      align-items: flex-start;
      gap: 8px;
      animation: slideUp .3s ease both;
    }
  </style>
</head>
<body>

{{-- Grid de fondo --}}
<div class="vl-login-bg-grid"></div>

<div class="vl-login-wrap">

  <div class="vl-login-card">

    {{-- ── Logo ── --}}
    <div class="vl-login-logo">
      <div class="vl-login-logo__icon"
           style="box-shadow: 0 0 0 8px rgba(37,99,235,.1), 0 8px 32px rgba(37,99,235,.35);">
        <i class="bi bi-droplet-half"></i>
      </div>
      <h1 class="vl-login-logo__title">Vaso de Leche Cusco</h1>
      <p class="vl-login-logo__sub">Sistema de Gestión Municipal</p>
    </div>

    {{-- ── Error de credenciales ── --}}
    @if($errors->any())
      <div class="vl-login-error">
        <i class="bi bi-exclamation-circle-fill"
           style="color:#f43f5e; flex-shrink:0; margin-top:1px;"></i>
        <div>
          <strong style="display:block; margin-bottom:2px;">
            Credenciales incorrectas
          </strong>
          {{ $errors->first() }}
        </div>
      </div>
    @endif

    {{-- ── Mensaje de logout ── --}}
    @if(session('info'))
      <div style="background:rgba(59,130,246,.10); border:1px solid rgba(59,130,246,.2);
                  border-left:3px solid #3b82f6; border-radius:var(--vl-radius-sm);
                  padding:10px 14px; margin-bottom:18px; font-size:.78rem;
                  color:#93c5fd; display:flex; align-items:center; gap:8px;">
        <i class="bi bi-info-circle-fill"></i>
        {{ session('info') }}
      </div>
    @endif

    {{-- ── Formulario ── --}}
    <form action="{{ route('login.post') }}" method="POST"
          autocomplete="off" id="loginForm">
      @csrf

      {{-- Email --}}
      <div class="vl-form-group">
        <label class="vl-label" for="email">
          Correo electrónico institucional
        </label>
        <div class="vl-input-icon-wrap">
          <i class="bi bi-envelope-fill" style="color:#93c5fd;"></i>
          <input type="email"
          
                 id="email"
                 name="email"
                 class="vl-input"
                 placeholder="usuario@municipalidad.gob.pe"
                 value="{{ old('email') }}"
                 autocomplete="username"
                 required
                 autofocus>
        </div>
      </div>

      {{-- Contraseña --}}
      <div class="vl-form-group" style="margin-bottom:12px;">
        <label class="vl-label" for="password">
          Contraseña
        </label>
        <div style="position:relative;">
          <i class="bi bi-lock-fill"
            style="position:absolute; left:11px; top:50%;
                    transform:translateY(-50%);
                    color:#93c5fd; font-size:.85rem;  
                    pointer-events:none; z-index:2;"></i>
          <input type="password"
                id="password"
                name="password"
                class="vl-input"
                placeholder="••••••••"
                autocomplete="current-password"
                style="padding-left:34px; padding-right:40px;"
                required>
          <button type="button"
                  onclick="togglePassword()"
                  style="position:absolute; right:8px; top:50%;
                        transform:translateY(-50%);
                        background:none; border:none;
                        ccolor:rgba(255,255,255,.8);
                        padding:4px 6px; font-size:.95rem; z-index:2;
                        line-height:1;"
                  title="Mostrar/ocultar contraseña">
            <i class="bi bi-eye-fill" id="iconTogglePwd"></i>
          </button>
        </div>
        {{-- Aviso Caps Lock --}}
        <div class="vl-caps-warning" id="capsWarning">
          <i class="bi bi-exclamation-triangle-fill"></i>
          Mayúsculas activadas
        </div>
      </div>
      {{-- Recordar sesión --}}
      <div style="display:flex; align-items:center;
                  justify-content:space-between; margin-bottom:24px;">
        <label class="vl-login-check">
          <input type="checkbox" name="remember" value="1">
          <span>Recordar sesión</span>
        </label>
        <span style="font-size:.7rem; color:rgba(255,255,255,.2);">
          <i class="bi bi-shield-check me-1"></i>
          Conexión segura
        </span>
      </div>

      {{-- Botón submit --}}
      <button type="submit"
              class="vl-login-btn"
              id="btnSubmit">
        <span id="btnText">
          <i class="bi bi-box-arrow-in-right me-2"></i>
          Ingresar al sistema
        </span>
        <span id="btnLoading" style="display:none;">
          <span class="spinner-border spinner-border-sm me-2"
                role="status"></span>
          Verificando credenciales…
        </span>
      </button>

    </form>

    {{-- ── Separador ── --}}
    <div class="vl-login-sep">
      <span>acceso al portal público</span>
    </div>

    {{-- ── Link al portal ── --}}
    <a href="{{ route('asistencia.index') }}"
       style="display:flex; align-items:center; justify-content:center;
              gap:8px; padding:10px; border-radius:var(--vl-radius-sm);
              border:1px solid rgba(255,255,255,.08);
              color:rgba(255,255,255,.4); text-decoration:none;
              font-size:.78rem; font-weight:500;
              transition:all .2s ease;"
       onmouseover="this.style.background='rgba(255,255,255,.05)';
                    this.style.color='rgba(255,255,255,.7)';
                    this.style.borderColor='rgba(255,255,255,.15)'"
       onmouseout="this.style.background='transparent';
                   this.style.color='rgba(255,255,255,.4)';
                   this.style.borderColor='rgba(255,255,255,.08)'">
      <i class="bi bi-person-check-fill" style="color:#10b981;"></i>
      Registrar asistencia ciudadana
      <i class="bi bi-arrow-right" style="font-size:.75rem;"></i>
    </a>

    {{-- ── Footer ── --}}
    <div style="margin-top:24px; padding-top:18px;
                border-top:1px solid rgba(255,255,255,.06);
                text-align:center;">
      <p style="font-size:.68rem; color:rgba(255,255,255,.2); margin:0;">
        <i class="bi bi-shield-lock me-1"></i>
        Acceso restringido — Personal autorizado únicamente
      </p>
      <p style="font-size:.62rem; color:rgba(255,255,255,.12);
                margin:6px 0 0;">
        Municipalidad Provincial del Cusco &copy; {{ date('Y') }}
      </p>
    </div>

  </div>

</div>

{{-- Info del sistema (esquina) --}}
<div class="vl-login-sys-info">
  Sistema Vaso de Leche v2.0<br>
  PHP {{ PHP_VERSION }} · Laravel {{ app()->version() }}
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function () {

  // ── Toggle ojo contraseña ──
  const btnToggle  = document.getElementById('btnTogglePwd');
  const inputPwd   = document.getElementById('password');
  const iconToggle = document.getElementById('iconTogglePwd');

  btnToggle?.addEventListener('click', () => {
    const visible = inputPwd.type === 'text';
    inputPwd.type = visible ? 'password' : 'text';
    iconToggle.className = visible ? 'bi bi-eye-fill' : 'bi bi-eye-slash-fill';
    btnToggle.style.color = visible
      ? 'rgba(255,255,255,.3)'
      : 'rgba(59,130,246,.8)';
  });

  // ── Detección de Caps Lock ──
  const capsWarning = document.getElementById('capsWarning');

  inputPwd?.addEventListener('keyup', (e) => {
    if (capsWarning) {
      capsWarning.classList.toggle('show', e.getModifierState('CapsLock'));
    }
  });

  inputPwd?.addEventListener('focus', (e) => {
    if (capsWarning) {
      capsWarning.classList.toggle('show', e.getModifierState('CapsLock'));
    }
  });

  inputPwd?.addEventListener('blur', () => {
    capsWarning?.classList.remove('show');
  });

  // ── Loading al submit ──
  const form       = document.getElementById('loginForm');
  const btnText    = document.getElementById('btnText');
  const btnLoading = document.getElementById('btnLoading');
  const btnSubmit  = document.getElementById('btnSubmit');

  form?.addEventListener('submit', (e) => {
    // Solo mostrar loading si hay valores
    const email = document.getElementById('email').value.trim();
    const pwd   = inputPwd.value;

    if (email && pwd) {
      btnText.style.display    = 'none';
      btnLoading.style.display = 'inline-flex';
      btnLoading.style.alignItems = 'center';
      btnSubmit.disabled = true;

      // Timeout de seguridad por si hay error de red
      setTimeout(() => {
        btnText.style.display    = 'inline';
        btnLoading.style.display = 'none';
        btnSubmit.disabled = false;
      }, 8000);
    }
  });

  // ── Dark mode ──
  const isDark = localStorage.getItem('vl_dark_mode') === '1'
    || (!localStorage.getItem('vl_dark_mode')
        && window.matchMedia('(prefers-color-scheme: dark)').matches);
  if (isDark) document.documentElement.classList.add('dark');

  // ── Shake animation si hay errores ──
  @if($errors->any())
    const card = document.querySelector('.vl-login-card');
    if (card) {
      card.style.animation = 'none';
      setTimeout(() => {
        card.style.animation = 'shake .4s ease';
      }, 10);
    }
  @endif

})();
</script>

<style>
  @keyframes shake {
    0%, 100% { transform: translateX(0); }
    20%       { transform: translateX(-8px); }
    40%       { transform: translateX(8px); }
    60%       { transform: translateX(-5px); }
    80%       { transform: translateX(5px); }
  }
</style>
<script>
function togglePassword() {
  const input = document.getElementById('password');
  const icon  = document.getElementById('iconTogglePwd');
  if (input.type === 'password') {
    input.type = 'text';
    icon.className = 'bi bi-eye-slash-fill';
  } else {
    input.type = 'password';
    icon.className = 'bi bi-eye-fill';
  }
}
</script>
</body>
</html>