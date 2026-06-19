{{-- ============================================================
     web/_components/tarjeta-asistencia.blade.php
     Formulario de marcado de asistencia por DNI
     ============================================================ --}}

<div class="card-portal p-4">

  {{-- Línea de acento superior --}}
  <div class="header-accent"></div>

  {{-- Encabezado --}}
  <h3 class="text-center fw-bold mb-1"
      style="color: var(--wp-text-main); letter-spacing: -0.02em;">
    CONTROL DE ASISTENCIA
  </h3>
  <p class="text-center mb-4"
     style="color: var(--wp-text-muted); font-size: 0.85rem;">
    Programa Municipal Vaso de Leche
  </p>

  {{-- Alerta: Registro exitoso --}}
  @if(session('success'))
    <div class="alert-portal-success mb-3">
      <i class="bi bi-check-circle-fill me-2"></i>
      {{ session('success') }}
    </div>
  @endif

  {{-- Alerta: DNI no encontrado / error --}}
  @if(session('error'))
    <div class="alert-portal-danger mb-3">
      <i class="bi bi-x-circle-fill me-2"></i>
      {{ session('error') }}
    </div>
    <div class="alert-portal-info mb-3">
      <i class="bi bi-info-circle me-2"></i>
      Si es un usuario nuevo, solicite su inscripción con el administrador de turno.
    </div>
  @endif

  {{-- Alerta: Ya recibió ración --}}
  @if(session('warning'))
    <div class="alert-portal-warning mb-3">
      <i class="bi bi-exclamation-triangle-fill me-2"></i>
      {{ session('warning') }}
    </div>
  @endif

  {{-- Alerta: Error de validación --}}
  @if($errors->any())
    <div class="alert-portal-warning mb-3">
      <i class="bi bi-exclamation-triangle-fill me-2"></i>
      El DNI debe contener exactamente 8 caracteres numéricos.
    </div>
  @endif

  {{-- Formulario de marcado --}}
  <form action="{{ route('asistencia.buscar') }}" method="POST" class="mt-2">
    @csrf

    <div class="mb-4">
      <label for="dni"
             class="d-block text-center fw-bold mb-3"
             style="font-size: 0.75rem; text-transform: uppercase;
                    letter-spacing: 0.06em; color: var(--wp-text-muted);">
        Ingrese DNI del Beneficiario
      </label>

      <input type="text"
             class="input-dni"
             id="dni"
             name="dni"
             maxlength="8"
             placeholder="00000000"
             inputmode="numeric"
             pattern="[0-9]{8}"
             autocomplete="off"
             value="{{ old('dni') }}"
             required
             autofocus>
    </div>

    <button type="submit" class="btn-portal-primary">
      <i class="bi bi-check-circle-fill me-2"></i>
      Registrar Asistencia
    </button>

  </form>

  {{-- Pie de tarjeta --}}
  <div class="mt-4 pt-3 text-center"
       style="border-top: 1px solid rgba(255,255,255,.08);">
    <p style="color: var(--wp-text-muted); font-size: 0.7rem; margin: 0;">
      &copy; {{ date('Y') }} Sistema de Gestión de Programas Sociales
    </p>
  </div>

</div>