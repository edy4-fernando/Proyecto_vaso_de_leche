{{-- ============================================================
     web/index.blade.php
     Pantalla principal de marcado de asistencia por DNI.
     Ruta: GET /asistencia  →  AsistenciaController@index
     Layout: layouts/web.blade.php
     ============================================================ --}}

@extends('layouts.web')

@section('title', 'Control de Asistencia — Vaso de Leche')

@section('content')

  <div class="container main-wrapper">
    <div class="row align-items-center g-5">

      {{-- ── COLUMNA IZQUIERDA: Formulario de marcado ── --}}
      <div class="col-lg-6">
        @include('publico._components.tarjeta-asistencia')
      </div>

      {{-- ── COLUMNA DERECHA: Hero institucional ── --}}
      <div class="col-lg-6">

        <h1 class="hero-title mb-3">
          Validación Diaria: <br>
          <span class="text-info">Control del Ciudadano</span>
        </h1>

        <p class="hero-subtitle mb-4">
          Plataforma digital centralizada para el marcaje de raciones del
          programa alimentario. El padrón oficial es gestionado de manera
          estricta por personal autorizado de la gerencia de desarrollo social.
        </p>

        {{-- Métricas --}}
        @include('publico._components.paneles-estadisticos')

        {{-- Badge de seguridad --}}
        <div class="hero-security-badge">
          <div class="fs-2 text-primary">
            <i class="bi bi-shield-lock-fill"></i>
          </div>
          <div>
            <h6>Acceso Restringido y Seguro</h6>
            <small>
              La inscripción de nuevos ciudadanos requiere
              validación presencial con el administrador.
            </small>
          </div>
        </div>

      </div>

    </div>
  </div>

@endsection