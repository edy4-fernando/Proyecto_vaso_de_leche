@extends(request()->routeIs('admin.*') || request()->is('admin*') ? 'layouts.admin' : 'layouts.dashboard')
@section('title', 'Tema — Vaso de Leche')
@section('breadcrumb', 'Tema del Dashboard')
@php $activeModule = 'tema'; @endphp

@section('content')

<div class="vl-section-header">
  <div>
    <h2 class="vl-section-title">
      <i class="bi bi-palette-fill text-primary"></i>
      Tema del Dashboard
    </h2>
    <p class="vl-section-sub">
      Personalizá los colores del sidebar según tu preferencia
    </p>
  </div>
  <span class="vl-badge vl-badge--blue" id="temaActualBadge">
    Cargando…
  </span>
</div>

<div class="row g-4">

  {{-- Temas predefinidos --}}
  <div class="col-lg-8 vl-animate">
    <div class="vl-card">
      <div class="vl-card__accent"></div>
      <div class="vl-card__header">
        <h5 class="vl-card__title">
          <i class="bi bi-grid-3x2-gap-fill text-primary"></i>
          Temas Predefinidos
        </h5>
      </div>
      <div class="vl-card__body">

        @php
          $temas = [
            ['oceano',    '🌊 Océano',    '#0f172a', '#3b82f6', '#6366f1', 'Azul — Índigo',    'Clásico y profesional'],
            ['atardecer', '🔥 Atardecer', '#1a0a0a', '#ef4444', '#f97316', 'Rojo — Naranja',   'Cálido y enérgico'],
            ['bosque',    '🌿 Bosque',    '#0a1a14', '#10b981', '#0d9488', 'Verde — Teal',     'Natural y sereno'],
            ['cosmico',   '🌌 Cósmico',   '#0e0f1a', '#6366f1', '#a855f7', 'Índigo — Violeta', 'Oscuro y técnico'],
            ['artico',    '❄️ Ártico',    '#0a1520', '#06b6d4', '#38bdf8', 'Cian — Celeste',   'Frío y limpio'],
            ['fusion',    '🌺 Fusión',    '#180a1a', '#ec4899', '#a855f7', 'Rosa — Violeta',   'Vibrante y creativo'],
          ];
        @endphp

        <div class="row g-3">
          @foreach($temas as [$key, $nombre, $bg, $accent, $accent2, $desc, $mood])
            <div class="col-6 col-md-4">
              <div class="theme-preview"
                   data-preview-theme="{{ $key }}"
                   data-set-theme="{{ $key }}"
                   data-name="{{ $nombre }}"
                   style="cursor:pointer;">
                <div class="theme-preview__sidebar"
                     style="background:{{ $bg }}; position:relative;
                            overflow:hidden; height:90px;">
                  {{-- Barra de acento lateral --}}
                  <div style="position:absolute; top:0; right:0; width:4px; height:100%;
                              background:linear-gradient(180deg,{{ $accent }},{{ $accent2 }});"></div>
                  {{-- Simulación de nav items --}}
                  <div style="padding:10px 8px; display:flex; flex-direction:column; gap:5px;">
                    <div style="height:6px; width:70%; border-radius:99px;
                                background:{{ $accent }}; opacity:.9;"></div>
                    <div style="height:5px; width:55%; border-radius:99px;
                                background:{{ $accent }}; opacity:.5;"></div>
                    <div style="height:5px; width:65%; border-radius:99px;
                                background:{{ $accent }}; opacity:.35;"></div>
                    <div style="height:5px; width:45%; border-radius:99px;
                                background:{{ $accent }}; opacity:.25;"></div>
                  </div>
                  {{-- Footer del sidebar simulado --}}
                  <div style="position:absolute; bottom:0; left:0; right:0;
                              padding:6px 8px; border-top:1px solid rgba(255,255,255,.06);
                              display:flex; gap:5px; align-items:center;">
                    <div style="width:16px; height:16px; border-radius:50%;
                                background:linear-gradient(135deg,{{ $accent }},{{ $accent2 }});
                                flex-shrink:0;"></div>
                    <div style="flex:1;">
                      <div style="height:4px; width:60%; border-radius:99px;
                                  background:rgba(255,255,255,.3);"></div>
                    </div>
                  </div>
                </div>
                <div class="theme-preview__content">
                  <div class="theme-preview__dot" style="background:{{ $accent }};"></div>
                  <div class="theme-preview__dot" style="background:{{ $accent2 }};"></div>
                  <div class="theme-preview__line"></div>
                </div>
              </div>
              <div style="text-align:center; margin-top:10px;">
                <div style="font-size:.82rem; font-weight:700;
                            color:var(--vl-text-main);">
                  {{ $nombre }}
                </div>
                <div style="font-size:.68rem; color:var(--vl-text-muted);">
                  {{ $desc }}
                </div>
                <div style="font-size:.62rem; color:var(--vl-text-muted);
                            opacity:.6; margin-top:2px;">
                  {{ $mood }}
                </div>
              </div>
            </div>
          @endforeach
        </div>

      </div>
    </div>
  </div>

  {{-- Personalización --}}
  <div class="col-lg-4 vl-animate vl-animate--delay-1">
    <div class="vl-card mb-4">
      <div class="vl-card__accent"></div>
      <div class="vl-card__header">
        <h5 class="vl-card__title">
          <i class="bi bi-sliders text-primary"></i>
          Personalizar
        </h5>
      </div>
      <div class="vl-card__body">

        <div class="vl-form-group">
          <label class="vl-label">Fondo del sidebar</label>
          <div style="display:flex; align-items:center; gap:10px;">
            <input type="color" id="pickDbBg" value="#0f172a"
                   style="width:44px; height:36px;
                          border:1px solid var(--vl-border);
                          border-radius:var(--vl-radius-sm);
                          cursor:pointer; padding:2px;">
            <span id="pickDbBgVal"
                  style="font-size:.75rem; font-family:var(--vl-font-mono);
                         color:var(--vl-text-muted);">#0f172a</span>
          </div>
        </div>

        <div class="vl-form-group">
          <label class="vl-label">Acento principal</label>
          <div style="display:flex; align-items:center; gap:10px;">
            <input type="color" id="pickDbAccent" value="#3b82f6"
                   style="width:44px; height:36px;
                          border:1px solid var(--vl-border);
                          border-radius:var(--vl-radius-sm);
                          cursor:pointer; padding:2px;">
            <span id="pickDbAccentVal"
                  style="font-size:.75rem; font-family:var(--vl-font-mono);
                         color:var(--vl-text-muted);">#3b82f6</span>
          </div>
        </div>

        <div class="vl-form-group" style="margin-bottom:0;">
          <label class="vl-label">Acento secundario</label>
          <div style="display:flex; align-items:center; gap:10px;">
            <input type="color" id="pickDbAccent2" value="#6366f1"
                   style="width:44px; height:36px;
                          border:1px solid var(--vl-border);
                          border-radius:var(--vl-radius-sm);
                          cursor:pointer; padding:2px;">
            <span id="pickDbAccent2Val"
                  style="font-size:.75rem; font-family:var(--vl-font-mono);
                         color:var(--vl-text-muted);">#6366f1</span>
          </div>
        </div>

        {{-- Preview --}}
        <div style="margin-top:16px; padding:14px; background:var(--vl-bg-body);
                    border-radius:var(--vl-radius);">
          <div style="font-size:.68rem; color:var(--vl-text-muted); margin-bottom:8px;">
            Preview del gradiente
          </div>
          <div id="livePreviewBar"
               style="height:10px; border-radius:99px;
                      background:linear-gradient(90deg,#3b82f6,#6366f1);
                      transition:background .3s ease;">
          </div>
          {{-- Mini sidebar preview --}}
          <div id="liveSidebarPreview"
               style="margin-top:10px; border-radius:8px; overflow:hidden;
                      height:50px; background:#0f172a; position:relative;
                      transition:background .3s ease;">
            <div style="position:absolute; top:0; right:0; width:3px; height:100%;"
                 id="liveAccentBar"
                 style="background:linear-gradient(180deg,#3b82f6,#6366f1);"></div>
            <div style="padding:8px; display:flex; flex-direction:column; gap:4px;">
              <div id="liveBar1"
                   style="height:5px; width:65%; border-radius:99px;
                          background:#3b82f6; transition:background .3s;"></div>
              <div id="liveBar2"
                   style="height:4px; width:45%; border-radius:99px;
                          background:#3b82f6; opacity:.5; transition:background .3s;"></div>
            </div>
          </div>
        </div>

        <div style="display:flex; gap:8px; margin-top:16px; flex-wrap:wrap;">
          <button type="button" class="vl-btn vl-btn--primary"
                  id="btnApplyCustom" style="flex:1;">
            <i class="bi bi-check-lg"></i>
            Aplicar
          </button>
          <button type="button" class="vl-btn vl-btn--outline"
                  onclick="vlDbSetTheme('oceano')"
                  title="Restablecer a Océano">
            <i class="bi bi-arrow-counterclockwise"></i>
          </button>
        </div>

      </div>
    </div>

    {{-- Tema activo --}}
    <div class="vl-card vl-animate vl-animate--delay-2">
      <div class="vl-card__accent"></div>
      <div class="vl-card__header">
        <h5 class="vl-card__title">
          <i class="bi bi-check-circle-fill text-success"></i>
          Tema Activo
        </h5>
      </div>
      <div class="vl-card__body" style="text-align:center; padding:24px;">
        <div id="temaActivoIcon"
             style="font-size:2.5rem; display:block; margin-bottom:8px;">
          🌊
        </div>
        <div id="temaActivoNombre"
             style="font-size:1rem; font-weight:800; color:var(--vl-text-main);">
          Océano
        </div>
        <div id="temaActivoDesc"
             style="font-size:.72rem; color:var(--vl-text-muted); margin-top:4px;">
          Azul — Índigo
        </div>
        <div id="temaActivoGradient"
             style="height:6px; border-radius:99px; margin:12px auto 0;
                    width:80%; background:linear-gradient(90deg,#3b82f6,#6366f1);
                    transition:background .4s ease;">
        </div>
      </div>
    </div>

  </div>

</div>

@endsection

@push('scripts')
<script>
const TEMAS_INFO = {
  oceano:    { emoji:'🌊', nombre:'Océano',    desc:'Azul — Índigo',    g:'#3b82f6,#6366f1' },
  atardecer: { emoji:'🔥', nombre:'Atardecer', desc:'Rojo — Naranja',   g:'#ef4444,#f97316' },
  bosque:    { emoji:'🌿', nombre:'Bosque',    desc:'Verde — Teal',     g:'#10b981,#0d9488' },
  cosmico:   { emoji:'🌌', nombre:'Cósmico',   desc:'Índigo — Violeta', g:'#6366f1,#a855f7' },
  artico:    { emoji:'❄️', nombre:'Ártico',    desc:'Cian — Celeste',   g:'#06b6d4,#38bdf8' },
  fusion:    { emoji:'🌺', nombre:'Fusión',    desc:'Rosa — Violeta',   g:'#ec4899,#a855f7' },
  custom:    { emoji:'🎨', nombre:'Personalizado', desc:'Colores propios', g:'#3b82f6,#6366f1' },
};

function actualizarTemaActivo(key, customGradient) {
  const info   = TEMAS_INFO[key] || TEMAS_INFO.oceano;
  const badge  = document.getElementById('temaActualBadge');
  const icon   = document.getElementById('temaActivoIcon');
  const nombre = document.getElementById('temaActivoNombre');
  const desc   = document.getElementById('temaActivoDesc');
  const grad   = document.getElementById('temaActivoGradient');

  if (badge)  badge.textContent  = info.emoji + ' ' + info.nombre;
  if (icon)   icon.textContent   = info.emoji;
  if (nombre) nombre.textContent = info.nombre;
  if (desc)   desc.textContent   = info.desc;
  if (grad)   grad.style.background =
    'linear-gradient(90deg,' + (customGradient || info.g) + ')';
}

// ── Pickers ──
['pickDbBg','pickDbAccent','pickDbAccent2'].forEach(id => {
  const el  = document.getElementById(id);
  const val = document.getElementById(id + 'Val');
  if (!el) return;
  el.addEventListener('input', () => {
    if (val) val.textContent = el.value;
    const a  = document.getElementById('pickDbAccent')?.value  || '#3b82f6';
    const a2 = document.getElementById('pickDbAccent2')?.value || '#6366f1';
    const bg = document.getElementById('pickDbBg')?.value      || '#0f172a';

    const bar     = document.getElementById('livePreviewBar');
    const preview = document.getElementById('liveSidebarPreview');
    const aBar    = document.getElementById('liveAccentBar');
    const b1      = document.getElementById('liveBar1');
    const b2      = document.getElementById('liveBar2');

    if (bar)     bar.style.background     = 'linear-gradient(90deg,' + a + ',' + a2 + ')';
    if (preview) preview.style.background = bg;
    if (aBar)    aBar.style.background    = 'linear-gradient(180deg,' + a + ',' + a2 + ')';
    if (b1)      b1.style.background      = a;
    if (b2)      b2.style.background      = a;
  });
});

// ── Init ──
document.addEventListener('DOMContentLoaded', () => {
  const saved   = JSON.parse(localStorage.getItem('vl_db_custom') || 'null');
  const current = localStorage.getItem('vl_db_theme') || 'oceano';

  if (saved) {
    [['pickDbBg','bg'],['pickDbAccent','accent'],['pickDbAccent2','accent2']].forEach(([id,key]) => {
      const el  = document.getElementById(id);
      const val = document.getElementById(id + 'Val');
      if (el && saved[key]) {
        el.value = saved[key];
        if (val) val.textContent = saved[key];
      }
    });
  }

  document.querySelectorAll('[data-preview-theme]').forEach(card => {
    card.classList.toggle('active', card.dataset.previewTheme === current);
  });

  actualizarTemaActivo(current,
    saved ? saved.accent + ',' + saved.accent2 : null);
});

// ── Click en tema ──
document.querySelectorAll('[data-set-theme]').forEach(card => {
  card.addEventListener('click', () => {
    const key = card.dataset.setTheme;
    document.querySelectorAll('[data-preview-theme]').forEach(c => {
      c.classList.toggle('active', c.dataset.previewTheme === key);
    });
    actualizarTemaActivo(key);
    if (window.vlDbSetTheme) window.vlDbSetTheme(key);
  });
});
</script>
@endpush