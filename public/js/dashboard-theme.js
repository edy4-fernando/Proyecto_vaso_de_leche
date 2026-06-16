/* ============================================================
   dashboard-theme.js — Sistema de temas del Dashboard
   Vaso de Leche Cusco
   ============================================================ */

(() => {
  'use strict';

  const THEME_KEY   = 'vl_db_theme';
  const CUSTOM_KEY  = 'vl_db_custom';
  const SIDEBAR_KEY = 'vl_db_sidebar_collapsed';

  /* ============================================================
     TEMAS PREDEFINIDOS
     ============================================================ */
  const THEMES = {
    oceano: {
      name:    'Océano',
      bg:      '#0f172a',
      accent:  '#3b82f6',
      accent2: '#7c3aed',
      muted:   '#64748b',
      text:    '#cbd5e1',
    },
    bosque: {
      name:    'Bosque',
      bg:      '#0a1a14',
      accent:  '#0d9488',
      accent2: '#065f46',
      muted:   '#5eead4',
      text:    '#ccfbf1',
    },
    cosmico: {
      name:    'Cósmico',
      bg:      '#0e0f1a',
      accent:  '#6366f1',
      accent2: '#a855f7',
      muted:   '#a5b4fc',
      text:    '#e0e7ff',
    },
  };

  /* ============================================================
     APLICAR TEMA
     ============================================================ */
  function applyTheme(themeKey, customData) {
    // Aplicar en <html> para que CSS lo lea inmediatamente
    document.documentElement.setAttribute('data-db-theme', themeKey || 'oceano');

    if (themeKey === 'custom' && customData) {
      const root = document.documentElement;
      root.style.setProperty('--db-custom-bg',      customData.bg      || '#0f172a');
      root.style.setProperty('--db-custom-accent',  customData.accent  || '#3b82f6');
      root.style.setProperty('--db-custom-accent2', customData.accent2 || '#7c3aed');
    } else {
      document.documentElement.style.removeProperty('--db-custom-bg');
      document.documentElement.style.removeProperty('--db-custom-accent');
      document.documentElement.style.removeProperty('--db-custom-accent2');
    }
  }

  /* ============================================================
     INIT — cargar tema guardado
     ============================================================ */
  function init() {
    const saved      = localStorage.getItem(THEME_KEY)  || 'oceano';
    const customData = JSON.parse(localStorage.getItem(CUSTOM_KEY) || 'null');

    applyTheme(saved, customData);
    syncUI(saved);
    initSidebar();
    initThemeButtons();
    initColorPickers();
    initPreviewCards();
  }

  /* ============================================================
     SIDEBAR — colapso desktop + móvil
     ============================================================ */
  function initSidebar() {
    const sidebar   = document.getElementById('vlDbSidebar');
    const body      = document.getElementById('vlBody');
    const toggle    = document.getElementById('btnDbSidebarToggle');
    const hamburger = document.getElementById('btnHamburger');
    const overlay   = document.getElementById('vlOverlay');

    if (!sidebar || !body) return;

    // Restaurar estado
    if (localStorage.getItem(SIDEBAR_KEY) === '1' && window.innerWidth >= 992) {
      sidebar.classList.add('collapsed');
      body.classList.add('sidebar-collapsed');
    }

    toggle?.addEventListener('click', () => {
      const collapsed = sidebar.classList.toggle('collapsed');
      body.classList.toggle('sidebar-collapsed', collapsed);
      localStorage.setItem(SIDEBAR_KEY, collapsed ? '1' : '0');
    });

    hamburger?.addEventListener('click', () => {
      sidebar.classList.toggle('mobile-open');
      overlay?.classList.toggle('active');
    });

    overlay?.addEventListener('click', () => {
      sidebar.classList.remove('mobile-open');
      overlay.classList.remove('active');
    });

    window.addEventListener('resize', () => {
      if (window.innerWidth >= 992) {
        sidebar.classList.remove('mobile-open');
        overlay?.classList.remove('active');
      }
    });
  }

  /* ============================================================
     BOTONES DE TEMA PREDEFINIDO
     ============================================================ */
  function initThemeButtons() {
    document.querySelectorAll('[data-set-theme]').forEach(btn => {
      btn.addEventListener('click', () => {
        const key = btn.dataset.setTheme;
        localStorage.setItem(THEME_KEY, key);
        localStorage.removeItem(CUSTOM_KEY);
        applyTheme(key, null);
        syncUI(key);
        updatePreviewCards(key);
        showToastTheme(THEMES[key]?.name || key);
      });
    });
  }

  /* ============================================================
     COLOR PICKERS — tema personalizado
     ============================================================ */
  function initColorPickers() {
    const pickBg      = document.getElementById('pickDbBg');
    const pickAccent  = document.getElementById('pickDbAccent');
    const pickAccent2 = document.getElementById('pickDbAccent2');
    const btnApply    = document.getElementById('btnApplyCustom');

    if (!pickBg || !pickAccent || !btnApply) return;

    // Cargar valores guardados
    const saved = JSON.parse(localStorage.getItem(CUSTOM_KEY) || 'null');
    if (saved) {
      pickBg.value      = saved.bg      || '#0f172a';
      pickAccent.value  = saved.accent  || '#3b82f6';
      if (pickAccent2) pickAccent2.value = saved.accent2 || '#7c3aed';
    }

    // Preview en tiempo real
    [pickBg, pickAccent, pickAccent2].forEach(p => {
      p?.addEventListener('input', () => livePreview());
    });

    btnApply?.addEventListener('click', () => {
      const customData = {
        bg:      pickBg.value,
        accent:  pickAccent.value,
        accent2: pickAccent2?.value || pickAccent.value,
      };
      localStorage.setItem(THEME_KEY,  'custom');
      localStorage.setItem(CUSTOM_KEY, JSON.stringify(customData));
      applyTheme('custom', customData);
      syncUI('custom');
      showToastTheme('Personalizado');
    });
  }

  function livePreview() {
    const pickBg      = document.getElementById('pickDbBg');
    const pickAccent  = document.getElementById('pickDbAccent');
    const pickAccent2 = document.getElementById('pickDbAccent2');

    const sidebar = document.getElementById('vlDbSidebar');
    if (!sidebar) return;

    sidebar.setAttribute('data-db-theme', 'custom');
    document.documentElement.style.setProperty('--db-custom-bg',      pickBg?.value      || '#0f172a');
    document.documentElement.style.setProperty('--db-custom-accent',  pickAccent?.value  || '#3b82f6');
    document.documentElement.style.setProperty('--db-custom-accent2', pickAccent2?.value || '#7c3aed');
  }

  /* ============================================================
     CARDS DE PREVIEW
     ============================================================ */
  function initPreviewCards() {
    const saved = localStorage.getItem(THEME_KEY) || 'oceano';
    updatePreviewCards(saved);
  }

  function updatePreviewCards(activeKey) {
    document.querySelectorAll('[data-preview-theme]').forEach(card => {
      const key    = card.dataset.previewTheme;
      const theme  = THEMES[key];
      if (!theme) return;

      card.classList.toggle('active', key === activeKey);

      const sidebarEl = card.querySelector('.theme-preview__sidebar');
      const bars      = card.querySelectorAll('.theme-preview__bar');
      const dot       = card.querySelector('.theme-preview__dot');

      if (sidebarEl) sidebarEl.style.background = theme.bg;
      bars.forEach(b => b.style.background = theme.accent);
      if (dot) dot.style.background = theme.accent;
    });
  }

  /* ============================================================
     SINCRONIZAR UI — resaltar botón activo
     ============================================================ */
  function syncUI(activeKey) {
    document.querySelectorAll('[data-set-theme]').forEach(btn => {
      const isActive = btn.dataset.setTheme === activeKey;
      btn.style.outline     = isActive ? `2px solid var(--db-sidebar-accent)` : 'none';
      btn.style.outlineOffset = isActive ? '2px' : '0';
    });
  }

  /* ============================================================
     TOAST de confirmación de tema
     ============================================================ */
  function showToastTheme(name) {
    if (window.vlShowToast) {
      window.vlShowToast(`Tema "${name}" aplicado correctamente`, 'success');
    }
  }

  /* ============================================================
     EXPONER función global para llamar desde configuración
     ============================================================ */
  window.vlDbSetTheme = function(key) {
    localStorage.setItem(THEME_KEY, key);
    applyTheme(key, null);
    syncUI(key);
    updatePreviewCards(key);
    showToastTheme(THEMES[key]?.name || key);
  };

  /* ── INIT ── */
  document.addEventListener('DOMContentLoaded', init);

})();