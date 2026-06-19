{{-- ============================================================
     admin/_components/sidebar.blade.php
     Sidebar del Panel Admin - gestion operativa
     ============================================================ --}}

<aside class="vl-sidebar" id="vlSidebar">

  {{-- HEADER --}}
  <div class="vl-sidebar__header">
    <a href="{{ route('admin.dashboard') }}" class="vl-sidebar__brand">
      <div class="vl-sidebar__brand-icon"
          style="background: linear-gradient(135deg, #7c3aed, #4f46e5);">
        <i class="bi bi-speedometer2"></i>
      </div>
      <div class="vl-sidebar__brand-text">
        <span class="vl-sidebar__brand-title">Vaso de Leche</span>
        <span class="vl-sidebar__brand-sub">Panel Administrativo</span>
      </div>
    </a>
    <button class="vl-sidebar__toggle"
            id="btnSidebarToggle"
            title="Colapsar menu">
      <i class="bi bi-layout-sidebar-reverse"></i>
    </button>
  </div>

  {{-- NAVEGACION --}}
  <nav class="vl-nav">

    <span class="vl-nav__label">Operaciones</span>

    <a href="{{ route('admin.dashboard') }}"
       class="vl-nav__link {{ ($activeModule ?? '') === 'dashboard' ? 'active' : '' }}"
       data-bs-toggle="tooltip" data-bs-placement="right"
       title="Registrar entregas del dia">
      <i class="bi bi-plus-circle-fill vl-nav__icon"></i>
      <span class="vl-nav__link-text">Registrar Entrega</span>
    </a>

    <a href="{{ route('admin.beneficiarios') }}"
       class="vl-nav__link {{ ($activeModule ?? '') === 'beneficiarios' ? 'active' : '' }}"
       data-bs-toggle="tooltip" data-bs-placement="right"
       title="Padron de beneficiarios">
      <i class="bi bi-people-fill vl-nav__icon"></i>
      <span class="vl-nav__link-text">Beneficiarios</span>
    </a>

    <a href="{{ route('admin.entregas') }}"
       class="vl-nav__link {{ ($activeModule ?? '') === 'entregas' ? 'active' : '' }}"
       data-bs-toggle="tooltip" data-bs-placement="right"
       title="Historial de entregas">
      <i class="bi bi-journal-check vl-nav__icon"></i>
      <span class="vl-nav__link-text">Entregas</span>
    </a>

    <a href="{{ route('admin.mi-actividad') }}"
       class="vl-nav__link {{ ($activeModule ?? '') === 'mi-actividad' ? 'active' : '' }}"
       data-bs-toggle="tooltip" data-bs-placement="right"
       title="Tu historial de acciones">
      <i class="bi bi-clock-history vl-nav__icon"></i>
      <span class="vl-nav__link-text">Mi Actividad</span>
    </a>

    @if(auth()->user()->rol === 'maestro')

      <span class="vl-nav__label">Administracion</span>

      <a href="{{ route('admin.productos') }}"
         class="vl-nav__link {{ ($activeModule ?? '') === 'productos' ? 'active' : '' }}"
         data-bs-toggle="tooltip" data-bs-placement="right"
         title="Inventario de productos">
        <i class="bi bi-box-seam vl-nav__icon"></i>
        <span class="vl-nav__link-text">Inventario</span>
      </a>
      <a href="{{ route('admin.papelera') }}"
         class="vl-nav__link {{ ($activeModule ?? '') === 'papelera' ? 'active' : '' }}"
         data-bs-toggle="tooltip" data-bs-placement="right"
         title="Papelera de registros eliminados">
        <i class="bi bi-trash3-fill vl-nav__icon"></i>
        <span class="vl-nav__link-text">Papelera</span>
      </a>

      <a href="{{ route('admin.usuarios') }}"
         class="vl-nav__link danger {{ ($activeModule ?? '') === 'usuarios' ? 'active' : '' }}"
         data-bs-toggle="tooltip" data-bs-placement="right"
         title="Gestionar personal">
        <i class="bi bi-shield-lock-fill vl-nav__icon"></i>
        <span class="vl-nav__link-text">Personal</span>
      </a>

    @endif

  </nav>

  {{-- FOOTER: Configuracion + Usuario --}}
  <div class="vl-sidebar__footer">

 

    {{-- Configuracion (solo maestro) --}}
    @if(auth()->user()->rol === 'maestro')
      <a href="{{ route('dashboard.configuracion') }}"
         class="vl-nav__link {{ ($activeModule ?? '') === 'configuracion' ? 'active' : '' }}"
         style="margin-bottom:4px;">
        <i class="bi bi-gear-fill vl-nav__icon"></i>
        <span class="vl-nav__link-text">Configuracion</span>
      </a>
      <div style="height:1px; background:rgba(255,255,255,.06); margin:4px 0;"></div>
    @endif

    {{-- Usuario --}}
    <div class="vl-sidebar__user">
      <div class="vl-sidebar__avatar">
        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
      </div>
      <div class="vl-sidebar__footer-info">
        <span class="vl-sidebar__footer-name">
          {{ auth()->user()->name }}
        </span>
        <span class="vl-sidebar__footer-role">
          {{ ucfirst(auth()->user()->rol) }}
        </span>
      </div>
    </div>

  </div>

</aside>
