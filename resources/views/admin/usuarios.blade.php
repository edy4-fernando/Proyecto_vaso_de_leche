{{-- ============================================================
     admin/usuarios.blade.php
     Ruta: GET /admin/usuarios → AdminController@listaUsuarios
     Variables: $usuarios (Collection)
     Solo accesible para rol maestro
     ============================================================ --}}

@extends('layouts.admin')

@section('title', 'Personal — Vaso de Leche')
@section('breadcrumb', 'Gestión de Personal')
@php $activeModule = 'usuarios'; @endphp

@section('content')

{{-- ── Encabezado ── --}}
<div class="vl-section-header">
  <div>
    <h2 class="vl-section-title">
      <i class="bi bi-shield-lock-fill text-danger"></i>
      Gestión de Personal
    </h2>
    <p class="vl-section-sub">
      {{ $usuarios->count() }} usuarios registrados en el sistema
    </p>
  </div>
  <button class="vl-btn vl-btn--primary vl-btn--sm"
          data-bs-toggle="modal"
          data-bs-target="#modalNuevoUsuario">
    <i class="bi bi-person-plus-fill"></i>
    Nuevo Usuario
  </button>
</div>

{{-- ── Alerta solo maestro ── --}}
<div class="vl-alert vl-alert--warning mb-4 vl-animate">
  <i class="bi bi-exclamation-triangle-fill"></i>
  <div>
    <strong>Zona restringida.</strong>
    Solo el usuario maestro puede crear, modificar o eliminar cuentas del personal.
    Los cambios aquí afectan el acceso al sistema.
  </div>
</div>

{{-- ── Buscador ── --}}
<div class="vl-card mb-4 vl-animate">
  <div class="vl-card__body" style="padding: 14px 20px;">
    <div class="vl-search-bar">
      <div class="vl-input-icon-wrap" style="flex: 1; min-width: 220px;">
        <i class="bi bi-search"></i>
        <input type="text"
               class="vl-input"
               id="vlTableSearch"
               data-table="tblUsuarios"
               data-cols="0,1,2"
               placeholder="Buscar por nombre, DNI o correo…">
      </div>
      <select class="vl-select"
              id="vlSelectFilter"
              data-table="tblUsuarios"
              data-col="3"
              style="width: auto; min-width: 140px;">
        <option value="">Todos los roles</option>
        <option value="maestro">Maestro</option>
        <option value="trabajador">Trabajador</option>
      </select>
    </div>
  </div>
</div>

{{-- ── Tabla ── --}}
<div class="vl-card vl-animate vl-animate--delay-1">
  <div class="vl-card__accent"></div>
  <div class="vl-card__body" style="padding: 0;">
    <div class="vl-table-wrap">
      <table class="vl-table" id="tblUsuarios">
        <thead>
          <tr>
            <th>Usuario</th>
            <th>DNI</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Estado</th>
            <th>Último ingreso</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($usuarios as $u)
            <tr>

              {{-- Usuario --}}
              <td>
                <div class="vl-table__cell-user">
                  @include('admin._components.avatar', [
                    'name'  => $u->name,
                    'class' => 'vl-table__mini-avatar',
                    'size'  => 'sm',
                  ])
                  <div>
                    <span class="vl-table__cell-name">{{ $u->name }}</span>
                    @if($u->id === auth()->id())
                      <span class="vl-badge vl-badge--blue" style="font-size: .6rem;">
                        Tú
                      </span>
                    @endif
                  </div>
                </div>
              </td>

              {{-- DNI --}}
              <td>
                <span style="font-family: var(--vl-font-mono); font-size: .8rem;">
                  {{ $u->dni ?? '—' }}
                </span>
              </td>

              {{-- Correo --}}
              <td>
                <span style="font-size: .78rem; color: var(--vl-text-sub);">
                  {{ $u->email }}
                </span>
              </td>

              {{-- Rol --}}
              <td>
                <span class="vl-badge {{ $u->rol === 'maestro' ? 'vl-badge--violet' : 'vl-badge--blue' }}">
                  <i class="bi bi-{{ $u->rol === 'maestro' ? 'shield-fill' : 'person-fill' }}"></i>
                  {{ ucfirst($u->rol) }}
                </span>
              </td>

              {{-- Estado cuenta --}}
              <td>
                @if($u->estado_cuenta ?? true)
                  <span class="vl-badge vl-badge--green">
                    <i class="bi bi-check-circle-fill"></i> Activo
                  </span>
                @else
                  <span class="vl-badge vl-badge--rose">
                    <i class="bi bi-x-circle-fill"></i> Bloqueado
                  </span>
                @endif
              </td>

              {{-- Último ingreso --}}
              <td>
                <span style="font-size: .75rem; color: var(--vl-text-muted);">
                  @if($u->ultimo_ingreso)
                    <i class="bi bi-clock me-1"></i>
                    {{ \Carbon\Carbon::parse($u->ultimo_ingreso)->format('d/m/Y H:i') }}
                  @else
                    <span style="opacity: .5;">Sin ingresos</span>
                  @endif
                </span>
              </td>

              {{-- Acciones --}}
              <td>
                <div class="vl-actions">

                  {{-- No permitir eliminar al propio maestro --}}
                  @if($u->id !== auth()->id())
                    <form action="{{ route('admin.usuarios.eliminar', $u->id) }}"
                          method="POST"
                          class="m-0"
                          data-confirm-delete="¿Eliminar permanentemente la cuenta de {{ $u->name }}?">
                      @csrf
                      @method('DELETE')
                      <button type="submit"
                              class="vl-btn vl-btn--ghost vl-btn--icon text-danger"
                              data-bs-toggle="tooltip"
                              title="Eliminar usuario">
                        <i class="bi bi-trash3-fill"></i>
                      </button>
                    </form>
                  @else
                    <span class="vl-badge vl-badge--slate" style="font-size: .65rem;">
                      <i class="bi bi-lock-fill"></i> Protegido
                    </span>
                  @endif

                </div>
              </td>

            </tr>
          @empty
            <tr id="vlTableEmpty">
              <td colspan="7">
                <div class="vl-table__empty">
                  <i class="bi bi-people"></i>
                  <p>No hay usuarios registrados en el sistema.</p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection

{{-- ============================================================
     MODAL — Nuevo Usuario
     ============================================================ --}}
@section('modals')
<div class="modal fade vl-modal" id="modalNuevoUsuario" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">
          <i class="bi bi-person-plus-fill text-primary"></i>
          Nuevo Usuario
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form action="{{ route('admin.usuarios.guardar') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="row g-3">

            {{-- Nombre completo --}}
            <div class="col-12">
              <label class="vl-label">
                Nombre completo <span class="req">*</span>
              </label>
              <input type="text"
                     name="name"
                     class="vl-input"
                     placeholder="Ej: Juan Carlos Mamani"
                     required
                     data-uppercase>
            </div>

            {{-- DNI --}}
            <div class="col-md-6">
              <label class="vl-label">DNI <span class="req">*</span></label>
              <input type="text"
                     name="dni"
                     class="vl-input"
                     placeholder="12345678"
                     maxlength="8"
                     pattern="\d{8}"
                     required>
            </div>

            {{-- Teléfono --}}
            <div class="col-md-6">
              <label class="vl-label">Teléfono</label>
              <input type="text"
                     name="telefono"
                     class="vl-input"
                     placeholder="984123456"
                     maxlength="15">
            </div>

            {{-- Correo --}}
            <div class="col-12">
              <label class="vl-label">
                Correo electrónico <span class="req">*</span>
              </label>
              <div class="vl-input-icon-wrap">
                <i class="bi bi-envelope-fill"></i>
                <input type="email"
                       name="email"
                       class="vl-input"
                       placeholder="usuario@municipalidad.gob.pe"
                       required>
              </div>
            </div>

            {{-- Rol --}}
            <div class="col-md-6">
              <label class="vl-label">
                Rol <span class="req">*</span>
              </label>
              <select name="rol" class="vl-select" required>
                <option value="trabajador" selected>Trabajador</option>
                <option value="maestro">Maestro</option>
              </select>
            </div>

            {{-- Contraseña --}}
            <div class="col-md-6">
              <label class="vl-label">
                Contraseña <span class="req">*</span>
              </label>
              <input type="password"
                     name="password"
                     class="vl-input"
                     placeholder="Mínimo 8 caracteres"
                     minlength="8"
                     required>
            </div>

          </div>

          {{-- Nota informativa --}}
          <div class="vl-alert vl-alert--info mt-3" style="margin-bottom: 0;">
            <i class="bi bi-info-circle-fill"></i>
            <div style="font-size: .78rem;">
              El usuario podrá cambiar su contraseña desde el panel una vez ingrese.
            </div>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button"
                  class="vl-btn vl-btn--outline"
                  data-bs-dismiss="modal">
            Cancelar
          </button>
          <button type="submit" class="vl-btn vl-btn--primary">
            <i class="bi bi-check-lg"></i>
            Crear usuario
          </button>
        </div>

      </form>
    </div>
  </div>
</div>
@endsection