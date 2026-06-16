<?php

/**
 * Meta 5 — Método dashboardNuevo actualizado
 * Reemplaza el método existente en AdminController.php (o el controlador que uses)
 * Agrega soporte para filtros: periodo, fecha_desde, fecha_hasta, operador_id, zona
 */

public function dashboardNuevo(Request $request)
{
    // ── 1. Construir rango de fechas según filtro ─────────────
    $periodo     = $request->input('periodo', 'hoy');
    $fechaDesde  = $request->input('fecha_desde');
    $fechaHasta  = $request->input('fecha_hasta');
    $operadorId  = $request->input('operador_id');
    $zona        = $request->input('zona');

    // Rango calculado a partir del período rápido
    if ($fechaDesde || $fechaHasta) {
        // Rango personalizado tiene prioridad
        $desde = $fechaDesde ? Carbon::parse($fechaDesde)->startOfDay() : Carbon::now()->startOfYear();
        $hasta = $fechaHasta ? Carbon::parse($fechaHasta)->endOfDay()   : Carbon::now()->endOfDay();
    } else {
        switch ($periodo) {
            case 'semana':
                $desde = Carbon::now()->subDays(6)->startOfDay();
                $hasta = Carbon::now()->endOfDay();
                break;
            case 'mes':
                $desde = Carbon::now()->startOfMonth();
                $hasta = Carbon::now()->endOfDay();
                break;
            case 'anio':
                $desde = Carbon::now()->startOfYear();
                $hasta = Carbon::now()->endOfDay();
                break;
            default: // 'hoy'
                $desde = Carbon::now()->startOfDay();
                $hasta = Carbon::now()->endOfDay();
                break;
        }
    }

    // ── 2. Query base de entregas filtradas ───────────────────
    $queryFiltrada = Entrega::with(['beneficiario', 'user'])
        ->whereBetween('created_at', [$desde, $hasta]);

    if ($operadorId) {
        $queryFiltrada->where('user_id', $operadorId);
    }

    if ($zona) {
        $queryFiltrada->whereHas('beneficiario', function ($q) use ($zona) {
            $q->where('direccion', 'like', "%{$zona}%");
        });
    }

    $entregasFiltradas   = $queryFiltrada->latest()->get();
    $totalEntregasPeriodo = $entregasFiltradas->count();

    // ── 3. Entregas de hoy (siempre, independiente del filtro) ─
    $entregasHoy = Entrega::with(['beneficiario', 'user'])
        ->whereDate('created_at', today())
        ->latest()
        ->get();

    // ── 4. Tendencia vs período anterior ─────────────────────
    $duracion    = $desde->diffInDays($hasta) ?: 1;
    $desdeAnterior = $desde->copy()->subDays($duracion + 1)->startOfDay();
    $hastaAnterior = $desde->copy()->subDay()->endOfDay();

    $totalAnterior = Entrega::whereBetween('created_at', [$desdeAnterior, $hastaAnterior])->count();
    $tendencia = $totalAnterior > 0
        ? round((($totalEntregasPeriodo - $totalAnterior) / $totalAnterior) * 100)
        : 0;

    // ── 5. Promedio diario ────────────────────────────────────
    $diasPeriodo  = max($duracion, 1);
    $promedioDiario = round($totalEntregasPeriodo / $diasPeriodo, 1);

    // ── 6. Operadores para el filtro ─────────────────────────
    $operadores = User::orderBy('name')->get();

    // ── 7. Zonas únicas (de direcciones de beneficiarios) ────
    $zonas = Beneficiario::whereNotNull('direccion')
        ->where('direccion', '!=', '')
        ->pluck('direccion')
        ->map(fn($d) => explode(' ', trim($d))[0]) // primera palabra = zona
        ->unique()
        ->sort()
        ->values();

    return view('dashboard.index', compact(
        'entregasHoy',
        'entregasFiltradas',
        'totalEntregasPeriodo',
        'tendencia',
        'promedioDiario',
        'operadores',
        'zonas'
    ));
}