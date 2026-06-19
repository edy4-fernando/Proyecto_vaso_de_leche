{{-- ============================================================
     dashboard/_components/sidebar.blade.php
     ============================================================ --}}

<aside class="vl-db-sidebar" id="vlDbSidebar">

  {{-- ── HEADER ── --}}
  <div class="vl-db-sidebar__header">
    <a href="{{ route('dashboard.index') }}" class="vl-db-sidebar__brand">
      <div class="vl-db-sidebar__brand-icon">
        <i class="bi bi-graph-up-arrow"></i>
      </div>
      <div>
        <span class="vl-db-sidebar__brand-title">Vaso de Leche</span>
        <span class="vl-db-sidebar__brand-sub">Dashboard Analytics</span>
      </div>
    </a>
    <button class="vl-db-sidebar__toggle"
            id="btnDbSidebarToggle"
            title="Colapsar menú">
      <i class="bi bi-layout-sidebar-reverse"></i>
    </button>
  </div>

  {{-- ── NAVEGACIÓN ── --}}
  <nav class="vl-db-nav">

    <span class="vl-db-nav__label">Análisis</span>

    <a href="{{ route('dashboard.index') }}"
      class="vl-db-nav__link {{ ($activeModule ?? '') === 'estadisticas' ? 'active' : '' }}">
      <i class="bi bi-bar-chart-fill vl-db-nav__icon"></i>
      <span class="vl-db-nav__link-text">Estadísticas</span>
    </a>

    <a href="{{ route('dashboard.actividad') }}"
      class="vl-db-nav__link {{ ($activeModule ?? '') === 'actividad' ? 'active' : '' }}">
      <i class="bi bi-list-check vl-db-nav__icon"></i>
      <span class="vl-db-nav__link-text">Actividad</span>
      @php
        $actHoy = \App\Models\ActividadLog::whereDate('created_at', today())->count();
      @endphp
      @if($actHoy > 0)
        <span class="vl-db-nav__badge">{{ $actHoy }}</span>
      @endif
    </a>

    <span class="vl-db-nav__label">Reportes</span>

    <a href="{{ route('dashboard.historial') }}"
      class="vl-db-nav__link {{ ($activeModule ?? '') === 'historial' ? 'active' : '' }}">
      <i class="bi bi-clock-history vl-db-nav__icon"></i>
      <span class="vl-db-nav__link-text">Historial</span>
    </a>

    <a href="{{ route('dashboard.alertas') }}"
      class="vl-db-nav__link {{ ($activeModule ?? '') === 'alertas' ? 'active' : '' }}">
      <i class="bi bi-exclamation-triangle-fill vl-db-nav__icon"></i>
      <span class="vl-db-nav__link-text">Alertas</span>
      @php
        $dbAlertas = \App\Models\Producto::whereColumn('stock_actual','<=','stock_minimo')->count()
          + \App\Models\Producto::whereNotNull('fecha_vencimiento')
              ->whereDate('fecha_vencimiento','<=', now()->addDays(30))
              ->whereDate('fecha_vencimiento','>=', now())->count();
      @endphp
      @if($dbAlertas > 0)
        <span class="vl-db-nav__badge">{{ $dbAlertas }}</span>
      @endif
    </a>

  </nav>

  {{-- ── FOOTER ── --}}
  <div class="vl-db-sidebar__footer">

    {{-- Configuración --}}
    @if(auth()->user()->rol === 'maestro')
      <a href="{{ route('dashboard.configuracion') }}"
        class="vl-db-footer__config {{ ($activeModule ?? '') === 'configuracion' ? 'active' : '' }}">
        <i class="bi bi-gear-fill"></i>
        <span class="vl-db-nav__link-text">Configuración</span>
      </a>
    @endif

    <div class="vl-db-footer__divider"></div>

    {{-- Usuario --}}
    <div class="vl-db-footer__user">
      <div class="vl-db-footer__avatar">
        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
      </div>
      <div class="vl-db-footer__info">
        <span class="vl-db-footer__name">{{ auth()->user()->name }}</span>
        <span class="vl-db-footer__role">{{ ucfirst(auth()->user()->rol) }}</span>
      </div>
    </div>

  </div>

</aside>

