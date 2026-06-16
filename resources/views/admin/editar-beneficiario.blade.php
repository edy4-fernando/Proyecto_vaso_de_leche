{{-- ============================================================
     admin/editar-beneficiario.blade.php
     Ruta: GET /admin/beneficiarios/{id}/editar
     Variables: $beneficiario
     ============================================================ --}}

@extends('layouts.admin')

@section('title', 'Editar — ' . $beneficiario->nombre . ' ' . $beneficiario->apellido)
@section('breadcrumb', 'Editar Beneficiario')
@php $activeModule = 'beneficiarios'; @endphp

@section('content')

{{-- ── Botón volver ── --}}
<div class="mb-3">
  <a href="{{ route('admin.beneficiarios') }}"
     class="vl-btn vl-btn--ghost vl-btn--sm">
    <i class="bi bi-arrow-left"></i>
    Volver al padrón
  </a>
</div>

{{-- ── Encabezado ── --}}
<div class="vl-section-header">
  <div>
    <h2 class="vl-section-title">
      <i class="bi bi-pencil-fill text-primary"></i>
      Editar Beneficiario
    </h2>
    <p class="vl-section-sub">
      Modificando registro de
      <strong>{{ strtoupper($beneficiario->apellido) }}, {{ $beneficiario->nombre }}</strong>
      — DNI: {{ $beneficiario->dni }}
    </p>
  </div>
  <a href="{{ route('admin.perfil', $beneficiario->id) }}"
     class="vl-btn vl-btn--outline vl-btn--sm">
    <i class="bi bi-eye-fill"></i>
    Ver perfil
  </a>
</div>

{{-- ── Formulario ── --}}
<div class="vl-card vl-animate">
  <div class="vl-card__accent"></div>
  <div class="vl-card__header">
    <h5 class="vl-card__title">
      <i class="bi bi-person-lines-fill text-primary"></i>
      Datos del Beneficiario
    </h5>
    {{-- Badge estado actual --}}
    @if($beneficiario->estado)
      <span class="vl-badge vl-badge--green">
        <i class="bi bi-check-circle-fill"></i> Activo
      </span>
    @else
      <span class="vl-badge vl-badge--rose">
        <i class="bi bi-x-circle-fill"></i> De baja
      </span>
    @endif
  </div>

  <div class="vl-card__body">
    <form action="{{ route('admin.actualizar', $beneficiario->id) }}"
          method="POST">
      @csrf
      @method('PUT')

      <div class="row g-3">

        {{-- DNI --}}
        <div class="col-md-4">
          <label class="vl-label" for="dni">
            DNI <span class="req">*</span>
          </label>
          <input type="text"
                 id="dni"
                 name="dni"
                 class="vl-input"
                 value="{{ old('dni', $beneficiario->dni) }}"
                 placeholder="12345678"
                 maxlength="8"
                 pattern="\d{8}"
                 required
                 data-uppercase>
        </div>

        {{-- Nombre --}}
        <div class="col-md-4">
          <label class="vl-label" for="nombre">
            Nombre(s) <span class="req">*</span>
          </label>
          <input type="text"
                 id="nombre"
                 name="nombre"
                 class="vl-input"
                 value="{{ old('nombre', $beneficiario->nombre) }}"
                 placeholder="Ej: Ana Lucía"
                 required
                 data-uppercase>
        </div>

        {{-- Apellido --}}
        <div class="col-md-4">
          <label class="vl-label" for="apellido">
            Apellidos <span class="req">*</span>
          </label>
          <input type="text"
                 id="apellido"
                 name="apellido"
                 class="vl-input"
                 value="{{ old('apellido', $beneficiario->apellido) }}"
                 placeholder="Ej: Quispe Flores"
                 required
                 data-uppercase>
        </div>

        {{-- Fecha nacimiento --}}
        <div class="col-md-4">
          <label class="vl-label" for="fecha_nacimiento">
            Fecha de nacimiento <span class="req">*</span>
          </label>
          <input type="date"
                 id="fecha_nacimiento"
                 name="fecha_nacimiento"
                 class="vl-input"
                 value="{{ old('fecha_nacimiento', $beneficiario->fecha_nacimiento?->format('Y-m-d')) }}"
                 required>
        </div>

        {{-- Tipo beneficiario --}}
        <div class="col-md-4">
          <label class="vl-label" for="tipo_beneficiario">
            Tipo de beneficiario <span class="req">*</span>
          </label>
          <select name="tipo_beneficiario" class="vl-select" required>
            <option value="">— Seleccionar —</option>

            <optgroup label="Primera Prioridad">
              <option value="niño 0-6"
                {{ old('tipo_beneficiario', $beneficiario->tipo_beneficiario) === 'niño 0-6' ? 'selected' : '' }}>
                Niño/a de 0 a 6 años
              </option>
              <option value="gestante"
                {{ old('tipo_beneficiario', $beneficiario->tipo_beneficiario) === 'gestante' ? 'selected' : '' }}>
                Madre gestante
              </option>
              <option value="lactante"
                {{ old('tipo_beneficiario', $beneficiario->tipo_beneficiario) === 'lactante' ? 'selected' : '' }}>
                Madre en lactancia (puérpera)
              </option>
            </optgroup>

            <optgroup label="Segunda Prioridad">
              <option value="niño 7-13"
                {{ old('tipo_beneficiario', $beneficiario->tipo_beneficiario) === 'niño 7-13' ? 'selected' : '' }}>
                Niño/a de 7 a 13 años
              </option>
              <option value="adulto mayor"
                {{ old('tipo_beneficiario', $beneficiario->tipo_beneficiario) === 'adulto mayor' ? 'selected' : '' }}>
                Adulto mayor
              </option>
              <option value="discapacitado"
                {{ old('tipo_beneficiario', $beneficiario->tipo_beneficiario) === 'discapacitado' ? 'selected' : '' }}>
                Persona con discapacidad (CONADIS)
              </option>
              <option value="tbc"
                {{ old('tipo_beneficiario', $beneficiario->tipo_beneficiario) === 'tbc' ? 'selected' : '' }}>
                Persona con Tuberculosis (TBC)
              </option>
            </optgroup>

          </select>
        </div>

        {{-- Sector / Comité --}}
        <div class="col-md-4">
          <label class="vl-label" for="sector_o_comite">
            Sector / Comité
          </label>
          <input type="text"
                 id="sector_o_comite"
                 name="sector_o_comite"
                 class="vl-input"
                 value="{{ old('sector_o_comite', $beneficiario->sector_o_comite) }}"
                 placeholder="Ej: Comité 12 - Ttio"
                 data-uppercase>
        </div>

        {{-- Dirección --}}
        <div class="col-12">
          <label class="vl-label" for="direccion">
            Dirección <span class="req">*</span>
          </label>
          <input type="text"
                 id="direccion"
                 name="direccion"
                 class="vl-input"
                 value="{{ old('direccion', $beneficiario->direccion) }}"
                 placeholder="Ej: Av. El Sol 456 - Cusco"
                 required
                 data-uppercase>
        </div>

        {{-- Teléfono --}}
        <div class="col-md-4">
          <label class="vl-label" for="telefono">
            Teléfono
          </label>
          <input type="text"
                 id="telefono"
                 name="telefono"
                 class="vl-input"
                 value="{{ old('telefono', $beneficiario->telefono) }}"
                 placeholder="984123456"
                 maxlength="15">
        </div>

        {{-- Nombre apoderado --}}
        <div class="col-md-4">
          <label class="vl-label" for="nombre_apoderado">
            Nombre del apoderado
          </label>
          <input type="text"
                 id="nombre_apoderado"
                 name="nombre_apoderado"
                 class="vl-input"
                 value="{{ old('nombre_apoderado', $beneficiario->nombre_apoderado) }}"
                 placeholder="Opcional"
                 data-uppercase>
        </div>

        {{-- DNI apoderado --}}
        <div class="col-md-4">
          <label class="vl-label" for="dni_apoderado">
            DNI del apoderado
          </label>
          <input type="text"
                 id="dni_apoderado"
                 name="dni_apoderado"
                 class="vl-input"
                 value="{{ old('dni_apoderado', $beneficiario->dni_apoderado) }}"
                 placeholder="Opcional"
                 maxlength="8">
        </div>

        {{-- Observaciones médicas --}}
        <div class="col-12">
          <label class="vl-label" for="observaciones_medicas">
            Observaciones médicas
          </label>
          <textarea id="observaciones_medicas"
                    name="observaciones_medicas"
                    class="vl-textarea"
                    placeholder="Alergias, condiciones especiales, etc."
                    rows="3"
                    maxlength="500"
                    data-charcount="charCountObs">{{ old('observaciones_medicas', $beneficiario->observaciones_medicas) }}</textarea>
          <div style="text-align: right; margin-top: 3px;">
            <span id="charCountObs"
                  style="font-size: 0.68rem; color: var(--vl-text-muted);">
              {{ strlen($beneficiario->observaciones_medicas ?? '') }} / 500
            </span>
          </div>
        </div>

      </div>

      {{-- ── Divider ── --}}
      <div class="vl-divider"></div>

      {{-- ── Botones ── --}}
      <div class="d-flex align-items-center gap-3 flex-wrap">
        <button type="submit" class="vl-btn vl-btn--primary">
          <i class="bi bi-check-lg"></i>
          Guardar cambios
        </button>
        <a href="{{ route('admin.beneficiarios') }}"
           class="vl-btn vl-btn--outline">
          <i class="bi bi-x-lg"></i>
          Cancelar
        </a>

        {{-- Toggle estado (solo maestro) --}}
        @if(auth()->user()->rol === 'maestro')
          <div class="ms-auto">
            <form action="{{ route('admin.beneficiarios.toggle', $beneficiario->id) }}"
                  method="POST" class="m-0">
              @csrf
              @method('PUT')
              <button type="submit"
                      class="vl-btn vl-btn--outline vl-btn--sm {{ $beneficiario->estado ? 'text-warning' : 'text-success' }}">
                <i class="bi bi-{{ $beneficiario->estado ? 'person-dash-fill' : 'person-check-fill' }}"></i>
                {{ $beneficiario->estado ? 'Dar de baja' : 'Reactivar' }}
              </button>
            </form>
          </div>
        @endif

      </div>

    </form>
  </div>
</div>

@endsection