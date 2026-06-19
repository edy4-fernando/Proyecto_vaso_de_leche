{{-- ============================================================
     admin/_components/navbar.blade.php
     Barra superior: hamburguesa | breadcrumb | acciones | avatar
     ============================================================ --}}

<header class="vl-navbar">

  {{-- ── Hamburguesa (solo móvil) ── --}}
  <button class="vl-navbar__hamburger" id="btnHamburger" title="Abrir menú">
    <i class="bi bi-list"></i>
  </button>

  {{-- ── Breadcrumb ── --}}
  <div class="vl-navbar__breadcrumb">
    <span class="vl-navbar__breadcrumb-root">
      <i class="bi bi-house-fill"></i>
      Panel
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

  {{-- ── Acciones derechas ── --}}
  <div class="vl-navbar__actions">
    {{-- Ir al Dashboard --}}
    @if(auth()->user()->rol === 'maestro')
      <a href="{{ route('dashboard.index') }}"
        class="vl-navbar__dropdown-item">
        <i class="bi bi-graph-up-arrow"></i>
        Ir al Dashboard
      </a>
    @endif

    {{-- Dark mode --}}
    <button class="vl-navbar__darkmode"
            id="btnDarkMode"
            title="Modo oscuro">
      <i class="bi bi-moon-fill"></i>
    </button>

    {{-- Notificaciones (enlace a alertas) --}}
    <a href="{{ route('dashboard.alertas') }}"
       class="vl-navbar__icon-btn"
       title="Alertas del sistema">
      <i class="bi bi-bell-fill"></i>
      @php
        $navAlertas = \App\Models\Producto::whereColumn('stock_actual','<=','stock_minimo')->count()
          + \App\Models\Producto::whereNotNull('fecha_vencimiento')
              ->whereDate('fecha_vencimiento','<=', now()->addDays(30))
              ->whereDate('fecha_vencimiento','>=', now())->count();
      @endphp
      @if($navAlertas > 0)
        <span class="vl-navbar__notif-badge"></span>
      @endif
    </a>

    <div class="vl-navbar__divider"></div>

    {{-- ── Dropdown de usuario ── --}}
    <div class="vl-navbar__user">

      <button class="vl-navbar__user-btn"
              id="btnUserMenu"
              aria-expanded="false"
              aria-haspopup="true">
        {{-- Avatar con iniciales --}}
        @include('admin._components.avatar', [
          'name'  => auth()->user()->name,
          'class' => 'vl-navbar__avatar',
        ])
        <div class="vl-navbar__user-info">
          <span class="vl-navbar__user-name">
            {{ auth()->user()->name }}
          </span>
          <span class="vl-navbar__user-role">
            {{ ucfirst(auth()->user()->rol) }}
          </span>
        </div>
        <i class="bi bi-chevron-down vl-navbar__user-chevron"></i>
      </button>

      {{-- Menú desplegable --}}
      <div class="vl-navbar__dropdown" id="vlUserDropdown">

        {{-- Encabezado --}}
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
          <div style="padding: 6px 10px; font-size: 0.68rem; color: var(--vl-text-muted);">
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

        {{-- Selección de panel (solo maestro) --}}
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

      </div>{{-- /dropdown --}}

    </div>{{-- /vl-navbar__user --}}

  </div>{{-- /vl-navbar__actions --}}

</header>

{{-- ============================================================
     MODAL: Ajustes de cuenta
     ============================================================ --}}
<div class="modal fade vl-modal" id="modalCambiarPassword" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">
          <i class="bi bi-sliders text-primary"></i>
          Ajustes de cuenta
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="row g-4">

          {{-- Info del usuario --}}
          <div class="col-md-5">
            <div style="background:var(--vl-bg-body); border-radius:var(--vl-radius);
                        padding:20px; text-align:center; margin-bottom:16px;">
              @include('admin._components.avatar', [
                'name'  => auth()->user()->name,
                'size'  => 'xl',
                'class' => '',
              ])
              <div style="margin-top:12px;">
                <div style="font-size:.95rem; font-weight:800; color:var(--vl-text-main);">
                  {{ auth()->user()->name }}
                </div>
                <div style="font-size:.75rem; color:var(--vl-text-muted); margin:4px 0;">
                  {{ auth()->user()->email }}
                </div>
                <span class="vl-badge vl-badge--{{ auth()->user()->rol === 'maestro' ? 'violet' : 'blue' }}">
                  <i class="bi bi-{{ auth()->user()->rol === 'maestro' ? 'shield-fill' : 'person-fill' }}"></i>
                  {{ ucfirst(auth()->user()->rol) }}
                </span>
              </div>
            </div>

            @php
              $infoU = [
                ['DNI',           auth()->user()->dni ?? '—',      'bi-card-text'],
                ['Teléfono',      auth()->user()->telefono ?? '—', 'bi-telephone-fill'],
                ['Último ingreso',auth()->user()->ultimo_ingreso
                  ? \Carbon\Carbon::parse(auth()->user()->ultimo_ingreso)->format('d/m/Y H:i')
                  : 'Primera sesión', 'bi-clock-fill'],
                ['Miembro desde', \Carbon\Carbon::parse(auth()->user()->created_at)->format('d/m/Y'),
                  'bi-calendar-check-fill'],
              ];
            @endphp

            @foreach($infoU as [$label, $valor, $icon])
              <div style="display:flex; align-items:center; gap:8px; padding:8px 0;
                          {{ !$loop->last ? 'border-bottom:1px solid var(--vl-border);' : '' }}">
                <i class="bi {{ $icon }}"
                   style="color:var(--vl-blue-600); font-size:.8rem; width:16px;"></i>
                <div>
                  <div style="font-size:.62rem; color:var(--vl-text-muted);
                              text-transform:uppercase; letter-spacing:.5px;">{{ $label }}</div>
                  <div style="font-size:.78rem; color:var(--vl-text-main); font-weight:500;">
                    {{ $valor }}
                  </div>
                </div>
              </div>
            @endforeach
          </div>

          {{-- Cambiar contraseña --}}
          <div class="col-md-7">
            <p class="vl-label mb-3">
              <i class="bi bi-key-fill me-1"></i>
              Cambiar contraseña
            </p>

            <div class="vl-alert vl-alert--info mb-3">
              <i class="bi bi-info-circle-fill"></i>
              <div style="font-size:.78rem;">
                Mínimo 8 caracteres con letras, números y símbolos.
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
                         id="adminPwdNuevo"
                         class="vl-input" placeholder="Mínimo 8 caracteres"
                         minlength="8" required>
                </div>
                <div style="margin-top:6px;">
                  <div style="background:var(--vl-border); border-radius:99px;
                              height:4px; overflow:hidden;">
                    <div id="adminPwdBar"
                         style="height:100%; width:0%; border-radius:99px;
                                transition:all .3s; background:#f43f5e;"></div>
                  </div>
                  <span id="adminPwdTxt"
                        style="font-size:.68rem; color:var(--vl-text-muted); margin-top:2px; display:block;">
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
                         id="adminPwdConfirm"
                         class="vl-input" placeholder="Repetir contraseña" required>
                </div>
                <span id="adminPwdMatch"
                      style="font-size:.68rem; display:none; margin-top:3px;"></span>
              </div>

              <div class="vl-divider"></div>

              <div style="display:flex; gap:8px;">
                <button type="button" class="vl-btn vl-btn--outline"
                        data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="vl-btn vl-btn--primary">
                  <i class="bi bi-check-lg"></i>
                  Actualizar contraseña
                </button>
              </div>

            </form>
          </div>

        </div>
      </div>

    </div>
  </div>
</div>

@push('scripts')
<script>
document.getElementById('adminPwdNuevo')?.addEventListener('input', function() {
  const bar = document.getElementById('adminPwdBar');
  const txt = document.getElementById('adminPwdTxt');
  if (!bar || !txt) return;
  let score = 0;
  if (this.value.length >= 8)          score++;
  if (/[A-Z]/.test(this.value))        score++;
  if (/[0-9]/.test(this.value))        score++;
  if (/[^A-Za-z0-9]/.test(this.value)) score++;
  const c=[
    {w:'0%',  c:'#f43f5e',t:'Ingresá una contraseña'},
    {w:'25%', c:'#f43f5e',t:'Muy débil'},
    {w:'50%', c:'#f59e0b',t:'Débil'},
    {w:'75%', c:'#3b82f6',t:'Buena'},
    {w:'100%',c:'#10b981',t:'Muy fuerte ✓'},
  ];
  bar.style.width=c[score].w;
  bar.style.background=c[score].c;
  txt.textContent=c[score].t;
  txt.style.color=c[score].c;
});

document.getElementById('adminPwdConfirm')?.addEventListener('input', function() {
  const nuevo = document.getElementById('adminPwdNuevo')?.value;
  const txt   = document.getElementById('adminPwdMatch');
  if (!txt) return;
  txt.style.display='block';
  if (this.value === nuevo) {
    txt.textContent='✓ Las contraseñas coinciden';
    txt.style.color='#10b981';
  } else {
    txt.textContent='✗ No coinciden';
    txt.style.color='#f43f5e';
  }
});
</script>
@endpush