<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beneficiario;
use App\Models\Entrega;
use App\Models\Producto; 
use App\Models\User; 
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; 

class AdminController extends Controller
{
    /**
     * Dashboard: Muestra el reporte de entregas de hoy
     */
    public function index()
    {
        $entregasHoy = Entrega::with(['beneficiario', 'producto'])
            ->whereDate('created_at', Carbon::today())
            ->latest()
            ->get();

        return view('admin.dashboard', compact('entregasHoy'));
    }

    /**
     * Inscribir un nuevo beneficiario
     */
    public function guardarBeneficiario(Request $request)
    {
        if (auth()->user()->rol !== 'maestro') {
            return back()->with('error', 'Operación denegada. Su rol no tiene permisos para inscribir beneficiarios.');
        }

        $request->validate([
            'dni' => 'required|numeric|digits:8|unique:beneficiarios,dni',
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'direccion' => 'required|string|max:255',
        ]);

        // MAPEO CORRECTO: Usamos campos en SINGULAR de tu Base de Datos real
        Beneficiario::create([
            'dni'              => $request->dni,
            'nombre'           => $request->nombres,       
            'apellido'         => $request->apellidos,   
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'direccion'        => $request->direccion,
            'estado'           => 1,
        ]);

        return redirect()->back()->with('success', '¡Beneficiario inscrito correctamente en el padrón!');
    }

    /**
     * CRUD: Lista todos los beneficiarios registrados
     */
    public function listaBeneficiarios()
    {
        // Ordenamos por 'apellido' en singular para evitar fallos de SQL
        $beneficiarios = Beneficiario::with('entregas')->orderBy('apellido', 'asc')->get();
        $productos = Producto::where('stock_actual', '>', 0)->get();

        return view('admin.beneficiarios', compact('beneficiarios', 'productos'));
    }

    /**
     * LOGÍSTICA: Procesa el despacho y descuenta el stock
     */
    public function guardarEntrega(Request $request)
    {
        if (auth()->user()->rol !== 'maestro') {
            return redirect()->back()->with('error', 'Acceso Denegado.');
        }

        $request->validate([
            'beneficiario_id'   => 'required|exists:beneficiarios,id',
            'producto_id'       => 'required|exists:productos,id',
            'cantidad_entregada'=> 'required|integer|min:1',
        ]);

        $producto = Producto::findOrFail($request->producto_id);

        if ($producto->stock_actual < $request->cantidad_entregada) {
            return redirect()->back()->with('error', "Quiebre de Stock: Solo quedan {$producto->stock_actual} unidades.");
        }

        DB::transaction(function () use ($request, $producto) {
            Entrega::create([
                'beneficiario_id'   => $request->beneficiario_id,
                'producto_id'       => $request->producto_id,
                'cantidad_entregada'=> $request->cantidad_entregada,
                'user_id'           => auth()->id(), 
            ]);

            $producto->decrement('stock_actual', $request->cantidad_entregada);
        });

        return redirect()->back()->with('success', '¡Ración oficial despachada y descontada con éxito!');
    }

    /**
     * CRUD: Formulario para editar un beneficiario
     */
    public function editarBeneficiario($id)
    {
        if (auth()->user()->rol !== 'maestro') {
            return redirect()->route('admin.beneficiarios')->with('error', 'No tiene permisos.');
        }

        $beneficiario = Beneficiario::findOrFail($id);
        return view('admin.editar_beneficiario', compact('beneficiario'));
    }

    /**
     * CRUD: Procesa la actualización en la base de datos
     */
    public function actualizarBeneficiario(Request $request, $id)
    {
        if (auth()->user()->rol !== 'maestro') {
            return redirect()->route('admin.beneficiarios')->with('error', 'No tiene permisos.');
        }

        $request->validate([
            'dni' => 'required|numeric|digits:8',
            'nombres' => 'required|string',
            'apellidos' => 'required|string',
            'direccion' => 'required|string',
        ]);

        $beneficiario = Beneficiario::findOrFail($id);
        
        $beneficiario->update([
            'dni' => $request->dni,
            'nombre' => $request->nombres,
            'apellido' => $request->apellidos,
            'direccion' => $request->direccion,
        ]);

        return redirect()->route('admin.beneficiarios')->with('success', 'Registro actualizado correctamente.');
    }

    /**
     * CRUD: Elimina un registro de la base de datos
     */
    public function eliminarBeneficiario($id)
    {
        if (auth()->user()->rol !== 'maestro') {
            return back()->with('error', 'Operación denegada.');
        }

        $beneficiario = Beneficiario::findOrFail($id);
        $beneficiario->delete();

        return back()->with('success', 'El beneficiario ha sido eliminado.');
    }

    // =========================================================================
    //   FUNCIONES DE GESTIÓN DE PERSONAL (SÓLO MAESTRO)
    // =========================================================================

    public function listaUsuarios()
    {
        if (auth()->user()->rol !== 'maestro') {
            return redirect()->route('admin.dashboard')->with('error', 'No tienes permisos.');
        }

        $usuarios = User::orderBy('name', 'asc')->get();
        return view('admin.usuarios', compact('usuarios'));
    }

    public function guardarUsuario(Request $request)
    {
        if (auth()->user()->rol !== 'maestro') {
            return back()->with('error', 'Operación denegada.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'rol' => 'required|in:maestro,trabajador'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
            'rol' => $request->rol
        ]);

        return back()->with('success', 'Nuevo personal administrativo registrado.');
    }

    public function eliminarUsuario($id)
    {
        if (auth()->user()->rol !== 'maestro') {
            return back()->with('error', 'Operación denegada.');
        }

        $usuario = User::findOrFail($id);

        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propio usuario de nivel Maestro.');
        }

        $usuario->delete();
        return back()->with('success', 'El acceso del usuario ha sido revocado.');
    }
}