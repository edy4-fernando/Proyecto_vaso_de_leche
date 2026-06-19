<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beneficiario;
use App\Models\Entrega;
use App\Models\Producto;
use App\Models\Configuracion;

class AsistenciaController extends Controller
{
    public function index()
    {
        return view('publico.index');
    }

    public function buscarDni(Request $request)
    {
        $request->validate([
            'dni' => 'required|digits:8',
        ], [
            'dni.required' => 'Debe ingresar un DNI.',
            'dni.digits'   => 'El DNI debe tener exactamente 8 dígitos.',
        ]);

        $beneficiario = Beneficiario::where('dni', $request->dni)
            ->where('estado', true)
            ->first();

        if (!$beneficiario) {
            return redirect()->route('asistencia.index')
                ->with('error', 'DNI ' . $request->dni . ' no encontrado o beneficiario inactivo.');
        }

        $limite = (int) Configuracion::get('limite_entregas_diarias', 1);

        $entregasHoy = Entrega::where('beneficiario_id', $beneficiario->id)
            ->whereDate('fecha_entrega', today())
            ->count();

        if ($entregasHoy >= $limite) {
            return redirect()->route('asistencia.index')
                ->with('warning',
                    strtoupper($beneficiario->apellido) . ', ' . $beneficiario->nombre .
                    ' ya recibió su ración hoy.'
                );
        }

        $producto = Producto::where('stock_actual', '>', 0)
            ->whereNotNull('fecha_vencimiento')
            ->orderBy('fecha_vencimiento')
            ->first();

        if (!$producto) {
            $producto = Producto::where('stock_actual', '>', 0)
                ->orderBy('nombre')
                ->first();
        }

        Entrega::create([
            'beneficiario_id' => $beneficiario->id,
            'user_id'         => 1,
            'producto_id'     => $producto?->id,
            'fecha_entrega'   => today(),
            'hora_entrega'    => now()->format('H:i:s'),
            'cantidad'        => (int) Configuracion::get('cantidad_default', 1),
        ]);

        if ($producto) {
            $producto->decrement('stock_actual', (int) Configuracion::get('cantidad_default', 1));
        }

        return redirect()->route('asistencia.bienvenida', $beneficiario->id);
    }

    public function bienvenidaBeneficiario($id)
    {
        $beneficiario = Beneficiario::findOrFail($id);
        $totalRaciones = \App\Models\Entrega::where('beneficiario_id', $id)->count();

       return view('publico.bienvenida', compact('beneficiario', 'totalRaciones'));
    }
}