{{-- ============================================================
     web/_components/paneles-estadisticos.blade.php
     Tres cuadros de métricas institucionales
     ============================================================ --}}

@php
  $totalBenef   = \App\Models\Beneficiario::where('estado', true)->count();
  $totalPoblacion = \App\Models\Beneficiario::count();
  $entregasMes  = \App\Models\Entrega::whereMonth('fecha_entrega', now()->month)
                    ->whereYear('fecha_entrega', now()->year)
                    ->count();
@endphp

<div class="row g-3 mb-4">

  {{-- Beneficiarios activos --}}
  <div class="col-4">
    <div class="stat-box">
      <span class="stat-value text-info">{{ $totalBenef }}</span>
      <span class="stat-label">Beneficiarios</span>
    </div>
  </div>

  {{-- Población total registrada --}}
  <div class="col-4">
    <div class="stat-box accent-fuchsia">
      <span class="stat-value" style="color: var(--wp-fuchsia-light);">
        {{ $totalPoblacion }}
      </span>
      <span class="stat-label">Población Vuln.</span>
    </div>
  </div>

  {{-- Entregas del mes --}}
  <div class="col-4">
    <div class="stat-box">
      <span class="stat-value text-warning">{{ $entregasMes }}</span>
      <span class="stat-label">Entregas Mes</span>
    </div>
  </div>

</div>