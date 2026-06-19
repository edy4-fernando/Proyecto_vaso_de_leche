{{-- ============================================================
     admin/_components/toast.blade.php
     Contenedor de notificaciones (toasts) — mensajes flash de Laravel
     Se conecta con admin.js (initToasts) y con window.vlShowToast()

     Claves de sesión esperadas:
       session('success') | session('error') | session('warning') | session('info')
     ============================================================ --}}

<div id="vlToastContainer"
     class="position-fixed"
     style="
       top: calc(var(--vl-navbar-h) + 16px);
       right: 20px;
       z-index: var(--vl-z-toast);
       display: flex;
       flex-direction: column;
       gap: 10px;
     ">

  @php
    $vlFlashTypes = [
      'success' => ['icon' => 'bi-check-circle-fill',          'color' => '#10b981'],
      'error'   => ['icon' => 'bi-x-circle-fill',               'color' => '#f43f5e'],
      'warning' => ['icon' => 'bi-exclamation-triangle-fill',   'color' => '#f59e0b'],
      'info'    => ['icon' => 'bi-info-circle-fill',            'color' => '#3b82f6'],
    ];
  @endphp

  @foreach($vlFlashTypes as $vlKey => $vlCfg)
    @if(session($vlKey))
      <div class="toast align-items-center border-0 show"
           role="alert" aria-live="assertive" aria-atomic="true"
           style="
             background:    var(--vl-bg-card);
             border:        1px solid var(--vl-border) !important;
             border-left:   4px solid {{ $vlCfg['color'] }} !important;
             border-radius: var(--vl-radius);
             box-shadow:    var(--vl-shadow-lg);
             min-width:     280px;
             max-width:     360px;
           ">
        <div class="d-flex align-items-center gap-2 p-3">
          <i class="bi {{ $vlCfg['icon'] }}"
             style="color:{{ $vlCfg['color'] }}; font-size:1.1rem; flex-shrink:0;"></i>
          <span style="font-size:.82rem; color:var(--vl-text-main); flex:1;">
            {{ session($vlKey) }}
          </span>
          <button type="button" class="btn-close ms-2" data-bs-dismiss="toast"
                  style="font-size:.65rem;" aria-label="Cerrar"></button>
        </div>
      </div>
    @endif
  @endforeach

</div>