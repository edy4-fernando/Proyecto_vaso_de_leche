{{-- ============================================================
     dashboard/_components/navbar.blade.php
     ============================================================ --}}

<header class="vl-navbar">

  {{-- Hamburguesa móvil --}}
  <button class="vl-navbar__hamburger" id="btnHamburger" title="Abrir menú">
    <i class="bi bi-list"></i>
  </button>

  {{-- Breadcrumb --}}
  <div class="vl-navbar__breadcrumb">
    <span class="vl-navbar__breadcrumb-root">
      <i class="bi bi-graph-up-arrow"></i>
      Dashboard
    </span>
    @hasSection('breadcrumb')
      <span class="vl-navbar__breadcrumb-sep">
        <i class="bi bi-chevron-right"></i>
      </span>
      <span class="vl-navbar__breadcrumb-current">
        @yield('breadcrumb')
      </span>
    @endif
  </div>

  {{-- Acciones --}}
  <div class="vl-navbar__actions">
    {{-- Ir al Admin --}}
    <a href="{{ route('admin.dashboard') }}"
      class="vl-navbar__dropdown-item">
      <i class="bi bi-speedometer2"></i>
      Ir al Admin
    </a>
    {{-- Dark mode --}}
    <button class="vl-navbar__darkmode" id="btnDarkMode" title="Modo oscuro">
      <i class="bi bi-moon-fill"></i>
    </button>

    {{-- Alertas --}}
    <a href="{{ route('dashboard.alertas') }}"
       class="vl-navbar__icon-btn"
       title="Alertas del sistema">
      <i class="bi bi-bell-fill"></i>
      @php
        $navDbAlertas = \App\Models\Producto::whereColumn('stock_actual','<=','stock_minimo')->count()
          + \App\Models\Producto::whereNotNull('fecha_vencimiento')
              ->whereDate('fecha_vencimiento','<=', now()->addDays(30))
              ->whereDate('fecha_vencimiento','>=', now())->count();
      @endphp
      @if($navDbAlertas > 0)
        <span class="vl-navbar__notif-badge"></span>
      @endif
    </a>

    <div class="vl-navbar__divider"></div>

    {{-- Dropdown usuario --}}
    <div class="vl-navbar__user">
      <button class="vl-navbar__user-btn"
              id="btnUserMenu"
              aria-expanded="false"
              aria-haspopup="true">
        @include('admin._components.avatar', [
          'name'  => auth()->user()->name,
          'class' => 'vl-navbar__avatar',
        ])
        <div class="vl-navbar__user-info">
          <span class="vl-navbar__user-name">{{ auth()->user()->name }}</span>
          <span class="vl-navbar__user-role">{{ ucfirst(auth()->user()->rol) }}</span>
        </div>
        <i class="bi bi-chevron-down vl-navbar__user-chevron"></i>
      </button>

      <div class="vl-navbar__dropdown" id="vlUserDropdown">

        {{-- Header --}}
        <div class="vl-navbar__dropdown-header">
          <span class="vl-navbar__dropdown-username">
            {{ auth()->user()->name }}
          </span>
          <span class="vl-navbar__dropdown-email">
            {{ auth()->user()->email }}
          </span>
          <span class="vl-navbar__dropdown-badge {{ auth()->user()->rol }}">
            <i class="bi bi-{{ auth()->user()->rol === 'maestro' ? 'shield-fill' : 'person-fill' }}"></i>
            {{ ucfirst(auth()->user()->rol) }}
          </span>
        </div>

        {{-- Último ingreso --}}
        @if(auth()->user()->ultimo_ingreso)
          <div style="padding:6px 10px; font-size:.68rem; color:var(--vl-text-muted);">
            <i class="bi bi-clock me-1"></i>
            Último ingreso:
            {{ \Carbon\Carbon::parse(auth()->user()->ultimo_ingreso)->format('d/m/Y H:i') }}
          </div>
        @endif

        {{-- Mi cuenta --}}
        <a href="{{ route('cuenta.index') }}"
           class="vl-navbar__dropdown-item">
          <i class="bi bi-person-circle"></i>
          Mi cuenta
        </a>

        {{-- Cambiar panel (maestro) --}}
        @if(auth()->user()->rol === 'maestro')
          <a href="{{ route('seleccion.panel') }}"
             class="vl-navbar__dropdown-item">
            <i class="bi bi-grid-fill"></i>
            Cambiar panel
          </a>
        @endif

        {{-- Cerrar sesión --}}
        <form action="{{ route('logout') }}" method="POST" class="m-0">
          @csrf
          <button type="submit" class="vl-navbar__dropdown-item logout">
            <i class="bi bi-box-arrow-right"></i>
            Cerrar sesión
          </button>
        </form>

      </div>
    </div>

  </div>

</header>

{{-- ============================================================
     MODAL: Ajustes de cuenta
     ============================================================ --}}
<div class="modal fade vl-modal" id="modalAjustes" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="bi bi-sliders text-primary"></i>
          Ajustes de cuenta
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('perfil.cambiar-password') }}" method="POST">
        @csrf
        <div class="modal-body">

          {{-- Info del usuario --}}
          <div style="
            display:flex; align-items:center; gap:12px;
            padding:14px; background:var(--vl-bg-body);
            border-radius:var(--vl-radius); margin-bottom:20px;
          ">
            @include('admin._components.avatar', [
              'name'  => auth()->user()->name,
              'class' => '',
              'size'  => 'lg',
            ])
            <div>
              <div style="font-size:.88rem; font-weight:700;
                          color:var(--vl-text-main);">
                {{ auth()->user()->name }}
              </div>
              <div style="font-size:.75rem; color:var(--vl-text-muted);">
                {{ auth()->user()->email }}
              </div>
              <span class="vl-badge vl-badge--{{ auth()->user()->rol === 'maestro' ? 'violet' : 'blue' }} mt-1">
                {{ ucfirst(auth()->user()->rol) }}
              </span>
            </div>
          </div>

          <div class="vl-divider"></div>

          <p class="vl-label mb-3">Cambiar contraseña</p>

          <div class="vl-form-group">
            <label class="vl-label">Contraseña actual <span class="req">*</span></label>
            <input type="password" name="password_actual"
                   class="vl-input" placeholder="••••••••" required>
          </div>
          <div class="vl-form-group">
            <label class="vl-label">Nueva contraseña <span class="req">*</span></label>
            <input type="password" name="password_nuevo"
                   class="vl-input" placeholder="Mínimo 8 caracteres"
                   minlength="8" required>
          </div>
          <div class="vl-form-group" style="margin-bottom:0;">
            <label class="vl-label">Confirmar contraseña <span class="req">*</span></label>
            <input type="password" name="password_nuevo_confirmation"
                   class="vl-input" placeholder="Repetir contraseña" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="vl-btn vl-btn--outline"
                  data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="vl-btn vl-btn--primary">
            <i class="bi bi-check-lg"></i> Guardar cambios
          </button>
        </div>
      </form>
    </div>
  </div>
</div>