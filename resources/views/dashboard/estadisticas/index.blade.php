@extends('layouts.dashboard')

@section('title', 'Estadísticas — Vaso de Leche')
@section('breadcrumb', 'Estadísticas')
@php $activeModule = 'estadisticas'; @endphp

@push('styles')
<style>
/* ── KPI ROW ── */
.vl-kpi-row {
  display: flex; gap: 12px; flex-wrap: nowrap; overflow-x: auto;
  padding-bottom: 4px;
}
.vl-kpi-row::-webkit-scrollbar { height: 4px; }
.vl-kpi-row::-webkit-scrollbar-track { background: transparent; }
.vl-kpi-row::-webkit-scrollbar-thumb { background: var(--vl-border); border-radius: 99px; }

.vl-kpi {
  flex: 1; min-width: 130px;
  background: var(--vl-bg-card);
  border: 1px solid var(--vl-border);
  border-radius: var(--vl-radius-lg);
  box-shadow: var(--vl-shadow-sm);
  padding: 14px 16px;
  display: flex; align-items: center; gap: 12px;
  transition: var(--vl-transition);
}
.vl-kpi:hover { box-shadow: var(--vl-shadow); transform: translateY(-1px); }

.vl-kpi__body { flex: 1; min-width: 0; }
.vl-kpi__value {
  font-size: 1.5rem; font-weight: 800;
  color: var(--vl-text-main); line-height: 1;
  margin-bottom: 3px; letter-spacing: -.5px;
}
.vl-kpi__label {
  font-size: 0.68rem; color: var(--vl-text-muted);
  font-weight: 600; text-transform: uppercase; letter-spacing: .5px;
  white-space: nowrap;
}
/* ── PRINT ── */
@media print {
  .vl-sidebar, .vl-navbar, .vl-topbar,
  .no-print, .vl-stat-config-panel { display: none !important; }
  .vl-main-content { margin: 0 !important; padding: 0 !important; }
  .print-section { page-break-inside: avoid; margin-bottom: 24px; }
  .vl-card { box-shadow: none !important; border: 1px solid #e2e8f0 !important; }
  body { background: white !important; }
  .stat-section[data-hidden="true"] { display: none !important; }
}

/* ── SECCIÓN TOGGLE ── */
.stat-section[data-hidden="true"] { display: none; }
.stat-section { margin-bottom: 40px; }

/* ── SECTION HEADER ── */
.stat-section-header {
  display: flex; align-items: center; gap: 10px;
  margin-bottom: 16px; padding-bottom: 12px;
  border-bottom: 2px solid var(--vl-border);
}
.stat-section-header h3 {
  flex: 1; font-size: .95rem; font-weight: 800;
  color: var(--vl-text-main); margin: 0;
}
.stat-section-accent {
  width: 4px; height: 24px; border-radius: 99px; flex-shrink: 0;
}

/* ── SECTION PILLS (barra de secciones) ── */
.vl-section-pills {
  display: flex; gap: 6px; flex-wrap: wrap;
}
.vl-section-pill {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 6px 14px; border-radius: 99px; font-size: .78rem;
  font-weight: 600; cursor: pointer; border: 1.5px solid var(--vl-border);
  background: var(--vl-bg-card); color: var(--vl-text-sub);
  transition: all .18s ease; user-select: none; white-space: nowrap;
}
.vl-section-pill:hover {
  border-color: #6366f1; color: #6366f1;
  background: rgba(99,102,241,.06);
}
.vl-section-pill.active {
  background: #6366f1; color: #fff;
  border-color: #6366f1; box-shadow: 0 2px 8px rgba(99,102,241,.3);
}
.vl-section-pill i { font-size: .8rem; }

/* ── SECTION WRAPPER — siempre con card ── */
.stat-section-card {
  background: var(--vl-bg-card);
  border: 1px solid var(--vl-border);
  border-radius: var(--vl-radius-lg);
  box-shadow: var(--vl-shadow-sm);
  overflow: hidden;
  margin-bottom: 16px;
}

/* ── SECTION HEADER dentro de la card ── */
.stat-section-header {
  padding: 14px 20px !important;
  margin-bottom: 0 !important;
  border-bottom: 1px solid var(--vl-border) !important;
}

/* ── SECTION BODY ── */
.stat-section-body {
  padding: 20px;
}
.stat-section[data-collapsed="true"] .stat-section-body {
  display: none;
}
.stat-section[data-collapsed="true"] .stat-section-header {
  border-bottom-color: transparent !important;
}

/* ── Botón colapsar ── */
.btn-collapse-section {
  display: inline-flex; align-items: center; justify-content: center;
  width: 28px; height: 28px; border-radius: 8px; border: 1.5px solid var(--vl-border);
  background: transparent; color: var(--vl-text-muted);
  cursor: pointer; transition: all .15s ease; flex-shrink: 0;
}
.btn-collapse-section:hover {
  border-color: #6366f1; color: #6366f1;
  background: rgba(99,102,241,.06);
}
</style>
@endpush

@section('content')

{{-- ── BARRA SUPERIOR: filtros + acciones ── --}}
<div class="vl-card mb-4 no-print vl-animate">
  <div class="vl-card__body" style="padding:12px 20px;">
    <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">

      {{-- Filtro de fechas --}}
      <form method="GET" action="{{ route('dashboard.index') }}"
            style="display:flex; align-items:center; gap:8px; flex:1; flex-wrap:wrap;">
        <span style="font-size:.78rem; font-weight:600; color:var(--vl-text-sub); white-space:nowrap;">
          <i class="bi bi-calendar3 me-1"></i> Rango:
        </span>
        <input type="date" name="desde" class="vl-input"
               value="{{ request('desde', now()->subDays(30)->format('Y-m-d')) }}"
               style="width:140px;">
        <span style="color:var(--vl-text-muted); font-size:.8rem;">→</span>
        <input type="date" name="hasta" class="vl-input"
               value="{{ request('hasta', now()->format('Y-m-d')) }}"
               style="width:140px;">
        <button type="submit" class="vl-btn vl-btn--primary vl-btn--sm">
          <i class="bi bi-funnel-fill me-1"></i>Filtrar
        </button>
        {{-- Atajos --}}
        <div style="display:flex; gap:6px; flex-wrap:wrap;">
          <a href="{{ route('dashboard.index', ['desde'=>now()->format('Y-m-d'),'hasta'=>now()->format('Y-m-d')]) }}"
             class="vl-btn vl-btn--outline vl-btn--sm">Hoy</a>
          <a href="{{ route('dashboard.index', ['desde'=>now()->startOfWeek()->format('Y-m-d'),'hasta'=>now()->format('Y-m-d')]) }}"
             class="vl-btn vl-btn--outline vl-btn--sm">Semana</a>
          <a href="{{ route('dashboard.index', ['desde'=>now()->startOfMonth()->format('Y-m-d'),'hasta'=>now()->format('Y-m-d')]) }}"
             class="vl-btn vl-btn--outline vl-btn--sm">Mes</a>
          <a href="{{ route('dashboard.index', ['desde'=>now()->subDays(90)->format('Y-m-d'),'hasta'=>now()->format('Y-m-d')]) }}"
             class="vl-btn vl-btn--outline vl-btn--sm">90 días</a>
        </div>
      </form>

      {{-- Acciones globales --}}
      <div style="display:flex; gap:8px; flex-shrink:0;">
        <button onclick="window.print()" class="vl-btn vl-btn--outline vl-btn--sm no-print">
          <i class="bi bi-printer-fill me-1"></i>Imprimir
        </button>
      </div>

    </div>
  </div>
</div>

{{-- ── KPI CARDS ── --}}
<div class="vl-kpi-row mb-3 print-section">

  <div class="vl-kpi vl-animate">
    <div class="vl-stat__icon vl-stat__icon--blue"><i class="bi bi-people-fill"></i></div>
    <div class="vl-kpi__body">
      <div class="vl-kpi__value">{{ $totalBeneficiarios }}</div>
      <div class="vl-kpi__label">Beneficiarios</div>
    </div>
  </div>

  <div class="vl-kpi vl-animate vl-animate--delay-1">
    <div class="vl-stat__icon vl-stat__icon--green"><i class="bi bi-person-check-fill"></i></div>
    <div class="vl-kpi__body">
      <div class="vl-kpi__value">{{ $totalActivos }}</div>
      <div class="vl-kpi__label">Activos</div>
    </div>
  </div>

  <div class="vl-kpi vl-animate vl-animate--delay-2">
    <div class="vl-stat__icon vl-stat__icon--violet"><i class="bi bi-journal-check"></i></div>
    <div class="vl-kpi__body">
      <div class="vl-kpi__value">{{ $totalEntregas }}</div>
      <div class="vl-kpi__label">Total Entregas</div>
    </div>
  </div>

  <div class="vl-kpi vl-animate vl-animate--delay-3">
    <div class="vl-stat__icon vl-stat__icon--amber"><i class="bi bi-calendar-day"></i></div>
    <div class="vl-kpi__body">
      <div class="vl-kpi__value">{{ $entregasHoy }}</div>
      <div class="vl-kpi__label">Hoy</div>
    </div>
  </div>

  <div class="vl-kpi vl-animate vl-animate--delay-1">
    <div class="vl-stat__icon vl-stat__icon--rose"><i class="bi bi-exclamation-triangle-fill"></i></div>
    <div class="vl-kpi__body">
      <div class="vl-kpi__value">{{ $stockCritico }}</div>
      <div class="vl-kpi__label">Stock Crítico</div>
    </div>
  </div>

  <div class="vl-kpi vl-animate vl-animate--delay-2">
    <div class="vl-stat__icon vl-stat__icon--green"><i class="bi bi-percent"></i></div>
    <div class="vl-kpi__body">
      <div class="vl-kpi__value">{{ $coberturaActual }}%</div>
      <div class="vl-kpi__label">Cobertura Mes</div>
    </div>
  </div>

</div>
{{-- ── BARRA DE SECCIONES ── --}}
<div class="vl-card mb-4 no-print vl-animate" style="padding:14px 20px;">
  <div style="display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
    <span style="font-size:.75rem; font-weight:700; color:var(--vl-text-muted);
                 text-transform:uppercase; letter-spacing:.06em; white-space:nowrap;">
      <i class="bi bi-layout-three-columns me-1"></i> Secciones:
    </span>
    <div class="vl-section-pills">
      <span class="vl-section-pill active" data-section="entregas">
        <i class="bi bi-graph-up-arrow"></i> Entregas
      </span>
      <span class="vl-section-pill active" data-section="beneficiarios">
        <i class="bi bi-people-fill"></i> Beneficiarios
      </span>
      <span class="vl-section-pill active" data-section="productos">
        <i class="bi bi-box-seam-fill"></i> Productos
      </span>
      <span class="vl-section-pill active" data-section="operadores">
        <i class="bi bi-person-gear"></i> Operadores
      </span>
    </div>
    <div style="margin-left:auto; display:flex; gap:6px;" class="no-print">
      <button id="btn-expand-all" class="vl-btn vl-btn--ghost vl-btn--sm"
              title="Expandir todas las secciones">
        <i class="bi bi-chevron-bar-expand me-1"></i>Expandir todo
      </button>
      <button id="btn-collapse-all" class="vl-btn vl-btn--ghost vl-btn--sm"
              title="Colapsar todas las secciones">
        <i class="bi bi-chevron-bar-contract me-1"></i>Colapsar todo
      </button>
    </div>
  </div>
</div>

{{-- ── SECCIONES MODULARES ── --}}
@include('dashboard.estadisticas._section_entregas')
@include('dashboard.estadisticas._section_beneficiarios')
@include('dashboard.estadisticas._section_productos')
@include('dashboard.estadisticas._section_operadores')

{{-- ── PANEL DE CONFIGURACIÓN ── --}}
@include('dashboard.estadisticas._config_panel')

@endsection

@push('scripts')
<script>
const SECTIONS = ['entregas','beneficiarios','productos','operadores'];
const STORAGE_KEY = 'vl_stat_v2';

// ── Persistencia ──
function loadState() {
  try { return JSON.parse(localStorage.getItem(STORAGE_KEY)) || {}; }
  catch { return {}; }
}
function saveState(state) {
  localStorage.setItem(STORAGE_KEY, JSON.stringify(state));
}

// ── Aplicar estado inicial ──
const state = loadState();
SECTIONS.forEach(id => {
  const section  = document.getElementById('stat-section-' + id);
  const pill     = document.querySelector('[data-section="' + id + '"]');
  const colBtn   = document.querySelector('[data-collapse-section="' + id + '"]');

  // Visibilidad (pill activo/inactivo)
  const visible = state['visible_' + id] !== false;
  if (!visible) {
    section && (section.dataset.hidden = 'true');
    pill && pill.classList.remove('active');
  }

  // Colapso
  const collapsed = state['collapsed_' + id] === true;
  if (collapsed) {
    section && (section.dataset.collapsed = 'true');
    colBtn && (colBtn.querySelector('i').className = 'bi bi-chevron-down');
  }
});

// ── Pills: mostrar/ocultar sección ──
document.querySelectorAll('.vl-section-pill').forEach(pill => {
  pill.addEventListener('click', () => {
    const id = pill.dataset.section;
    const section = document.getElementById('stat-section-' + id);
    const isActive = pill.classList.contains('active');
    pill.classList.toggle('active', !isActive);
    if (section) section.dataset.hidden = isActive ? 'true' : 'false';
    const s = loadState();
    s['visible_' + id] = !isActive;
    saveState(s);
  });
});

// ── Botón colapsar sección ──
document.querySelectorAll('.btn-collapse-section').forEach(btn => {
  btn.addEventListener('click', () => {
    const id = btn.dataset.collapseSection;
    const section = document.getElementById('stat-section-' + id);
    if (!section) return;
    const isCollapsed = section.dataset.collapsed === 'true';
    section.dataset.collapsed = isCollapsed ? 'false' : 'true';
    btn.querySelector('i').className = isCollapsed ? 'bi bi-chevron-up' : 'bi bi-chevron-down';
    const s = loadState();
    s['collapsed_' + id] = !isCollapsed;
    saveState(s);
  });
});

// ── Expandir / Colapsar todo ──
document.getElementById('btn-expand-all')?.addEventListener('click', () => {
  SECTIONS.forEach(id => {
    const section = document.getElementById('stat-section-' + id);
    const btn = document.querySelector('[data-collapse-section="' + id + '"]');
    if (section) section.dataset.collapsed = 'false';
    if (btn) btn.querySelector('i').className = 'bi bi-chevron-up';
  });
  const s = loadState();
  SECTIONS.forEach(id => s['collapsed_' + id] = false);
  saveState(s);
});

document.getElementById('btn-collapse-all')?.addEventListener('click', () => {
  SECTIONS.forEach(id => {
    const section = document.getElementById('stat-section-' + id);
    const btn = document.querySelector('[data-collapse-section="' + id + '"]');
    if (section) section.dataset.collapsed = 'true';
    if (btn) btn.querySelector('i').className = 'bi bi-chevron-down';
  });
  const s = loadState();
  SECTIONS.forEach(id => s['collapsed_' + id] = true);
  saveState(s);
});

// ── Imprimir sección ──
document.querySelectorAll('[data-print-section]').forEach(btn => {
  btn.addEventListener('click', () => {
    const id = btn.dataset.printSection;
    const section = document.getElementById('stat-section-' + id);
    if (!section) return;
    const w = window.open('', '_blank');
    w.document.write(`
      <html><head><title>Estadísticas — ${id}</title>
      <link rel="stylesheet" href="/css/variables.css">
      <link rel="stylesheet" href="/css/admin.css">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
      <style>body{padding:24px;background:#fff;} .no-print{display:none;}</style>
      </head><body>${section.innerHTML}</body></html>
    `);
    w.document.close();
    w.print();
  });
});


</script>
@endpush