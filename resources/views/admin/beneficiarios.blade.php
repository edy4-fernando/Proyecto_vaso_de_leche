{{-- ============================================================
     admin/beneficiarios.blade.php
     Ruta: GET /admin/beneficiarios → AdminController@listaBeneficiarios
     Variables: $beneficiarios (Collection), $productos (Collection)
     ============================================================ --}}

@extends('layouts.admin')

@section('title', 'Beneficiarios — Vaso de Leche')
@section('breadcrumb', 'Beneficiarios')
@php $activeModule = 'beneficiarios'; @endphp

@section('content')

{{-- ── Encabezado ── --}}
<div class="vl-section-header">
  <div>
    <h2 class="vl-section-title">
      <i class="bi bi-people-fill text-primary"></i>
      Padrón de Beneficiarios
    </h2>
    <p class="vl-section-sub">
      {{ $beneficiarios->count() }} registros en la base de datos
    </p>
  </div>
  <div class="d-flex gap-2 flex-wrap">
    <button class="vl-btn vl-btn--outline vl-btn--sm"
            onclick="window.print()"
            title="Imprimir padrón">
      <i class="bi bi-printer-fill"></i>
      Imprimir
    </button>
    @if(auth()->user()->rol === 'maestro')
      <button class="vl-btn vl-btn--primary vl-btn--sm"
              data-bs-toggle="modal"
              data-bs-target="#modalNuevoBeneficiario">
        <i class="bi bi-person-plus-fill"></i>
        Nuevo Beneficiario
      </button>
    @endif
  </div>
</div>

{{-- ── Buscador y filtros ── --}}
<div class="vl-card mb-4 vl-animate">
  <div class="vl-card__body" style="padding: 14px 20px;">
    <div class="vl-search-bar">

      <div class="vl-input-icon-wrap" style="flex: 1; min-width: 220px;">
        <i class="bi bi-search"></i>
        <input type="text"
               class="vl-input"
               id="vlTableSearch"
               data-table="tblBeneficiarios"
               data-cols="0,1,2,3"
               placeholder="Buscar por DNI, nombre, sector…">
      </div>

      <select class="vl-select"
              id="vlSelectFilter"
              data-table="tblBeneficiarios"
              data-col="5"
              style="width: auto; min-width: 150px;">
        <option value="">Todos los estados</option>
        <option value="activo">Activos</option>
        <option value="baja">De baja</option>
      </select>

      <button class="vl-btn vl-btn--ghost vl-btn--sm"
              id="btnLimpiarFiltros"
              title="Limpiar filtros">
        <i class="bi bi-x-circle"></i>
        Limpiar
      </button>

    </div>
  </div>
</div>

{{-- ── Tabla ── --}}
<div class="vl-card vl-animate vl-animate--delay-1">
  <div class="vl-card__accent"></div>
  <div class="vl-card__body" style="padding: 0;">
    <div class="vl-table-wrap">
      <table class="vl-table" id="tblBeneficiarios">
        <thead>
          <tr>
            <th>DNI</th>
            <th>Beneficiario</th>
            <th>Tipo</th>
            <th>Sector / Comité</th>
            <th>Edad</th>
            <th>Estado</th>
            <th>Ración hoy</th>
            <th class="col-acciones">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($beneficiarios as $b)
            @php
              $recibiHoy = isset($conRacionHoy)
                ? in_array($b->id, $conRacionHoy->pluck('beneficiario_id')->toArray())
                : false;
            @endphp
            <tr>

              {{-- DNI --}}
              <td>
                <span style="font-family: var(--vl-font-mono); font-size: 0.8rem;">
                  {{ $b->dni }}
                </span>
              </td>

              {{-- Nombre --}}
              <td>
                <div class="vl-table__cell-user">
                  <div class="vl-table__mini-avatar">
                    {{ strtoupper(substr($b->nombre, 0, 1)) }}
                  </div>
                  <div>
                    <span class="vl-table__cell-name">
                      {{ strtoupper($b->apellido) }}, {{ $b->nombre }}
                    </span>
                    @if($b->nombre_apoderado)
                      <span class="vl-table__cell-sub">
                        <i class="bi bi-person-fill me-1"></i>
                        Apod: {{ $b->nombre_apoderado }}
                      </span>
                    @endif
                  </div>
                </div>
              </td>

              {{-- Tipo --}}
              <td>
                <span class="vl-badge {{ $b->tipo_beneficiario === 'niño' ? 'vl-badge--blue' : ($b->tipo_beneficiario === 'gestante' ? 'vl-badge--violet' : 'vl-badge--slate') }}">
                  {{ ucfirst($b->tipo_beneficiario ?? '—') }}
                </span>
              </td>

              {{-- Sector --}}
              <td>
                <span style="font-size: 0.78rem;">
                  {{ $b->sector_o_comite ?? '—' }}
                </span>
              </td>

              {{-- Edad --}}
              <td>
                <span style="font-size: 0.8rem;">
                  {{ $b->fecha_nacimiento ? \Carbon\Carbon::parse($b->fecha_nacimiento)->age . ' años' : '—' }}
                </span>
              </td>

              {{-- Estado --}}
              <td>
                @if($b->estado)
                  <span class="vl-badge vl-badge--green">
                    <i class="bi bi-check-circle-fill"></i> Activo
                  </span>
                @else
                  <span class="vl-badge vl-badge--rose">
                    <i class="bi bi-x-circle-fill"></i> Baja
                  </span>
                @endif
              </td>

              {{-- Ración hoy --}}
              <td>
                @if($recibiHoy)
                  <span class="vl-badge vl-badge--green">
                    <i class="bi bi-check-lg"></i> Sí
                  </span>
                @else
                  <span class="vl-badge vl-badge--slate">
                    <i class="bi bi-dash"></i> No
                  </span>
                @endif
              </td>

              {{-- Acciones --}}
              <td class="col-acciones">
                <div class="vl-actions">

                  {{-- Ver perfil --}}
                  <a href="{{ route('admin.perfil', $b->id) }}"
                     class="vl-btn vl-btn--ghost vl-btn--icon"
                     data-bs-toggle="tooltip"
                     title="Ver perfil">
                    <i class="bi bi-eye-fill"></i>
                  </a>

                  {{-- Editar (maestro y trabajador) --}}
                  <a href="{{ route('admin.editar', $b->id) }}"
                     class="vl-btn vl-btn--ghost vl-btn--icon"
                     data-bs-toggle="tooltip"
                     title="Editar">
                    <i class="bi bi-pencil-fill"></i>
                  </a>

                  {{-- Toggle estado + Eliminar (solo maestro) --}}
                  @if(auth()->user()->rol === 'maestro')

                    <form action="{{ route('admin.beneficiarios.toggle', $b->id) }}"
                          method="POST" class="m-0">
                      @csrf
                      @method('PATCH')
                      <button type="submit"
                              class="vl-btn vl-btn--ghost vl-btn--icon {{ $b->estado ? 'text-warning' : 'text-success' }}"
                              data-bs-toggle="tooltip"
                              title="{{ $b->estado ? 'Dar de baja' : 'Reactivar' }}">
                        <i class="bi bi-{{ $b->estado ? 'person-dash-fill' : 'person-check-fill' }}"></i>
                      </button>
                    </form>

                    <form action="{{ route('admin.eliminar', $b->id) }}"
                          method="POST"
                          class="m-0"
                          data-confirm-delete="¿Eliminar PERMANENTEMENTE a {{ $b->nombre }} {{ $b->apellido }}?">
                      @csrf
                      @method('DELETE')
                      <button type="submit"
                              class="vl-btn vl-btn--ghost vl-btn--icon text-danger"
                              data-bs-toggle="tooltip"
                              title="Eliminar permanentemente">
                        <i class="bi bi-trash3-fill"></i>
                      </button>
                    </form>

                  @endif

                </div>
              </td>

            </tr>
          @empty
            <tr id="vlTableEmpty">
              <td colspan="8">
                <div class="vl-table__empty">
                  <i class="bi bi-people"></i>
                  <p>No hay beneficiarios registrados en el padrón.</p>
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
     MODAL — Nuevo Beneficiario
     ============================================================ --}}
@section('modals')
@if(auth()->user()->rol === 'maestro')
<div class="modal fade vl-modal" id="modalNuevoBeneficiario" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">
          <i class="bi bi-person-plus-fill text-primary"></i>
          Nuevo Beneficiario
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form action="{{ route('beneficiario.guardar') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="row g-3">

            {{-- DNI --}}
            <div class="col-md-4">
              <label class="vl-label">DNI <span class="req">*</span></label>
              <input type="text" name="dni" class="vl-input"
                     placeholder="12345678" maxlength="8"
                     pattern="\d{8}" required
                     data-uppercase>
            </div>

            {{-- Nombre --}}
            <div class="col-md-4">
              <label class="vl-label">Nombre(s) <span class="req">*</span></label>
              <input type="text" name="nombre" class="vl-input"
                     placeholder="Ej: Ana Lucía" required
                     data-uppercase>
            </div>

            {{-- Apellido --}}
            <div class="col-md-4">
              <label class="vl-label">Apellidos <span class="req">*</span></label>
              <input type="text" name="apellido" class="vl-input"
                     placeholder="Ej: Quispe Flores" required
                     data-uppercase>
            </div>

            {{-- Fecha nacimiento --}}
            <div class="col-md-4">
              <label class="vl-label">Fecha de nacimiento <span class="req">*</span></label>
              <input type="date" name="fecha_nacimiento" class="vl-input" required>
            </div>

            {{-- Tipo --}}
            <div class="col-md-4">
              <label class="vl-label">Tipo de beneficiario <span class="req">*</span></label>
              <select name="tipo_beneficiario" class="vl-select" required>
                <option value="">— Seleccionar —</option>

                <optgroup label="Primera Prioridad">
                  <option value="niño 0-6">Niño/a de 0 a 6 años</option>
                  <option value="gestante">Madre gestante</option>
                  <option value="lactante">Madre en lactancia (puérpera)</option>
                </optgroup>

                <optgroup label="Segunda Prioridad">
                  <option value="niño 7-13">Niño/a de 7 a 13 años</option>
                  <option value="adulto mayor">Adulto mayor</option>
                  <option value="discapacitado">Persona con discapacidad (CONADIS)</option>
                  <option value="tbc">Persona con Tuberculosis (TBC)</option>
                </optgroup>

              </select>
            </div>

            {{-- Sector --}}
            <div class="col-md-4">
              <label class="vl-label">Sector / Comité</label>
              <input type="text" name="sector_o_comite" class="vl-input"
                     placeholder="Ej: Comité 12 - Ttio"
                     data-uppercase>
            </div>

            {{-- Dirección --}}
            <div class="col-12">
              <label class="vl-label">Dirección <span class="req">*</span></label>
              <input type="text" name="direccion" class="vl-input"
                     placeholder="Ej: Av. El Sol 456 - Cusco" required
                     data-uppercase>
            </div>

            {{-- Teléfono --}}
            <div class="col-md-4">
              <label class="vl-label">Teléfono</label>
              <input type="text" name="telefono" class="vl-input"
                     placeholder="984123456" maxlength="15">
            </div>

            {{-- Nombre apoderado --}}
            <div class="col-md-4">
              <label class="vl-label">Nombre del apoderado</label>
              <input type="text" name="nombre_apoderado" class="vl-input"
                     placeholder="Opcional"
                     data-uppercase>
            </div>

            {{-- DNI apoderado --}}
            <div class="col-md-4">
              <label class="vl-label">DNI del apoderado</label>
              <input type="text" name="dni_apoderado" class="vl-input"
                     placeholder="Opcional" maxlength="8">
            </div>

            {{-- Observaciones médicas --}}
            <div class="col-12">
              <label class="vl-label">Observaciones médicas</label>
              <textarea name="observaciones_medicas"
                        class="vl-textarea"
                        placeholder="Alergias, condiciones especiales, etc."
                        rows="2"
                        maxlength="500"></textarea>
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
            Guardar beneficiario
          </button>
        </div>

      </form>
    </div>
  </div>
</div>
@endif
@endsection

@push('scripts')
<script>
  // Limpiar filtros
  document.getElementById('btnLimpiarFiltros')?.addEventListener('click', () => {
    document.getElementById('vlTableSearch').value = '';
    document.getElementById('vlSelectFilter').value = '';
    document.querySelectorAll('#tblBeneficiarios tbody tr').forEach(r => {
      r.style.display = '';
    });
    document.getElementById('vlTableEmpty') &&
      (document.getElementById('vlTableEmpty').style.display = 'none');
  });
</script>
@endpush