<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS — Sin autenticación
|--------------------------------------------------------------------------
*/

// Raíz → portal de asistencia
Route::get('/', function () {
    return redirect()->route('asistencia.index');
});

// Portal público de asistencia (no requiere login)
Route::get('/asistencia', [AsistenciaController::class, 'index'])
    ->name('asistencia.index');
Route::post('/asistencia/buscar', [AsistenciaController::class, 'buscarDni'])
    ->name('asistencia.buscar');
Route::get('/asistencia/bienvenida/{id}', [AsistenciaController::class, 'bienvenidaBeneficiario'])
    ->name('asistencia.bienvenida');
// Páginas institucionales — sin autenticación
Route::get('/servicios', function () {
    return view('publico.servicios');
})->name('web.servicios');

Route::get('/noticias', function () {
    return view('publico.noticias');
})->name('web.noticias');

Route::get('/contacto', function () {
    return view('publico.contacto');
})->name('web.contacto');

Route::post('/contacto', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'nombre'        => 'required|string|max:150',
        'tipo_consulta' => 'required|string',
        'mensaje'       => 'required|string|max:500',
    ]);
    \Illuminate\Support\Facades\Log::info('Contacto web', $request->except('_token'));
    return redirect()->route('web.contacto')->with('contacto_ok', true);
})->name('web.contacto.post');

/*
|--------------------------------------------------------------------------
| AUTENTICACIÓN
|--------------------------------------------------------------------------
*/

// Mostrar login (si ya está autenticado, redirigir según rol)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| SELECCIÓN DE PANEL — Solo maestro, requiere auth
|--------------------------------------------------------------------------
*/

Route::get('/seleccion', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    if (auth()->user()->rol !== 'maestro') {
        return redirect()->route('admin.dashboard');
    }
    return view('auth.seleccion');
})->name('seleccion.panel')->middleware('auth');

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS — Requieren autenticación
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // ── PANEL ADMIN ───────────────────────────────────────────────────────

    Route::get('/admin', [AdminController::class, 'index'])
        ->name('admin.dashboard');

    // Beneficiarios
    Route::get('/admin/beneficiarios',
        [AdminController::class, 'listaBeneficiarios'])->name('admin.beneficiarios');
    Route::post('/admin/beneficiarios/guardar',
        [AdminController::class, 'guardarBeneficiario'])->name('beneficiario.guardar');
    Route::get('/admin/beneficiarios/{id}/perfil',
        [AdminController::class, 'perfilBeneficiario'])->name('admin.perfil');
    Route::get('/admin/beneficiarios/{id}/editar',
        [AdminController::class, 'editarBeneficiario'])->name('admin.editar');
    Route::put('/admin/beneficiarios/{id}/actualizar',
        [AdminController::class, 'actualizarBeneficiario'])->name('admin.actualizar');
    Route::delete('/admin/beneficiarios/{id}/eliminar',
        [AdminController::class, 'eliminarBeneficiario'])->name('admin.eliminar');
    Route::patch('/admin/beneficiarios/{id}/toggle-estado',
        [AdminController::class, 'toggleEstadoBeneficiario'])->name('admin.beneficiarios.toggle');

    // Usuarios
    Route::get('/admin/usuarios',
        [AdminController::class, 'listaUsuarios'])->name('admin.usuarios');
    Route::post('/admin/usuarios/guardar',
        [AdminController::class, 'guardarUsuario'])->name('admin.usuarios.guardar');
    Route::delete('/admin/usuarios/eliminar/{id}',
        [AdminController::class, 'eliminarUsuario'])->name('admin.usuarios.eliminar');

    // Entregas
    Route::post('/admin/entregas/guardar',
        [AdminController::class, 'guardarEntrega'])->name('admin.entregas.guardar');
    Route::get('/admin/entregas',
        [AdminController::class, 'historialEntregas'])->name('admin.entregas');

    // Productos
    Route::get('/admin/productos',
        [AdminController::class, 'listaProductos'])->name('admin.productos');
    Route::post('/admin/productos/guardar',
        [AdminController::class, 'guardarProducto'])->name('admin.productos.guardar');
    Route::delete('/admin/productos/{id}/eliminar',
        [AdminController::class, 'eliminarProducto'])->name('admin.productos.eliminar');
    Route::post('/admin/productos/{id}/reabastecer',
        [AdminController::class, 'reabastecerProducto'])->name('admin.productos.reabastecer');


    // Cambiar contraseña
    Route::post('/perfil/cambiar-password',
        [AdminController::class, 'cambiarPassword'])->name('perfil.cambiar-password');
    // Actualizar correo electrónico
    Route::post('/perfil/actualizar-email',
        [AdminController::class, 'actualizarEmail'])->name('perfil.actualizar-email');
    // ── DASHBOARD — Solo maestro ──────────────────────────────────────────

    Route::middleware(['es.admin:maestro'])->group(function () {
        Route::get('/dashboard',
            [AdminController::class, 'dashboardNuevo'])->name('dashboard.index');
        Route::get('/dashboard/estadisticas',
            [AdminController::class, 'dashboardNuevo'])->name('dashboard.estadisticas');
        Route::get('/dashboard/historial',
            [AdminController::class, 'dashboardHistorial'])->name('dashboard.historial');
        Route::get('/dashboard/alertas',
            [AdminController::class, 'dashboardAlertas'])->name('dashboard.alertas');
        Route::get('/dashboard/reportes',
            [AdminController::class, 'dashboardReportes'])->name('dashboard.reportes');
        Route::get('/dashboard/actividad',
            [AdminController::class, 'dashboardActividad'])->name('dashboard.actividad');
        Route::get('/dashboard/configuracion',
            [AdminController::class, 'dashboardConfiguracion'])->name('dashboard.configuracion');
    });
    Route::put('/dashboard/configuracion',
    [AdminController::class, 'guardarConfiguracion'])->name('dashboard.configuracion.guardar');

    Route::get('/cuenta', [AdminController::class, 'cuenta'])->name('cuenta.index');
    Route::get('/admin/mi-actividad', [AdminController::class, 'miActividad'])->name('admin.mi-actividad');

    // Papelera — solo maestro
    Route::get('/admin/papelera', [AdminController::class, 'papelera'])->name('admin.papelera');
    Route::patch('/admin/beneficiarios/{id}/restaurar', [AdminController::class, 'restaurarBeneficiario'])->name('admin.beneficiarios.restaurar');
    Route::patch('/admin/productos/{id}/restaurar', [AdminController::class, 'restaurarProducto'])->name('admin.productos.restaurar');
    Route::patch('/admin/entregas/{id}/restaurar', [AdminController::class, 'restaurarEntrega'])->name('admin.entregas.restaurar');
});