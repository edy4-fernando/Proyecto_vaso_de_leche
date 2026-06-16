<!DOCTYPE html>
<html lang="es" class="">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Selección de Panel — Vaso de Leche Cusco</title>

  <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
  <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>

<div class="vl-seleccion-wrap">
  <div class="vl-seleccion-card vl-animate">

    {{-- Logo --}}
    <div style="margin-bottom: 28px;">
      <div style="
        width: 56px; height: 56px;
        border-radius: var(--vl-radius-lg);
        background: linear-gradient(135deg, #2563eb, #7c3aed);
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 1.4rem; color: #fff; margin-bottom: 14px;
        box-shadow: 0 8px 24px rgba(37,99,235,.4);
      ">
        <i class="bi bi-droplet-half"></i>
      </div>

      <h1 class="vl-seleccion-title">¿Dónde desea ingresar?</h1>
      <p class="vl-seleccion-sub">
        Bienvenido,
        <strong style="color: rgba(255,255,255,.7);">
          {{ auth()->user()->name }}
        </strong>
        — Seleccione el panel de trabajo
      </p>
    </div>

    {{-- Opciones: solo 2 --}}
    <div class="vl-seleccion-options">

      {{-- Panel de Administración --}}
      <a href="{{ route('admin.dashboard') }}"
         class="vl-seleccion-opt vl-animate vl-animate--delay-1">
        <div class="vl-seleccion-opt__icon vl-seleccion-opt__icon--blue">
          <i class="bi bi-speedometer2"></i>
        </div>
        <div class="vl-seleccion-opt__body">
          <span class="vl-seleccion-opt__title">Panel de Administración</span>
          <span class="vl-seleccion-opt__desc">
            Gestión de beneficiarios, entregas, inventario y personal
          </span>
        </div>
        <i class="bi bi-chevron-right"
           style="color: rgba(255,255,255,.3); font-size: .85rem;"></i>
      </a>

      {{-- Dashboard Estadísticas --}}
      @if(auth()->user()->rol === 'maestro')
        <a href="{{ route('dashboard.alertas') }}"
          class="vl-seleccion-opt vl-animate vl-animate--delay-2">
          <div class="vl-seleccion-opt__icon vl-seleccion-opt__icon--violet">
            <i class="bi bi-graph-up-arrow"></i>
          </div>
          <div class="vl-seleccion-opt__body">
            <span class="vl-seleccion-opt__title">Dashboard Estadísticas</span>
            <span class="vl-seleccion-opt__desc">
              Reportes, alertas y análisis del programa
            </span>
          </div>
          <i class="bi bi-chevron-right"
            style="color: rgba(255,255,255,.3); font-size: .85rem;"></i>
        </a>
      @endif

    </div>

    {{-- Cerrar sesión --}}
    <div style="margin-top: 28px; padding-top: 20px;
                border-top: 1px solid rgba(255,255,255,.08);">
      <form action="{{ route('logout') }}" method="POST" class="m-0">
        @csrf
        <button type="submit" style="
          background: none; border: none;
          color: rgba(255,255,255,.3); font-size: .75rem;
          cursor: pointer; display: flex; align-items: center;
          gap: 6px; margin: 0 auto; padding: 6px 12px;
          border-radius: var(--vl-radius-sm);
          transition: color .2s ease;
        "
        onmouseover="this.style.color='rgba(244,63,94,.8)'"
        onmouseout="this.style.color='rgba(255,255,255,.3)'">
          <i class="bi bi-box-arrow-right"></i>
          Cerrar sesión
        </button>
      </form>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const isDark = localStorage.getItem('vl_dark_mode') === '1'
    || (!localStorage.getItem('vl_dark_mode')
        && window.matchMedia('(prefers-color-scheme: dark)').matches);
  if (isDark) document.documentElement.classList.add('dark');
</script>

</body>
</html>