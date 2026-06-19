{{-- ============================================================
     admin/mi-actividad.blade.php
     Ruta: GET /admin/mi-actividad → AdminController@miActividad
     Variables: $actividad (paginación)
     Disponible para todos los roles — solo lectura, solo acciones propias
     ============================================================ --}}

@extends('layouts.admin')

@section('title', 'Mi Actividad — Vaso de Leche')
@section('breadcrumb', 'Mi Actividad')
@php $activeModule = 'mi-actividad'; @endphp

@section('content')

<div class="vl-section-header">
  <div>
    <h2 class="vl-section-title">
      <i class="bi bi-clock-history text-primary"></i>
      Mi Actividad
    </h2>
    <p class="vl-section-sub">
      Historial de tus propias acciones registradas en el sistema (solo lectura)
    </p>
  </div>
</div>

<div class="vl-card vl-animate">
  <div class="vl-card__accent"></div>
  <div class="vl-card__header">
    <h5 class="vl-card__title">
      <i class="bi bi-list-check text-primary"></i>
      Registro de acciones
    </h5>
    <span class="vl-badge vl-badge--blue">
      {{ $actividad->total() }} registro(s)
    </span>
  </div>

  <div class="vl-card__body" style="padding: 12px 0 0;">
    @if($actividad->count() === 0)
      <div class="vl-table__empty">
        <i class="bi bi-inbox"></i>
        <p>Todavía no tienes acciones registradas.</p>
      </div>
    @else
      <div class="vl-table-wrap">
        <table class="vl-table">
          <thead>
            <tr>
              <th>Acción</th>
              <th>Descripción</th>
              <th>Fecha</th>
            </tr>
          </thead>
          <tbody>
            @foreach($actividad as $log)
              <tr>
                <td>
                  <span class="vl-badge vl-badge--slate">{{ $log->accion }}</span>
                </td>
                <td>
                  <span style="font-size:.82rem; color:var(--vl-text-sub);">
                    {{ $log->descripcion }}
                  </span>
                </td>
                <td>
                  <span style="font-size:.78rem; color:var(--vl-text-muted); white-space:nowrap;">
                    {{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i') }}
                  </span>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div style="padding: 16px 20px;">
        {{ $actividad->links() }}
      </div>
    @endif
  </div>
</div>

@endsection