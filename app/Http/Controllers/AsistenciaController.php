<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beneficiario;
use App\Models\Entrega;
use App\Models\User;
use Carbon\Carbon;

class AsistenciaController extends Controller
{
    // Muestra la pantalla del celular para poner DNI
    public function index()
    {
        return view('asistencia.index');
    }

    // Busca el DNI en la base de datos y procesa la asistencia
    public function buscarDni(Request $request)
    {
        $request->validate(['dni' => 'required|numeric|digits:8']);

        $beneficiario = Beneficiario::where('dni', $request->dni)->first();

        if ($beneficiario) {
            // Buscamos dinámicamente al primer administrador disponible
            $admin = User::first();

            if (!$admin) {
                return back()->with('error', 'Error del sistema: No existe un usuario administrador registrado.');
            }

            // ✨ AJUSTE CORRECTO PARA TU TABLA REAL 'ENTREGAS'
            Entrega::create([
                'beneficiario_id'      => $beneficiario->id,
                'user_id'              => $admin->id,
                'fecha_entrega'        => Carbon::now()->toDateString(),
                'productos_entregados' => 'Ración diaria estándar' // Volvemos a tu columna real de texto
            ]);

            // En lugar de volver atrás, lo enviamos a su pantalla personalizada
            return redirect()->route('asistencia.bienvenida', $beneficiario->id);
        } else {
            return back()->with('error', 'DNI no encontrado. Por favor, acérquese a registrarse.');
        }
    }

    // Muestra el portal de bienvenida personalizado del ciudadano
    public function bienvenidaBeneficiario($id)
    {
        $beneficiario = Beneficiario::with('entregas')->findOrFail($id);
        $totalRaciones = $beneficiario->entregas->count();

        return view('asistencia.bienvenida', compact('beneficiario', 'totalRaciones'));
    }

    // Muestra el formulario de registro
    public function formularioRegistro()
    {
        return view('asistencia.registro');
    }

    // Guarda los datos en la base de datos
    public function guardarBeneficiario(Request $request)
    {
        $request->validate([
            'dni' => 'required|numeric|digits:8|unique:beneficiarios,dni',
            'nombres' => 'required|string',
            'apellidos' => 'required|string',
            'fecha_nacimiento' => 'required|date',
            'direccion' => 'required|string',
        ]);

        Beneficiario::create([
            'dni'              => $request->dni,
            'nombre'           => $request->nombres,
            'apellido'         => $request->apellidos,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'direccion'        => $request->direccion,
            'estado'           => 1,
        ]);

        return redirect()->route('asistencia.index')->with('success', '¡Registro exitoso! Ahora puedes marcar tu asistencia.');
    }
}