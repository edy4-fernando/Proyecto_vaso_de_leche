@extends('layouts.web')

@section('title', 'Contacto — Vaso de Leche Cusco')

@section('content')

{{-- ── Hero ── --}}
<div style="background:rgba(0,0,0,.25); border-bottom:1px solid rgba(255,255,255,.06);
            padding:60px 0 40px;">
  <div class="container">
    <div style="max-width:680px;">
      <span class="vl-badge vl-badge--violet mb-3" style="font-size:.75rem;">
        <i class="bi bi-envelope-fill me-1"></i>
        Atención Ciudadana
      </span>
      <h1 style="font-size:2rem; font-weight:800; color:#fff;
                 line-height:1.2; letter-spacing:-.5px; margin:12px 0;">
        Contacto y Atención al Ciudadano
      </h1>
      <p style="color:rgba(255,255,255,.55); font-size:.95rem; line-height:1.7; margin:0;">
        Comunícate con la Gerencia de Desarrollo Social de la Municipalidad
        Provincial del Cusco para consultas sobre el Programa Vaso de Leche.
      </p>
    </div>
  </div>
</div>

<div class="container" style="padding:48px 0 60px;">

  <div class="row g-5">

    {{-- ── Columna izquierda: Info de contacto ── --}}
    <div class="col-lg-5">

      {{-- Datos de contacto --}}
      <div style="margin-bottom:32px;">
        <h3 style="font-size:1.1rem; font-weight:800; color:#fff;
                   margin-bottom:20px; letter-spacing:-.3px;">
          Información de Contacto
        </h3>

        @foreach([
          [
            'bi-building-fill', '#3b82f6',
            'Dirección',
            'Plaza Regocijo S/N, Municipalidad Provincial del Cusco',
            'Cusco, Perú',
          ],
          [
            'bi-telephone-fill', '#10b981',
            'Teléfonos',
            'Central: (084) 252-781',
            'Desarrollo Social: (084) 252-782',
          ],
          [
            'bi-envelope-fill', '#7c3aed',
            'Correo Electrónico',
            'desarrollosocial@municusco.gob.pe',
            'vasdeleche@municusco.gob.pe',
          ],
          [
            'bi-clock-fill', '#f59e0b',
            'Horario de Atención',
            'Lunes a Viernes: 7:30 am — 3:30 pm',
            'Sábados y domingos: No hay atención',
          ],
        ] as [$icon, $color, $titulo, $linea1, $linea2])
          <div style="display:flex; gap:14px; padding:16px 0;
                      {{ !$loop->last ? 'border-bottom:1px solid rgba(255,255,255,.07);' : '' }}">
            <div style="width:40px; height:40px; border-radius:10px;
                        background:{{ $color }}18; display:flex; align-items:center;
                        justify-content:center; flex-shrink:0;">
              <i class="bi {{ $icon }}" style="color:{{ $color }}; font-size:.95rem;"></i>
            </div>
            <div>
              <div style="font-size:.68rem; color:rgba(255,255,255,.35);
                          text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">
                {{ $titulo }}
              </div>
              <div style="font-size:.82rem; color:#fff; font-weight:600; margin-bottom:2px;">
                {{ $linea1 }}
              </div>
              <div style="font-size:.75rem; color:rgba(255,255,255,.45);">
                {{ $linea2 }}
              </div>
            </div>
          </div>
        @endforeach
      </div>

      {{-- Comités sectoriales --}}
      <div style="background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.08);
                  border-radius:16px; padding:20px; margin-bottom:24px;">
        <h5 style="font-size:.88rem; font-weight:800; color:#fff; margin-bottom:14px;">
          <i class="bi bi-geo-alt-fill me-2" style="color:#f59e0b;"></i>
          Comités del Programa por Sector
        </h5>
        @php
          $sectores = \App\Models\Beneficiario::selectRaw('sector_o_comite, count(*) as total')
            ->whereNotNull('sector_o_comite')
            ->groupBy('sector_o_comite')
            ->orderByDesc('total')
            ->get();
        @endphp
        @forelse($sectores as $sector)
          <div style="display:flex; align-items:center; justify-content:space-between;
                      padding:8px 0;
                      {{ !$loop->last ? 'border-bottom:1px solid rgba(255,255,255,.05);' : '' }}">
            <div style="display:flex; align-items:center; gap:8px;">
              <i class="bi bi-pin-map-fill"
                 style="color:#f59e0b; font-size:.75rem; flex-shrink:0;"></i>
              <span style="font-size:.78rem; color:rgba(255,255,255,.65);">
                {{ $sector->sector_o_comite }}
              </span>
            </div>
            <span style="font-size:.7rem; color:rgba(255,255,255,.3);">
              {{ $sector->total }} benef.
            </span>
          </div>
        @empty
          <p style="font-size:.78rem; color:rgba(255,255,255,.3); margin:0;">
            No hay sectores registrados aún.
          </p>
        @endforelse
      </div>

      {{-- Redes y canales --}}
      <div style="background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.08);
                  border-radius:16px; padding:20px;">
        <h5 style="font-size:.88rem; font-weight:800; color:#fff; margin-bottom:14px;">
          <i class="bi bi-broadcast me-2" style="color:#3b82f6;"></i>
          Canales Oficiales
        </h5>
        @foreach([
          ['bi-globe', '#3b82f6', 'Portal Web Municipal', 'www.municusco.gob.pe'],
          ['bi-facebook', '#1877f2', 'Facebook Oficial', 'Municipalidad Provincial del Cusco'],
          ['bi-youtube', '#ff0000', 'Canal YouTube', 'MuniCusco Oficial'],
        ] as [$icon, $color, $label, $desc])
          <div style="display:flex; align-items:center; gap:10px; padding:10px 0;
                      {{ !$loop->last ? 'border-bottom:1px solid rgba(255,255,255,.05);' : '' }}">
            <div style="width:32px; height:32px; border-radius:8px;
                        background:{{ $color }}20; display:flex; align-items:center;
                        justify-content:center; flex-shrink:0;">
              <i class="bi {{ $icon }}" style="color:{{ $color }}; font-size:.85rem;"></i>
            </div>
            <div>
              <div style="font-size:.78rem; font-weight:600; color:#fff;">{{ $label }}</div>
              <div style="font-size:.68rem; color:rgba(255,255,255,.35);">{{ $desc }}</div>
            </div>
          </div>
        @endforeach
      </div>

    </div>

    {{-- ── Columna derecha: Formulario ── --}}
    <div class="col-lg-7">

      <div style="background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.08);
                  border-radius:20px; padding:32px;">

        <h3 style="font-size:1.1rem; font-weight:800; color:#fff;
                   margin-bottom:6px;">
          Envíanos un mensaje
        </h3>
        <p style="font-size:.8rem; color:rgba(255,255,255,.4); margin-bottom:24px;">
          Completa el formulario y nos comunicaremos contigo a la brevedad posible.
        </p>

        @if(session('contacto_ok'))
          <div style="background:rgba(16,185,129,.12); border:1px solid rgba(16,185,129,.25);
                      border-radius:10px; padding:14px 16px; margin-bottom:20px;
                      display:flex; align-items:center; gap:10px;">
            <i class="bi bi-check-circle-fill" style="color:#10b981; font-size:1.1rem;"></i>
            <span style="font-size:.82rem; color:#6ee7b7;">
              ¡Mensaje enviado correctamente! Nos comunicaremos pronto.
            </span>
          </div>
        @endif

        <form action="{{ route('web.contacto.post') }}" method="POST">
          @csrf

          <div class="row g-3">

            <div class="col-md-6">
              <label class="vl-label" style="color:rgba(255,255,255,.5);">
                Nombre completo <span class="req">*</span>
              </label>
              <input type="text" name="nombre" class="vl-input"
                     style="background:rgba(255,255,255,.07); border-color:rgba(255,255,255,.12);
                            color:#fff;"
                     placeholder="Ej: Juan Quispe"
                     value="{{ old('nombre') }}" required>
              @error('nombre')
                <span style="font-size:.7rem; color:#f43f5e;">{{ $message }}</span>
              @enderror
            </div>

            <div class="col-md-6">
              <label class="vl-label" style="color:rgba(255,255,255,.5);">
                DNI
              </label>
              <input type="text" name="dni" class="vl-input"
                     style="background:rgba(255,255,255,.07); border-color:rgba(255,255,255,.12);
                            color:#fff;"
                     placeholder="12345678" maxlength="8"
                     value="{{ old('dni') }}">
            </div>

            <div class="col-md-6">
              <label class="vl-label" style="color:rgba(255,255,255,.5);">
                Correo electrónico
              </label>
              <input type="email" name="email" class="vl-input"
                     style="background:rgba(255,255,255,.07); border-color:rgba(255,255,255,.12);
                            color:#fff;"
                     placeholder="correo@ejemplo.com"
                     value="{{ old('email') }}">
            </div>

            <div class="col-md-6">
              <label class="vl-label" style="color:rgba(255,255,255,.5);">
                Teléfono
              </label>
              <input type="text" name="telefono" class="vl-input"
                     style="background:rgba(255,255,255,.07); border-color:rgba(255,255,255,.12);
                            color:#fff;"
                     placeholder="984123456" maxlength="9"
                     value="{{ old('telefono') }}">
            </div>

            <div class="col-12">
              <label class="vl-label" style="color:rgba(255,255,255,.5);">
                Tipo de consulta <span class="req">*</span>
              </label>
              <select name="tipo_consulta" class="vl-select"
                      style="background:rgba(255,255,255,.07); border-color:rgba(255,255,255,.12);
                             color:#fff;" required>
                <option value="">— Seleccionar —</option>
                <option value="inscripcion"    {{ old('tipo_consulta')==='inscripcion'    ?'selected':'' }}>
                  Inscripción al programa
                </option>
                <option value="actualizacion"  {{ old('tipo_consulta')==='actualizacion'  ?'selected':'' }}>
                  Actualización de datos
                </option>
                <option value="reclamo"        {{ old('tipo_consulta')==='reclamo'        ?'selected':'' }}>
                  Reclamo o queja
                </option>
                <option value="informacion"    {{ old('tipo_consulta')==='informacion'    ?'selected':'' }}>
                  Información general
                </option>
                <option value="baja"           {{ old('tipo_consulta')==='baja'           ?'selected':'' }}>
                  Solicitud de baja del programa
                </option>
                <option value="otro"           {{ old('tipo_consulta')==='otro'           ?'selected':'' }}>
                  Otro
                </option>
              </select>
              @error('tipo_consulta')
                <span style="font-size:.7rem; color:#f43f5e;">{{ $message }}</span>
              @enderror
            </div>

            <div class="col-12">
              <label class="vl-label" style="color:rgba(255,255,255,.5);">
                Sector / Comité de residencia
              </label>
              <input type="text" name="sector" class="vl-input"
                     style="background:rgba(255,255,255,.07); border-color:rgba(255,255,255,.12);
                            color:#fff;"
                     placeholder="Ej: Comité 12 - Ttio"
                     value="{{ old('sector') }}">
            </div>

            <div class="col-12">
              <label class="vl-label" style="color:rgba(255,255,255,.5);">
                Mensaje <span class="req">*</span>
              </label>
              <textarea name="mensaje" class="vl-textarea"
                        style="background:rgba(255,255,255,.07); border-color:rgba(255,255,255,.12);
                               color:#fff; min-height:120px;"
                        placeholder="Describe tu consulta, reclamo o comentario..."
                        required maxlength="500">{{ old('mensaje') }}</textarea>
              @error('mensaje')
                <span style="font-size:.7rem; color:#f43f5e;">{{ $message }}</span>
              @enderror
            </div>

          </div>

          <div style="margin-top:20px; display:flex; gap:12px; flex-wrap:wrap;
                      align-items:center;">
            <button type="submit" class="btn-portal-primary"
                    style="width:auto; padding:11px 24px;">
              <i class="bi bi-send-fill me-2"></i>
              Enviar mensaje
            </button>
            <span style="font-size:.72rem; color:rgba(255,255,255,.25);">
              <i class="bi bi-shield-check me-1"></i>
              Tus datos están protegidos
            </span>
          </div>

        </form>

      </div>

      {{-- Nota informativa --}}
      <div style="margin-top:20px; background:rgba(245,158,11,.08);
                  border:1px solid rgba(245,158,11,.15); border-radius:14px;
                  padding:16px 20px; display:flex; gap:12px;">
        <i class="bi bi-exclamation-triangle-fill"
           style="color:#f59e0b; font-size:1rem; flex-shrink:0; margin-top:1px;"></i>
        <div>
          <div style="font-size:.82rem; font-weight:700; color:#fbbf24; margin-bottom:4px;">
            Importante
          </div>
          <div style="font-size:.75rem; color:rgba(255,255,255,.45); line-height:1.6;">
            Para <strong style="color:rgba(255,255,255,.6);">inscripción, bajas o
            modificaciones en el padrón</strong>, es necesario acercarse
            presencialmente a la Gerencia de Desarrollo Social con la documentación
            requerida. No se realizan cambios por este canal.
          </div>
        </div>
      </div>

    </div>

  </div>

</div>

@endsection