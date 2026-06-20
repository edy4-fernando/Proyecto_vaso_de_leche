@extends('layouts.admin')

@section('title', 'Verificar código — Vaso de Leche')

@section('content')
<div class="vl-page-header">
  <div>
    <h1 class="vl-page-title">
      <i class="bi bi-shield-lock-fill text-primary"></i>
      Verificación de seguridad
    </h1>
    <p class="vl-page-sub">Ingresa el código enviado a tu correo</p>
  </div>
</div>

<div style="max-width:440px; margin:0 auto;">
  <div class="vl-card vl-animate">
    <div class="vl-card__accent"></div>
    <div class="vl-card__body">

      {{-- Alerta info --}}
      @if(session('info'))
        <div class="vl-alert vl-alert--info" style="margin-bottom:20px;">
          <i class="bi bi-envelope-fill"></i>
          {{ session('info') }}
        </div>
      @endif

      {{-- Error --}}
      @if(session('error'))
        <div class="vl-alert vl-alert--danger" style="margin-bottom:20px;">
          <i class="bi bi-x-circle-fill"></i>
          {{ session('error') }}
        </div>
      @endif

      <p style="font-size:.88rem; color:var(--vl-text-sub); margin-bottom:24px;">
        Se envió un código de <strong>6 caracteres</strong> a tu correo registrado.
        Expira en <strong>10 minutos</strong>.
      </p>

      <form action="{{ route('perfil.otp.verificar.post') }}" method="POST">
        @csrf

        <div class="vl-form-group">
          <label class="vl-label">
            Código de verificación <span class="req">*</span>
          </label>
          <input
            type="text"
            name="codigo"
            class="vl-input"
            placeholder="Ej: XH8AM2"
            maxlength="6"
            autocomplete="off"
            autofocus
            style="text-transform:uppercase; letter-spacing:6px;
                   font-size:1.4rem; text-align:center; font-weight:700;"
          >
          @error('codigo')
            <span style="font-size:.75rem; color:var(--vl-danger);">{{ $message }}</span>
          @enderror
        </div>

        <button type="submit" class="vl-btn vl-btn--primary" style="width:100%;">
          <i class="bi bi-check-lg"></i>
          Confirmar código
        </button>

      </form>

      <div style="margin-top:16px; text-align:center;">
        <a href="{{ route('cuenta.index') }}"
           style="font-size:.8rem; color:var(--vl-text-muted);">
          <i class="bi bi-arrow-left"></i>
          Cancelar y volver
        </a>
      </div>

    </div>
  </div>

  {{-- Aviso local --}}
  @if(config('app.env') === 'local')
    <div class="vl-alert vl-alert--warning vl-animate" style="margin-top:16px;">
      <i class="bi bi-bug-fill"></i>
      <div>
        <strong>Modo local:</strong> el código no se envía por correo.
        Búscalo en <code>storage/logs/laravel.log</code>
      </div>
    </div>
  @endif

</div>
@endsection