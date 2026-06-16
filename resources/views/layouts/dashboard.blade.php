<!DOCTYPE html>
<html lang="es" class="">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @yield('meta-refresh')
  <title>@yield('title', 'Dashboard — Vaso de Leche Cusco')</title>

  {{-- ── Aplicar tema ANTES del render (evita flash) ── --}}
  <script>
    (function () {
      var theme  = localStorage.getItem('vl_db_theme') || 'oceano';
      var custom = JSON.parse(localStorage.getItem('vl_db_custom') || 'null');
      var root   = document.documentElement;

      // Guardar en dataset para que CSS lo lea
      root.setAttribute('data-db-theme', theme);

      // Si es personalizado, inyectar variables CSS
      if (theme === 'custom' && custom) {
        var style = document.createElement('style');
        style.id  = 'themeCustomVars';
        style.textContent =
          ':root {' +
          '--db-sidebar-bg: '       + (custom.bg      || '#0f172a') + ';' +
          '--db-custom-bg: '        + (custom.bg      || '#0f172a') + ';' +
          '--db-custom-accent: '    + (custom.accent  || '#3b82f6') + ';' +
          '--db-custom-accent2: '   + (custom.accent2 || '#7c3aed') + ';' +
          '--db-sidebar-accent: '   + (custom.accent  || '#3b82f6') + ';' +
          '--db-accent-gradient: linear-gradient(135deg,' +
            (custom.accent || '#3b82f6') + ',' +
            (custom.accent2 || '#7c3aed') + ');' +
          '}';
        document.head.appendChild(style);
      }

      // Dark mode
      if (localStorage.getItem('vl_dark_mode') === '1') {
        root.classList.add('dark');
      }
    })();
  </script>

  <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">

  <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
  <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
  <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
  <link rel="stylesheet" href="{{ asset('css/dashboard-theme.css') }}">

  @stack('styles')
</head>
<body>

<div class="vl-sidebar-overlay" id="vlOverlay"></div>

<div class="vl-layout">

  @include('dashboard._components.sidebar')

  <div class="vl-layout__body" id="vlBody">

    @include('dashboard._components.navbar')

    <main class="vl-main">
      @include('admin._components.toast')
      @yield('content')
    </main>

  </div>

</div>

@yield('modals')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/admin.js') }}"></script>
<script src="{{ asset('js/dashboard-theme.js') }}"></script>
@stack('scripts')

</body>
</html>