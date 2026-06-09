<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

// RUTAS PÚBLICAS (Para los Ciudadanos)
Route::get('/', function () {
    return redirect()->route('asistencia.index');
});

// Pantalla principal de marcado de DNI
Route::get('/asistencia', [AsistenciaController::class, 'index'])->name('asistencia.index');
Route::post('/asistencia/buscar', [AsistenciaController::class, 'buscarDni'])->name('asistencia.buscar');

// Pantalla de Bienvenida personalizada con contador de Vaso de Leche
Route::get('/asistencia/bienvenida/{id}', [AsistenciaController::class, 'bienvenidaBeneficiario'])->name('asistencia.bienvenida');

// AUTENTICACIÓN (Ingreso al Panel)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// RUTAS PROTEGIDAS (Solo Personal Autenticado)
Route::middleware(['auth'])->group(function () {
    
    // --- DASHBOARD GENERAL ---
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');


   // --- GESTIÓN DE BENEFICIARIOS (Ambos Roles: Maestro y Trabajador) ---
    Route::get('/admin/beneficiarios', [AdminController::class, 'listaBeneficiarios'])->name('admin.beneficiarios');
    
    // CORRECCIÓN AQUÍ: Apuntamos directo al AdminController
    Route::post('/admin/beneficiarios/guardar', [AdminController::class, 'guardarBeneficiario'])->name('beneficiario.guardar');
    
    Route::get('/admin/beneficiarios/{id}/editar', [AdminController::class, 'editarBeneficiario'])->name('admin.editar');
    Route::put('/admin/beneficiarios/{id}/actualizar', [AdminController::class, 'actualizarBeneficiario'])->name('admin.actualizar');
    Route::delete('/admin/beneficiarios/{id}/eliminar', [AdminController::class, 'eliminarBeneficiario'])->name('admin.eliminar');

    // --- GESTIÓN DE PERSONAL / TRABAJADORES (Protección por Rol dentro del Controlador) ---
    // Colocadas dentro del middleware para asegurar que nadie sin iniciar sesión pueda invocarlas
    Route::get('/admin/usuarios', [AdminController::class, 'listaUsuarios'])->name('admin.usuarios');
    Route::post('/admin/usuarios/guardar', [AdminController::class, 'guardarUsuario'])->name('admin.usuarios.guardar');
    Route::delete('/admin/usuarios/eliminar/{id}', [AdminController::class, 'eliminarUsuario'])->name('admin.usuarios.eliminar');
    
    Route::post('/admin/entregas/guardar', [AdminController::class, 'guardarEntrega'])->name('admin.entregas.guardar');
});