@extends(request()->routeIs('admin.*') || request()->is('admin*') ? 'layouts.admin' : 'layouts.dashboard')
@section('title', 'Configuración — Vaso de Leche')
@section('breadcrumb', 'Configuración del Sistema')
@php $activeModule = 'configuracion'; @endphp

@section('content')

<div class="vl-section-header">
  <div>
    <h2 class="vl-section-title">
      <i class="bi bi-gear-fill text-primary"></i>
      Configuración del Sistema
    </h2>
    <p class="vl-section-sub">
      Parámetros institucionales y técnicos — Solo maestro
    </p>
  </div>
  <span class="vl-badge vl-badge--violet">
    <i class="bi bi-shield-fill"></i> Solo Maestro
  </span>
</div>

<div class="row g-4">

  {{-- ── Datos institucionales ── --}}
  <div class="col-lg-6 vl-animate">
    <div class="vl-card h-100">
      <div class="vl-card__accent"></div>
      <div class="vl-card__header">
        <h5 class="vl-card__title">
          <i class="bi bi-building text-primary"></i>
          Datos Institucionales
        </h5>
      </div>
      <div class="vl-card__body">
        <form action="{{ route('dashboard.configuracion.guardar') }}"
              method="POST">
          @csrf
          @method('PUT')
          <input type="hidden" name="grupo" value="institucional">

          <div class="vl-form-group">
            <label class="vl-label">Nombre de la institución</label>
            <input type="text" name="nombre_institucion" class="vl-input"
                   value="{{ \App\Models\Configuracion::get('nombre_institucion',
                     'Municipalidad Provincial del Cusco') }}">
          </div>

          <div class="vl-form-group">
            <label class="vl-label">Nombre del programa</label>
            <input type="text" name="nombre_programa" class="vl-input"
                   value="{{ \App\Models\Configuracion::get('nombre_programa',
                     'Vaso de Leche') }}">
          </div>

          <div class="vl-form-group">
            <label class="vl-label">Distrito / Zona</label>
            <input type="text" name="distrito" class="vl-input"
                   value="{{ \App\Models\Configuracion::get('distrito', 'Cusco') }}">
          </div>

          <div class="vl-form-group" style="margin-bottom:0;">
            <label class="vl-label">Responsable del programa</label>
            <input type="text" name="responsable" class="vl-input"
                   value="{{ \App\Models\Configuracion::get('responsable', '') }}"
                   placeholder="Nombre completo del responsable">
          </div>

          <div class="vl-divider"></div>
          <button type="submit" class="vl-btn vl-btn--primary">
            <i class="bi bi-check-lg"></i> Guardar
          </button>
        </form>
      </div>
    </div>
  </div>

  {{-- ── Parámetros operativos ── --}}
  <div class="col-lg-6 vl-animate vl-animate--delay-1">
    <div class="vl-card h-100">
      <div class="vl-card__accent"></div>
      <div class="vl-card__header">
        <h5 class="vl-card__title">
          <i class="bi bi-toggles text-primary"></i>
          Parámetros Operativos
        </h5>
      </div>
      <div class="vl-card__body">
        <form action="{{ route('dashboard.configuracion.guardar') }}"
              method="POST">
          @csrf
          @method('PUT')
          <input type="hidden" name="grupo" value="operativo">

          <div class="vl-form-group">
            <label class="vl-label">
              Límite de raciones diarias por beneficiario
            </label>
            <input type="number" name="limite_entregas_diarias"
                   class="vl-input" min="1" max="5"
                   value="{{ \App\Models\Configuracion::get('limite_entregas_diarias', 1) }}">
            <div style="font-size:.7rem; color:var(--vl-text-muted); margin-top:4px;">
              <i class="bi bi-info-circle me-1"></i>
              Ley N° 27470: máximo 1 ración diaria.
            </div>
          </div>

          <div class="vl-form-group">
            <label class="vl-label">Cantidad por defecto en entrega</label>
            <input type="number" name="cantidad_default"
                   class="vl-input" min="1"
                   value="{{ \App\Models\Configuracion::get('cantidad_default', 1) }}">
          </div>

          <div class="vl-form-group">
            <label class="vl-label">Días de alerta antes del vencimiento</label>
            <input type="number" name="dias_alerta_vencimiento"
                   class="vl-input" min="1" max="90"
                   value="{{ \App\Models\Configuracion::get('dias_alerta_vencimiento', 30) }}">
          </div>

          <div class="vl-form-group" style="margin-bottom:0;">
            <label class="vl-label">Stock mínimo global por defecto</label>
            <input type="number" name="stock_minimo_global"
                   class="vl-input" min="0"
                   value="{{ \App\Models\Configuracion::get('stock_minimo_global', 10) }}">
          </div>

          <div class="vl-divider"></div>
          <button type="submit" class="vl-btn vl-btn--primary">
            <i class="bi bi-check-lg"></i> Guardar parámetros
          </button>
        </form>
      </div>
    </div>
  </div>

  {{-- ── Info del sistema ── --}}
  <div class="col-lg-5 vl-animate">
    <div class="vl-card">
      <div class="vl-card__accent"></div>
      <div class="vl-card__header">
        <h5 class="vl-card__title">
          <i class="bi bi-cpu-fill text-primary"></i>
          Entorno Técnico
        </h5>
      </div>
      <div class="vl-card__body">
        @php
          $infoSys = [
            ['Laravel',       app()->version(),           'bi-box-fill',      '#3b82f6'],
            ['PHP',           PHP_VERSION,                'bi-filetype-php',  '#7c3aed'],
            ['Entorno',       app()->environment(),       'bi-layers-fill',   '#10b981'],
            ['Base de datos', config('database.default'), 'bi-database-fill', '#f59e0b'],
            ['Zona horaria',  config('app.timezone'),     'bi-clock-fill',    '#0891b2'],
          ];
        @endphp
        @foreach($infoSys as [$label, $valor, $icon, $color])
          <div style="display:flex; align-items:center; justify-content:space-between;
                      padding:10px 0;
                      {{ !$loop->last ? 'border-bottom:1px solid var(--vl-border);' : '' }}">
            <div style="display:flex; align-items:center; gap:8px;">
              <i class="bi {{ $icon }}" style="color:{{ $color }}; font-size:.85rem;"></i>
              <span style="font-size:.8rem; color:var(--vl-text-sub);">{{ $label }}</span>
            </div>
            <span style="font-size:.78rem; font-weight:600; color:var(--vl-text-main);
                         font-family:var(--vl-font-mono);">{{ $valor }}</span>
          </div>
        @endforeach
      </div>
    </div>
  </div>

  {{-- ── Estado del sistema ── --}}
  <div class="col-lg-7 vl-animate vl-animate--delay-1">
    <div class="vl-card">
      <div class="vl-card__accent"></div>
      <div class="vl-card__header">
        <h5 class="vl-card__title">
          <i class="bi bi-bar-chart-fill text-primary"></i>
          Estado Actual
        </h5>
        <span style="font-size:.68rem; color:var(--vl-text-muted);">
          {{ now()->format('d/m/Y H:i') }}
        </span>
      </div>
      <div class="vl-card__body">
        <div class="row g-3">
          @php
            $stats = [
              ['Beneficiarios',   \App\Models\Beneficiario::count(),                               'bi-people-fill',       '#3b82f6'],
              ['Activos',         \App\Models\Beneficiario::where('estado',true)->count(),          'bi-person-check-fill', '#10b981'],
              ['Total entregas',  \App\Models\Entrega::count(),                                    'bi-journal-check',     '#7c3aed'],
              ['Entregas hoy',    \App\Models\Entrega::whereDate('fecha_entrega',today())->count(), 'bi-calendar-day',      '#f59e0b'],
              ['Productos',       \App\Models\Producto::count(),                                   'bi-box-seam-fill',     '#0891b2'],
              ['Stock crítico',   \App\Models\Producto::whereColumn('stock_actual','<=','stock_minimo')->count(), 'bi-exclamation-triangle-fill', '#f43f5e'],
              ['Usuarios',        \App\Models\User::count(),                                       'bi-shield-lock-fill',  '#7c3aed'],
              ['Actividad hoy',   \App\Models\ActividadLog::whereDate('created_at',today())->count(), 'bi-activity',       '#10b981'],
            ];
          @endphp
          @foreach($stats as [$label, $valor, $icon, $color])
            <div class="col-6 col-md-3">
              <div style="background:var(--vl-bg-body); border-radius:var(--vl-radius);
                          padding:14px; text-align:center;">
                <i class="bi {{ $icon }}"
                   style="font-size:1.1rem; color:{{ $color }};
                          display:block; margin-bottom:6px;"></i>
                <div style="font-size:1.3rem; font-weight:800;
                            color:var(--vl-text-main); line-height:1;">
                  {{ $valor }}
                </div>
                <div style="font-size:.65rem; color:var(--vl-text-muted);
                            text-transform:uppercase; letter-spacing:.4px; margin-top:3px;">
                  {{ $label }}
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>

</div>

@endsection