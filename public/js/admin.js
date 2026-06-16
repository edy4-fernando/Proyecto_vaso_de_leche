/* ============================================================
   admin.js — Lógica del panel de administración
   Vaso de Leche Cusco
   ============================================================ */

(() => {
  'use strict';

  /* ============================================================
     1. DARK MODE
     ============================================================ */
  const DARK_KEY = 'vl_dark_mode';

  function applyDark(on) {
    document.documentElement.classList.toggle('dark', on);
    const btn = document.getElementById('btnDarkMode');
    if (!btn) return;
    btn.querySelector('i').className = on ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
    btn.title = on ? 'Modo claro' : 'Modo oscuro';
  }

  function initDarkMode() {
    const saved = localStorage.getItem(DARK_KEY);
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    applyDark(saved !== null ? saved === '1' : prefersDark);

    const btn = document.getElementById('btnDarkMode');
    if (!btn) return;
    btn.addEventListener('click', () => {
      const isDark = document.documentElement.classList.toggle('dark');
      localStorage.setItem(DARK_KEY, isDark ? '1' : '0');
      applyDark(isDark);
    });
  }

  /* ============================================================
     2. SIDEBAR — colapso desktop + abrir/cerrar móvil
     ============================================================ */
  const SIDEBAR_KEY = 'vl_sidebar_collapsed';

  function initSidebar() {
    const sidebar  = document.getElementById('vlSidebar');
    const body     = document.getElementById('vlBody');
    const toggle   = document.getElementById('btnSidebarToggle');
    const hamburger = document.getElementById('btnHamburger');
    const overlay  = document.getElementById('vlOverlay');

    if (!sidebar || !body) return;

    // Restaurar estado guardado en desktop
    const isCollapsed = localStorage.getItem(SIDEBAR_KEY) === '1';
    if (isCollapsed && window.innerWidth >= 992) {
      sidebar.classList.add('collapsed');
      body.classList.add('sidebar-collapsed');
    }

    // Toggle desktop (botón dentro del sidebar)
    if (toggle) {
      toggle.addEventListener('click', () => {
        const collapsed = sidebar.classList.toggle('collapsed');
        body.classList.toggle('sidebar-collapsed', collapsed);
        localStorage.setItem(SIDEBAR_KEY, collapsed ? '1' : '0');
      });
    }

    // Hamburguesa móvil
    if (hamburger) {
      hamburger.addEventListener('click', () => {
        sidebar.classList.toggle('mobile-open');
        overlay && overlay.classList.toggle('active');
      });
    }

    // Cerrar al hacer clic en overlay
    if (overlay) {
      overlay.addEventListener('click', () => {
        sidebar.classList.remove('mobile-open');
        overlay.classList.remove('active');
      });
    }

    // Cerrar sidebar en móvil al cambiar a desktop
    window.addEventListener('resize', () => {
      if (window.innerWidth >= 992) {
        sidebar.classList.remove('mobile-open');
        overlay && overlay.classList.remove('active');
      }
    });
  }

  /* ============================================================
     3. DROPDOWN DE USUARIO en navbar
     ============================================================ */
  function initUserDropdown() {
    const btn      = document.getElementById('btnUserMenu');
    const dropdown = document.getElementById('vlUserDropdown');
    if (!btn || !dropdown) return;

    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      const open = dropdown.classList.toggle('open');
      btn.setAttribute('aria-expanded', open);
    });

    // Cerrar al clic fuera
    document.addEventListener('click', () => {
      dropdown.classList.remove('open');
      btn.setAttribute('aria-expanded', 'false');
    });

    // Evitar cierre al clic dentro del dropdown
    dropdown.addEventListener('click', (e) => e.stopPropagation());
  }

  /* ============================================================
     4. TOASTS (notificaciones Laravel → JS)
     ============================================================ */
  function initToasts() {
    const container = document.getElementById('vlToastContainer');
    if (!container) return;

    // Bootstrap 5 auto-inicializa los toasts con data-bs-autohide
    const toasts = container.querySelectorAll('.toast');
    toasts.forEach(el => {
      const bsToast = new bootstrap.Toast(el, { delay: 4000 });
      bsToast.show();
    });
  }

  /* ============================================================
     5. BUSCADOR EN TABLAS (filtrado en cliente)
     ============================================================ */
  function initTableSearch() {
    const input = document.getElementById('vlTableSearch');
    if (!input) return;

    const tableId   = input.dataset.table   || 'vlMainTable';
    const colsAttr  = input.dataset.cols;
    const table     = document.getElementById(tableId);
    if (!table) return;

    const cols = colsAttr ? colsAttr.split(',').map(Number) : null;
    const emptyRow = document.getElementById('vlTableEmpty');

    input.addEventListener('input', () => {
      const q = input.value.trim().toLowerCase();
      let visible = 0;

      table.querySelectorAll('tbody tr:not(#vlTableEmpty)').forEach(row => {
        const cells = row.querySelectorAll('td');
        let text = '';
        if (cols) {
          cols.forEach(i => { if (cells[i]) text += cells[i].textContent + ' '; });
        } else {
          text = row.textContent;
        }
        const match = text.toLowerCase().includes(q);
        row.style.display = match ? '' : 'none';
        if (match) visible++;
      });

      if (emptyRow) emptyRow.style.display = visible === 0 ? '' : 'none';
    });
  }

  /* ============================================================
     6. FILTRO POR SELECT (estado activo/inactivo, etc.)
     ============================================================ */
  function initSelectFilter() {
    const sel = document.getElementById('vlSelectFilter');
    if (!sel) return;

    const tableId  = sel.dataset.table  || 'vlMainTable';
    const colIndex = parseInt(sel.dataset.col || '0', 10);
    const table    = document.getElementById(tableId);
    if (!table) return;

    sel.addEventListener('change', () => {
      const val = sel.value.toLowerCase();

      table.querySelectorAll('tbody tr:not(#vlTableEmpty)').forEach(row => {
        const cell = row.querySelectorAll('td')[colIndex];
        if (!cell) return;
        const match = val === '' || cell.textContent.toLowerCase().includes(val);
        row.style.display = match ? '' : 'none';
      });
    });
  }

  /* ============================================================
     7. CONFIRMAR ELIMINACIÓN
     ============================================================ */
  function initDeleteConfirm() {
    document.querySelectorAll('[data-confirm-delete]').forEach(form => {
      form.addEventListener('submit', (e) => {
        const msg = form.dataset.confirmDelete ||
          '¿Está seguro de eliminar este registro permanentemente?';
        if (!confirm(msg)) e.preventDefault();
      });
    });
  }

  /* ============================================================
     8. PREVIEW DE DATOS EN MODAL DE ENTREGA
        Al seleccionar un beneficiario por DNI → muestra su nombre
     ============================================================ */
  function initEntregaModal() {
    const dniInput   = document.getElementById('entregaDni');
    const nombreSpan = document.getElementById('entregaNombrePreview');
    if (!dniInput || !nombreSpan) return;

    dniInput.addEventListener('input', () => {
      const val = dniInput.value.trim();
      // Buscar en la tabla si existe
      const table = document.getElementById('vlMainTable');
      if (!table) return;

      let found = '';
      table.querySelectorAll('tbody tr').forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells[1] && cells[1].textContent.trim() === val) {
          found = cells[2] ? cells[2].textContent.trim() : '';
        }
      });

      nombreSpan.textContent = found || '';
      nombreSpan.style.display = found ? 'block' : 'none';
    });
  }

  /* ============================================================
     9. AUTO-UPPERCASE en campos de nombre / apellido
     ============================================================ */
  function initAutoUppercase() {
    document.querySelectorAll('[data-uppercase]').forEach(el => {
      el.addEventListener('input', () => {
        const pos = el.selectionStart;
        el.value = el.value.toUpperCase();
        el.setSelectionRange(pos, pos);
      });
    });
  }

  /* ============================================================
     10. CONTADOR DE CARACTERES en textarea
     ============================================================ */
  function initCharCount() {
    document.querySelectorAll('[data-charcount]').forEach(el => {
      const targetId = el.dataset.charcount;
      const target   = document.getElementById(targetId);
      if (!target) return;

      const max = parseInt(el.maxLength || el.dataset.max || 255, 10);

      el.addEventListener('input', () => {
        target.textContent = `${el.value.length} / ${max}`;
        target.style.color = el.value.length > max * .9
          ? 'var(--vl-rose)'
          : 'var(--vl-text-muted)';
      });
    });
  }

  /* ============================================================
     11. FLASH MESSAGES desde sessionStorage
         (útil para mensajes post-redirect)
     ============================================================ */
  function initFlashMessages() {
    const msg  = sessionStorage.getItem('vl_flash');
    const type = sessionStorage.getItem('vl_flash_type') || 'success';
    if (!msg) return;

    sessionStorage.removeItem('vl_flash');
    sessionStorage.removeItem('vl_flash_type');

    showToast(msg, type);
  }

  /* ============================================================
     12. FUNCIÓN PÚBLICA — mostrar toast programáticamente
     ============================================================ */
  window.vlShowToast = function(msg, type = 'success') {
    showToast(msg, type);
  };

  function showToast(msg, type) {
    const container = document.getElementById('vlToastContainer');
    if (!container) return;

    const icons = {
      success: 'bi-check-circle-fill',
      danger:  'bi-x-circle-fill',
      warning: 'bi-exclamation-triangle-fill',
      info:    'bi-info-circle-fill',
    };

    const colors = {
      success: '#10b981',
      danger:  '#f43f5e',
      warning: '#f59e0b',
      info:    '#3b82f6',
    };

    const icon  = icons[type]  || icons.info;
    const color = colors[type] || colors.info;

    const el = document.createElement('div');
    el.className = 'toast align-items-center border-0 show';
    el.setAttribute('role', 'alert');
    el.style.cssText = `
      background: var(--vl-bg-card);
      border: 1px solid var(--vl-border) !important;
      border-left: 4px solid ${color} !important;
      border-radius: var(--vl-radius);
      box-shadow: var(--vl-shadow-lg);
      min-width: 280px;
      max-width: 360px;
    `;
    el.innerHTML = `
      <div class="d-flex align-items-center gap-2 p-3">
        <i class="bi ${icon}" style="color:${color};font-size:1.1rem;flex-shrink:0;"></i>
        <span style="font-size:.82rem;color:var(--vl-text-main);flex:1;">${msg}</span>
        <button type="button" class="btn-close ms-2" data-bs-dismiss="toast"
                style="font-size:.65rem;" aria-label="Cerrar"></button>
      </div>
    `;

    container.appendChild(el);
    const bsToast = new bootstrap.Toast(el, { delay: 4500 });
    bsToast.show();
    el.addEventListener('hidden.bs.toast', () => el.remove());
  }

  /* ============================================================
     13. TOOLTIPS de Bootstrap
     ============================================================ */
  function initTooltips() {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
      new bootstrap.Tooltip(el, { trigger: 'hover' });
    });
  }

  /* ============================================================
     INIT — ejecutar cuando el DOM esté listo
     ============================================================ */
  document.addEventListener('DOMContentLoaded', () => {
    initDarkMode();
    initSidebar();
    initUserDropdown();
    initToasts();
    initTableSearch();
    initSelectFilter();
    initDeleteConfirm();
    initEntregaModal();
    initAutoUppercase();
    initCharCount();
    initFlashMessages();
    initTooltips();
  });

})();