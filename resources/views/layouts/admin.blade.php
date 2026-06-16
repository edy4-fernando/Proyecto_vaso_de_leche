<!DOCTYPE html>
<html lang="es" class="">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @yield('meta-refresh')
  <title>@yield('title', 'Panel — Vaso de Leche Cusco')</title>

  {{-- Bootstrap 5 --}}
  <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  {{-- Bootstrap Icons --}}
  <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  {{-- Google Fonts: Inter --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">

  {{-- CSS del sistema --}}
  <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
  <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
  <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

  @stack('styles')
</head>
<body>

{{-- ============================================================
     OVERLAY para móvil
     ============================================================ --}}
<div class="vl-sidebar-overlay" id="vlOverlay"></div>

{{-- ============================================================
     LAYOUT PRINCIPAL
     ============================================================ --}}
<div class="vl-layout">

  {{-- SIDEBAR --}}
  @include('admin._components.sidebar')

  {{-- BODY (navbar + contenido) --}}
  <div class="vl-layout__body" id="vlBody">

    {{-- NAVBAR --}}
    @include('admin._components.navbar')

    {{-- CONTENIDO PRINCIPAL --}}
    <main class="vl-main">

      {{-- Toast de sesión Laravel --}}
      @include('admin._components.toast')

      {{-- CONTENIDO DE LA VISTA --}}
      @yield('content')

    </main>

  </div>{{-- /vl-layout__body --}}

</div>{{-- /vl-layout --}}

{{-- MODALES (fuera del layout para evitar z-index) --}}
@yield('modals')

{{-- ============================================================
     SCRIPTS
     ============================================================ --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/admin.js') }}"></script>
@stack('scripts')

</body>
</html>