{{-- ============================================================
     web/_components/navbar.blade.php
     Navbar del portal público ciudadano
     ============================================================ --}}

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
  <div class="container">

    {{-- Marca --}}
    <a class="navbar-brand d-flex align-items-center gap-2"
       href="{{ route('asistencia.index') }}">
      <i class="bi bi-droplet-half text-info"></i>
      Cusco — Vaso de Leche
    </a>

    {{-- Toggle móvil --}}
    <button class="navbar-toggler border-0"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarPortal"
            aria-controls="navbarPortal"
            aria-expanded="false"
            aria-label="Abrir menú">
      <span class="navbar-toggler-icon"></span>
    </button>

    {{-- Links --}}
    <div class="collapse navbar-collapse" id="navbarPortal">
      <ul class="navbar-nav ms-auto align-items-center gap-2">
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('web.servicios') ? 'active' : '' }}"
            href="{{ route('web.servicios') }}">
            Servicios Nutricionales
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('web.noticias') ? 'active' : '' }}"
            href="{{ route('web.noticias') }}">
            Noticias
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('web.contacto') ? 'active' : '' }}"
            href="{{ route('web.contacto') }}">
            Contacto
          </a>
        </li>
        <li class="nav-item ms-lg-2">
          @auth
            <a class="btn-login btn btn-outline-light btn-sm"
              href="{{ auth()->user()->rol === 'maestro' ? route('seleccion.panel') : route('admin.dashboard') }}">
              <i class="bi bi-speedometer2 me-1"></i> IR AL PANEL
            </a>
          @else
            <a class="btn-login btn btn-outline-light btn-sm"
              href="{{ route('login') }}">
              LOGIN
            </a>
          @endauth
        </li>
      </ul>
    </div>

  </div>
</nav>