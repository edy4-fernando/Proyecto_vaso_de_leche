<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /* ============================================================
       1. MOSTRAR LOGIN
       GET /login
       ============================================================ */
    public function showLogin()
    {
        // Solo redirigir si ya está autenticado Y viene del panel admin
        if (Auth::check()) {
            if (Auth::user()->rol === 'maestro') {
                return redirect()->route('seleccion.panel');
            }
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    /* ============================================================
       2. PROCESAR LOGIN
       POST /login
       ============================================================ */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required'    => 'El correo electrónico es obligatorio.',
            'email.email'       => 'Ingrese un correo electrónico válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min'      => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        $credenciales = $request->only('email', 'password');
        $recordar     = $request->boolean('remember', false);

        if (!Auth::attempt($credenciales, $recordar)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Las credenciales no coinciden con nuestros registros.',
                ]);
        }

        $user = Auth::user();

        // Verificar estado de la cuenta
        if (!$user->estado_cuenta) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Su cuenta está bloqueada. Contacte al administrador.',
            ]);
        }

        // Registrar último ingreso
        $user->update(['ultimo_ingreso' => now()]);

        // Regenerar sesión (seguridad)
        $request->session()->regenerate();

        return $this->redirigirSegunRol();
    }

    /* ============================================================
       3. CERRAR SESIÓN
       POST /logout
       ============================================================ */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('info', 'Sesión cerrada correctamente.');
    }

    /* ============================================================
       HELPER — Redirigir según rol del usuario
       ============================================================ */
    private function redirigirSegunRol()
    {
        $user = Auth::user();

        // Maestro ve la pantalla de selección de panel
        if ($user->rol === 'maestro') {
            return redirect()->route('seleccion.panel');
        }

        // Trabajador va directo al panel admin
        return redirect()->route('admin.dashboard');
    }
}