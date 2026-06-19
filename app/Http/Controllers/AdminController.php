<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Beneficiario;
use App\Models\Entrega;
use App\Models\Producto;
use App\Models\User;
use App\Models\Configuracion;
use App\Models\ActividadLog;

class AdminController extends Controller
{
    /* ============================================================
       MIDDLEWARE — solo autenticados
       ============================================================ */
    

    /* ============================================================
       1. DASHBOARD — Monitoreo del día
       GET /admin
       ============================================================ */
    public function index()
    {
        $productos = Producto::where('stock_actual', '>', 0)
            ->orderBy('nombre')
            ->get();

        $entregasHoy = Entrega::with(['beneficiario', 'producto', 'user'])
            ->whereDate('fecha_entrega', today())
            ->orderByDesc('created_at')
            ->get();

        $totalHoy           = $entregasHoy->count();
        $totalBeneficiarios = Beneficiario::where('estado', true)->count();
        $stockCritico       = Producto::whereColumn('stock_actual', '<=', 'stock_minimo')->count();

        return view('admin.registrar-entrega', compact(
            'productos',
            'entregasHoy',
            'totalHoy',
            'totalBeneficiarios',
            'stockCritico'
        ));
    }

    /* ============================================================
       2. BENEFICIARIOS — Lista completa
       GET /admin/beneficiarios
       ============================================================ */
    public function listaBeneficiarios()
    {
        $beneficiarios = Beneficiario::orderBy('apellido')->get();

        $conRacionHoy = Entrega::whereDate('fecha_entrega', today())
            ->select('beneficiario_id')
            ->get();

        $productos = Producto::where('stock_actual', '>', 0)->get();

        return view('admin.beneficiarios', compact(
            'beneficiarios',
            'conRacionHoy',
            'productos'
        ));
    }

    /* ============================================================
       3. BENEFICIARIOS — Guardar nuevo
       POST /admin/beneficiarios/guardar
       ============================================================ */
    public function guardarBeneficiario(Request $request)
    {
        $request->validate([
            'dni'              => 'required|digits:8|unique:beneficiarios,dni',
            'nombre'           => 'required|string|max:100',
            'apellido'         => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'direccion'        => 'required|string|max:255',
            'tipo_beneficiario'=> 'required|string|max:50',
        ]);

        Beneficiario::create([
            'dni'                   => $request->dni,
            'nombre'                => strtoupper($request->nombre),
            'apellido'              => strtoupper($request->apellido),
            'fecha_nacimiento'      => $request->fecha_nacimiento,
            'direccion'             => strtoupper($request->direccion),
            'telefono'              => $request->telefono,
            'tipo_beneficiario'     => $request->tipo_beneficiario,
            'sector_o_comite'       => $request->sector_o_comite
                                        ? strtoupper($request->sector_o_comite)
                                        : null,
            'nombre_apoderado'      => $request->nombre_apoderado
                                        ? strtoupper($request->nombre_apoderado)
                                        : null,
            'dni_apoderado'         => $request->dni_apoderado,
            'observaciones_medicas' => $request->observaciones_medicas,
            'estado'                => true,
        ]);
        ActividadLog::registrar(
            'BENEFICIARIO_CREADO',
            'Se registró al beneficiario: ' . strtoupper($request->nombre) . ' ' . strtoupper($request->apellido) . ' (DNI: ' . $request->dni . ')'
        );
        return redirect()->route('admin.beneficiarios')
            ->with('success', 'Beneficiario registrado correctamente en el padrón.');
    }

    /* ============================================================
       4. BENEFICIARIOS — Perfil
       GET /admin/beneficiarios/{id}/perfil
       ============================================================ */
    public function perfilBeneficiario($id)
    {
        $beneficiario = Beneficiario::findOrFail($id);

        $entregas = Entrega::with(['producto', 'user'])
            ->where('beneficiario_id', $id)
            ->orderByDesc('fecha_entrega')
            ->paginate(10);

        $totalRaciones    = Entrega::where('beneficiario_id', $id)->count();
        $racionesEsteMes  = Entrega::where('beneficiario_id', $id)
            ->whereMonth('fecha_entrega', now()->month)
            ->whereYear('fecha_entrega', now()->year)
            ->count();
        $ultimaEntrega    = Entrega::where('beneficiario_id', $id)
            ->orderByDesc('fecha_entrega')
            ->first();

        return view('admin.perfil-beneficiario', compact(
            'beneficiario',
            'entregas',
            'totalRaciones',
            'racionesEsteMes',
            'ultimaEntrega'
        ));
    }

    /* ============================================================
       5. BENEFICIARIOS — Editar (form)
       GET /admin/beneficiarios/{id}/editar
       ============================================================ */
    public function editarBeneficiario($id)
    {
        $beneficiario = Beneficiario::findOrFail($id);
        return view('admin.editar-beneficiario', compact('beneficiario'));
    }

    /* ============================================================
       6. BENEFICIARIOS — Actualizar
       PUT /admin/beneficiarios/{id}/actualizar
       ============================================================ */
    public function actualizarBeneficiario(Request $request, $id)
    {
        $beneficiario = Beneficiario::findOrFail($id);

        $request->validate([
            'dni'              => 'required|digits:8|unique:beneficiarios,dni,' . $id,
            'nombre'           => 'required|string|max:100',
            'apellido'         => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'direccion'        => 'required|string|max:255',
            'tipo_beneficiario'=> 'required|string|max:50',
        ]);

        $beneficiario->update([
            'dni'                   => $request->dni,
            'nombre'                => strtoupper($request->nombre),
            'apellido'              => strtoupper($request->apellido),
            'fecha_nacimiento'      => $request->fecha_nacimiento,
            'direccion'             => strtoupper($request->direccion),
            'telefono'              => $request->telefono,
            'tipo_beneficiario'     => $request->tipo_beneficiario,
            'sector_o_comite'       => $request->sector_o_comite
                                        ? strtoupper($request->sector_o_comite)
                                        : null,
            'nombre_apoderado'      => $request->nombre_apoderado
                                        ? strtoupper($request->nombre_apoderado)
                                        : null,
            'dni_apoderado'         => $request->dni_apoderado,
            'observaciones_medicas' => $request->observaciones_medicas,
        ]);
        // Capturar cambios antes de guardar
        $etiquetas = [
            'nombre'               => 'Nombre',
            'apellido'             => 'Apellido',
            'dni'                  => 'DNI',
            'fecha_nacimiento'     => 'Fecha nacimiento',
            'direccion'            => 'Dirección',
            'telefono'             => 'Teléfono',
            'tipo_beneficiario'    => 'Tipo',
            'sector_o_comite'      => 'Sector / Comité',
            'nombre_apoderado'     => 'Apoderado',
            'dni_apoderado'        => 'DNI apoderado',
            'observaciones_medicas'=> 'Obs. médicas',
        ];

        $cambios = [];
        foreach ($etiquetas as $campo => $label) {
            $viejo = $beneficiario->$campo ?? '';
            $nuevo = $request->$campo ?? '';
            if (strtolower(trim((string)$viejo)) !== strtolower(trim((string)$nuevo))) {
                $cambios[] = [
                    'campo'  => $label,
                    'antes'  => $viejo ?: '—',
                    'despues'=> $nuevo ?: '—',
                ];
            }
        }

        ActividadLog::registrar(
            'BENEFICIARIO_EDITADO',
            'Se modificaron datos de: ' . strtoupper($request->nombre) . ' ' . strtoupper($request->apellido) . ' (DNI: ' . $request->dni . ')',
            ['cambios' => $cambios]
        );

        return redirect()->route('admin.beneficiarios')
            ->with('success', 'Datos del beneficiario actualizados correctamente.');
    }

    /* ============================================================
       7. BENEFICIARIOS — Eliminar
       DELETE /admin/beneficiarios/{id}/eliminar
       Solo maestro
       ============================================================ */
    public function eliminarBeneficiario($id)
    {
        if (Auth::user()->rol !== 'maestro') {
            return redirect()->back()->with('error', 'No tiene permisos para esta acción.');
        }

        $beneficiario = Beneficiario::findOrFail($id);
        ActividadLog::registrar(
            'BENEFICIARIO_ELIMINADO',
            'Se eliminó permanentemente a: ' . $beneficiario->nombre . ' ' . $beneficiario->apellido . ' (DNI: ' . $beneficiario->dni . ')'
        );
        
        $beneficiario->delete();

        return redirect()->route('admin.beneficiarios')
            ->with('success', 'Beneficiario enviado a la papelera. Puede restaurarlo desde Administración → Papelera.');
    }

    /* ============================================================
       8. BENEFICIARIOS — Toggle estado (activo/baja)
       PATCH /admin/beneficiarios/{id}/toggle-estado
       Solo maestro
       ============================================================ */
    public function toggleEstadoBeneficiario($id)
    {
        if (Auth::user()->rol !== 'maestro') {
            return redirect()->back()->with('error', 'No tiene permisos para esta acción.');
        }

        $beneficiario = Beneficiario::findOrFail($id);
        $beneficiario->update(['estado' => !$beneficiario->estado]);
        ActividadLog::registrar(
            $beneficiario->estado ? 'BENEFICIARIO_REACTIVADO' : 'BENEFICIARIO_DADO_DE_BAJA',
            ($beneficiario->estado ? 'Se reactivó' : 'Se dio de baja') . ' a: ' . $beneficiario->nombre . ' ' . $beneficiario->apellido . ' (DNI: ' . $beneficiario->dni . ')'
        );

        $msg = $beneficiario->estado
            ? 'Beneficiario reactivado en el padrón.'
            : 'Beneficiario dado de baja del padrón.';

        return redirect()->back()->with('success', $msg);
    }

    /* ============================================================
       9. USUARIOS — Lista
       GET /admin/usuarios
       Solo maestro
       ============================================================ */
    public function listaUsuarios()
    {
        if (Auth::user()->rol !== 'maestro') {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Acceso restringido.');
        }

        $usuarios = User::orderBy('name')->get();
        return view('admin.usuarios', compact('usuarios'));
    }

    /* ============================================================
       10. USUARIOS — Guardar nuevo
       POST /admin/usuarios/guardar
       Solo maestro
       ============================================================ */
    public function guardarUsuario(Request $request)
    {
        if (Auth::user()->rol !== 'maestro') {
            return redirect()->back()->with('error', 'No tiene permisos.');
        }

        $request->validate([
            'name'     => 'required|string|max:255',
            'dni'      => 'nullable|digits:8',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'rol'      => 'required|in:maestro,trabajador',
        ]);

        User::create([
            'name'         => strtoupper($request->name),
            'dni'          => $request->dni,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'telefono'     => $request->telefono,
            'rol'          => $request->rol,
            'estado_cuenta'=> true,
        ]);
        ActividadLog::registrar(
            'USUARIO_CREADO',
            'Se creó el usuario: ' . strtoupper($request->name) . ' (' . $request->email . ') con rol: ' . $request->rol
        );

        return redirect()->route('admin.usuarios')
            ->with('success', 'Usuario creado correctamente.');
    }

    /* ============================================================
       11. USUARIOS — Eliminar
       DELETE /admin/usuarios/eliminar/{id}
       Solo maestro
       ============================================================ */
    public function eliminarUsuario($id)
    {
        if (Auth::user()->rol !== 'maestro') {
            return redirect()->back()->with('error', 'No tiene permisos.');
        }

        if ((int)$id === Auth::id()) {
            return redirect()->back()
                ->with('error', 'No puede eliminar su propia cuenta.');
        }
        $usuario = User::findOrFail($id);
        ActividadLog::registrar(
            'USUARIO_ELIMINADO',
            'Se eliminó el usuario: ' . $usuario->name . ' (' . $usuario->email . ')'
        );
        $usuario->delete();
        // User::findOrFail($id)->delete();

        return redirect()->route('admin.usuarios')
            ->with('success', 'Usuario eliminado del sistema.');
    }
    /* ============================================================
       12. ENTREGAS — Guardar
       POST /admin/entregas/guardar
       ============================================================ */
    public function guardarEntrega(Request $request)
    {
        $request->validate([
            'dni'           => 'required|digits:8',
            'cantidad'      => 'required|integer|min:1',
            'fecha_entrega' => 'required|date',
        ]);

        $beneficiario = Beneficiario::where('dni', $request->dni)
            ->where('estado', true)
            ->first();

        if (!$beneficiario) {
            return redirect()->back()
                ->with('error', 'DNI no encontrado o beneficiario inactivo.');
        }

        // Verificar si ya recibió ración hoy
        $limite = (int) Configuracion::get('limite_entregas_diarias', 1);
        $entregasHoy = Entrega::where('beneficiario_id', $beneficiario->id)
            ->whereDate('fecha_entrega', $request->fecha_entrega)
            ->count();

        if ($entregasHoy >= $limite && Auth::user()->rol !== 'maestro') {
            return redirect()->back()
                ->with('warning', 'Este beneficiario ya recibió su ración hoy. Solo el maestro puede forzar una segunda entrega.');
        }

        // Descontar stock si se seleccionó producto
        if ($request->producto_id) {
            $producto = Producto::find($request->producto_id);
            if ($producto && $producto->stock_actual >= $request->cantidad) {
                $producto->decrement('stock_actual', $request->cantidad);
            } elseif ($producto) {
                return redirect()->back()
                    ->with('error', 'Stock insuficiente para el producto seleccionado.');
            }
        }

        Entrega::create([
            'beneficiario_id'           => $beneficiario->id,
            'user_id'                   => Auth::id(),
            'producto_id'               => $request->producto_id ?: null,
            'fecha_entrega'             => $request->fecha_entrega,
            'hora_entrega'              => now()->format('H:i:s'),
            'cantidad'                  => $request->cantidad,
            'observaciones_incidencias' => $request->observaciones_incidencias,
        ]);
        ActividadLog::registrar(
            'ENTREGA_REGISTRADA',
            'Entrega registrada para: ' . $beneficiario->nombre . ' ' . $beneficiario->apellido . ' (DNI: ' . $beneficiario->dni . ')'
        );

        return redirect()->route('admin.dashboard')
            ->with('success', 'Entrega registrada correctamente para ' . $beneficiario->nombre . ' ' . $beneficiario->apellido . '.');
    }

    /* ============================================================
       13. ENTREGAS — Historial
       GET /admin/entregas
       ============================================================ */
    public function historialEntregas(Request $request)
    {
        $query = Entrega::with(['beneficiario', 'producto', 'user'])
            ->orderByDesc('fecha_entrega')
            ->orderByDesc('created_at');

        // Búsqueda por nombre/DNI
        if ($request->q) {
            $q = $request->q;
            $query->whereHas('beneficiario', function ($sub) use ($q) {
                $sub->where('nombre', 'like', "%$q%")
                    ->orWhere('apellido', 'like', "%$q%")
                    ->orWhere('dni', 'like', "%$q%");
            });
        }

        // Rango de fechas
        if ($request->desde) {
            $query->whereDate('fecha_entrega', '>=', $request->desde);
        }
        if ($request->hasta) {
            $query->whereDate('fecha_entrega', '<=', $request->hasta);
        }

        // Filtro por producto específico
        if ($request->producto_id) {
            if ($request->producto_id === 'general') {
                $query->whereNull('producto_id');
            } else {
                $query->where('producto_id', $request->producto_id);
            }
        }

        // Filtro por tipo de beneficiario
        if ($request->tipo_beneficiario) {
            $query->whereHas('beneficiario', function ($sub) use ($request) {
                $sub->where('tipo_beneficiario', $request->tipo_beneficiario);
            });
        }

        // Filtro por operador
        if ($request->operador_id) {
            $query->where('user_id', $request->operador_id);
        }

        $entregas  = $query->paginate(20)->withQueryString();
        $productos = Producto::orderBy('nombre')->get();
        $operadores = User::orderBy('name')->get();

        return view('admin.entregas', compact('entregas', 'productos', 'operadores'));
    }

    /* ============================================================
       14. PRODUCTOS — Lista
       GET /admin/productos
       ============================================================ */
    public function listaProductos()
    {
        $productos = Producto::orderBy('nombre')->get();
        return view('admin.productos', compact('productos'));
    }

    /* ============================================================
       15. PRODUCTOS — Guardar nuevo
       POST /admin/productos/guardar
       ============================================================ */
    public function guardarProducto(Request $request)
    {
        if (Auth::user()->rol !== 'maestro') {
            return redirect()->back()->with('error', 'No tiene permisos.');
        }

        $request->validate([
            'nombre'       => 'required|string|max:150',
            'tipo_insumo'  => 'required|string|max:100',
            'stock_actual' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'unidad_medida'=> 'required|string|max:50',
        ]);

        Producto::create([
            'tipo_insumo'       => $request->tipo_insumo,
            'nombre'            => strtoupper($request->nombre),
            'marca'             => $request->marca ? strtoupper($request->marca) : null,
            'numero_lote'       => $request->numero_lote,
            'fecha_vencimiento' => $request->fecha_vencimiento ?: null,
            'unidad_medida'     => $request->unidad_medida,
            'stock_actual'      => $request->stock_actual,
            'stock_minimo'      => $request->stock_minimo,
        ]);

        return redirect()->route('admin.productos')
            ->with('success', 'Producto agregado al inventario.');
    }

    /* ============================================================
       16. PRODUCTOS — Eliminar
       DELETE /admin/productos/{id}/eliminar
       ============================================================ */
    public function eliminarProducto($id)
    {
        if (Auth::user()->rol !== 'maestro') {
            return redirect()->back()->with('error', 'No tiene permisos.');
        }
        $prod = Producto::findOrFail($id);
        ActividadLog::registrar(
            'PRODUCTO_ELIMINADO',
            'Se eliminó el producto: ' . $prod->nombre
        );
        $prod->delete();
        Producto::findOrFail($id)->delete();

        return redirect()->route('admin.productos')
            ->with('success', 'Producto eliminado del inventario.');
    }

    /* ============================================================
       17. PRODUCTOS — Reabastecer stock
       POST /admin/productos/{id}/reabastecer
       ============================================================ */
    public function reabastecerProducto(Request $request, $id)
    {
        if (Auth::user()->rol !== 'maestro') {
            return redirect()->back()->with('error', 'No tiene permisos.');
        }

        $request->validate([
            'cantidad' => 'required|integer|min:1',
        ]);

        $producto = Producto::findOrFail($id);
        $producto->increment('stock_actual', $request->cantidad);
        ActividadLog::registrar(
            'STOCK_REABASTECIDO',
            'Se agregaron ' . $request->cantidad . ' ' . $producto->unidad_medida . ' a: ' . $producto->nombre
        );

        return redirect()->route('admin.productos')
            ->with('success', 'Stock actualizado: +' . $request->cantidad . ' ' . $producto->unidad_medida . ' para ' . $producto->nombre . '.');
    }


    /* ============================================================
       19. CAMBIAR CONTRASEÑA
       POST /perfil/cambiar-password
       ============================================================ */
    public function cambiarPassword(Request $request)
    {
        $request->validate([
            'password_actual'              => 'required',
            'password_nuevo'               => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password_actual, $user->password)) {
            return redirect()->back()
                ->with('error', 'La contraseña actual no es correcta.');
        }

        $user->update([
            'password' => Hash::make($request->password_nuevo),
        ]);

        return redirect()->back()
            ->with('success', 'Contraseña actualizada correctamente.');
    }
    public function cuenta()
    {
        return view('configuracion.cuenta');
    }
    /* ============================================================
       PAPELERA — Solo maestro
       GET /admin/papelera
       ============================================================ */
    public function papelera()
    {
        if (Auth::user()->rol !== 'maestro') {
            return redirect()->route('admin.dashboard')->with('error', 'Acceso restringido.');
        }

        $beneficiarios = Beneficiario::onlyTrashed()->orderByDesc('deleted_at')->get();
        $productos     = Producto::onlyTrashed()->orderByDesc('deleted_at')->get();
        $entregas      = Entrega::onlyTrashed()
                            ->with(['beneficiario' => fn($q) => $q->withTrashed(),
                                    'producto'     => fn($q) => $q->withTrashed(),
                                    'user'])
                            ->orderByDesc('deleted_at')->get();

        return view('admin.papelera', compact('beneficiarios', 'productos', 'entregas'));
    }

    /* ── Restaurar beneficiario ── */
    public function restaurarBeneficiario($id)
    {
        if (Auth::user()->rol !== 'maestro') {
            return redirect()->back()->with('error', 'Acceso restringido.');
        }
        $b = Beneficiario::onlyTrashed()->findOrFail($id);
        $b->restore();
        ActividadLog::registrar(
            'BENEFICIARIO_RESTAURADO',
            'Se restauró desde la papelera a: ' . $b->nombre . ' ' . $b->apellido . ' (DNI: ' . $b->dni . ')'
        );
        return redirect()->route('admin.papelera')->with('success', $b->nombre . ' ' . $b->apellido . ' restaurado correctamente.');
    }

    /* ── Restaurar producto ── */
    public function restaurarProducto($id)
    {
        if (Auth::user()->rol !== 'maestro') {
            return redirect()->back()->with('error', 'Acceso restringido.');
        }
        $p = Producto::onlyTrashed()->findOrFail($id);
        $p->restore();
        ActividadLog::registrar(
            'PRODUCTO_RESTAURADO',
            'Se restauró desde la papelera el producto: ' . $p->nombre
        );
        return redirect()->route('admin.papelera')->with('success', 'Producto "' . $p->nombre . '" restaurado correctamente.');
    }

    /* ── Restaurar entrega ── */
    public function restaurarEntrega($id)
    {
        if (Auth::user()->rol !== 'maestro') {
            return redirect()->back()->with('error', 'Acceso restringido.');
        }
        $e = Entrega::onlyTrashed()->findOrFail($id);
        $e->restore();
        ActividadLog::registrar(
            'ENTREGA_RESTAURADA',
            'Se restauró desde la papelera una entrega del ' . \Carbon\Carbon::parse($e->fecha_entrega)->format('d/m/Y')
        );
        return redirect()->route('admin.papelera')->with('success', 'Entrega restaurada correctamente.');
    }
    /* ============================================================
       MI ACTIVIDAD — Historial personal (cualquier rol, solo lectura)
       GET /admin/mi-actividad
       ============================================================ */
    public function miActividad()
    {
        $actividad = \App\Models\ActividadLog::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.mi-actividad', compact('actividad'));
    }

    /* ============================================================
       19b. ACTUALIZAR CORREO ELECTRÓNICO
       POST /perfil/actualizar-email
       ============================================================ */
    public function actualizarEmail(Request $request)
    {
        $request->validate([
            'email_nuevo'     => 'required|email|unique:users,email,' . Auth::id(),
            'password_actual' => 'required',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password_actual, $user->password)) {
            return redirect()->back()
                ->with('error', 'La contraseña actual no es correcta.');
        }

        $emailAnterior = $user->email;

        $user->update([
            'email' => $request->email_nuevo,
        ]);

        ActividadLog::registrar(
            'EMAIL_ACTUALIZADO',
            'El usuario ' . $user->name . ' cambió su correo de ' . $emailAnterior . ' a ' . $request->email_nuevo
        );

        return redirect()->back()
            ->with('success', 'Correo electrónico actualizado correctamente.');
    }
    /* ============================================================
       20. DASHBOARD NUEVO — Estadísticas generales
       GET /dashboard
       ============================================================ */
    public function dashboardNuevo()
    {
        // ── Rango de fechas desde filtro ──
        $desde = request('desde')
            ? \Carbon\Carbon::parse(request('desde'))->startOfDay()
            : now()->subDays(30)->startOfDay();
        $hasta = request('hasta')
            ? \Carbon\Carbon::parse(request('hasta'))->endOfDay()
            : now()->endOfDay();

        // ── Totales generales ──
        $totalBeneficiarios = Beneficiario::count();
        $totalActivos       = Beneficiario::where('estado', true)->count();
        $totalInactivos     = Beneficiario::where('estado', false)->count();
        $totalEntregas      = Entrega::count();
        $totalProductos     = Producto::count();
        $stockCritico       = Producto::whereColumn('stock_actual', '<=', 'stock_minimo')->count();
        $entregasHoy        = Entrega::whereDate('fecha_entrega', today())->count();

        // ── Entregas últimos 7 días ──
        $ultimos7Dias = collect(range(6, 0))->map(function ($i) {
            $fecha = now()->subDays($i);
            return [
                'fecha' => $fecha->format('d/m'),
                'total' => Entrega::whereDate('fecha_entrega', $fecha)->count(),
                'dia'   => $fecha->isoFormat('ddd'),
            ];
        });

        // ── Entregas últimos 6 meses ──
        $ultimos6Meses = collect(range(5, 0))->map(function ($i) {
            $fecha = now()->subMonths($i);
            return [
                'mes'   => $fecha->isoFormat('MMM'),
                'total' => Entrega::whereMonth('fecha_entrega', $fecha->month)
                                ->whereYear('fecha_entrega', $fecha->year)
                                ->count(),
            ];
        });

        // ── Entregas acumuladas por mes (últimos 12 meses) ──
        $acumulado = collect(range(11, 0))->map(function ($i) {
            $fecha = now()->subMonths($i);
            return [
                'mes'   => $fecha->isoFormat('MMM'),
                'total' => Entrega::whereMonth('fecha_entrega', $fecha->month)
                                ->whereYear('fecha_entrega', $fecha->year)
                                ->count(),
            ];
        });

        // ── Entregas por día de la semana ──
        $porDiaSemana = collect(range(1, 7))->map(function ($dia) {
            $nombres = [1=>'Lun',2=>'Mar',3=>'Mié',4=>'Jue',5=>'Vie',6=>'Sáb',7=>'Dom'];
            return [
                'dia'   => $nombres[$dia],
                'total' => Entrega::whereRaw('DAYOFWEEK(fecha_entrega) = ?',
                            [$dia === 7 ? 1 : $dia + 1])->count(),
            ];
        });

        // ── Beneficiarios por tipo ──
        $porTipo = Beneficiario::selectRaw('tipo_beneficiario, count(*) as total')
            ->groupBy('tipo_beneficiario')
            ->get();

        // ── Beneficiarios por sector ──
        $porSector = Beneficiario::selectRaw('sector_o_comite, count(*) as total')
            ->whereNotNull('sector_o_comite')
            ->groupBy('sector_o_comite')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        // ── Top 5 beneficiarios con más raciones ──
        $topBeneficiarios = Entrega::selectRaw('beneficiario_id, count(*) as total')
            ->with('beneficiario')
            ->groupBy('beneficiario_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // ── Productos más usados ──
        $topProductos = Entrega::selectRaw('producto_id, count(*) as total')
            ->with('producto')
            ->whereNotNull('producto_id')
            ->groupBy('producto_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // ── Entregas por operador ──
        $porOperador = Entrega::selectRaw('user_id, count(*) as total')
            ->with('user')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->get();

        // ── Este mes vs anterior ──
        $esteMes     = Entrega::whereMonth('fecha_entrega', now()->month)
                            ->whereYear('fecha_entrega', now()->year)->count();
        $mesAnterior = Entrega::whereMonth('fecha_entrega', now()->subMonth()->month)
                            ->whereYear('fecha_entrega', now()->subMonth()->year)->count();

        // ── Stock productos ──
        $stockProductos = Producto::orderByDesc('stock_actual')->limit(8)->get();

        // ── Vencimientos próximos 90 días ──
        $vencimientoTimeline = Producto::whereNotNull('fecha_vencimiento')
            ->whereDate('fecha_vencimiento', '>=', now())
            ->whereDate('fecha_vencimiento', '<=', now()->addDays(90))
            ->orderBy('fecha_vencimiento')
            ->get();

        // ── Cobertura del programa (%) ──
        $coberturaActual = $totalActivos > 0
            ? min(round(($esteMes / $totalActivos) * 100), 100)
            : 0;

        // ── Entregas en rango seleccionado ──
        $entregasRango = Entrega::whereBetween('created_at', [$desde, $hasta])->count();

        return view('dashboard.estadisticas.index', compact(
            'totalBeneficiarios', 'totalActivos', 'totalInactivos',
            'totalEntregas', 'totalProductos', 'stockCritico', 'entregasHoy',
            'ultimos7Dias', 'ultimos6Meses', 'acumulado', 'porDiaSemana',
            'porTipo', 'porSector', 'topBeneficiarios', 'topProductos',
            'porOperador', 'esteMes', 'mesAnterior', 'stockProductos',
            'vencimientoTimeline', 'coberturaActual', 'entregasRango',
            'desde', 'hasta'
        ));
    }

    /* ============================================================
       21. DASHBOARD — Estadísticas
       GET /dashboard/estadisticas
       ============================================================ */
    public function dashboardEstadisticas()
    {
        return redirect()->route('admin.dashboard');
    }

    /* ============================================================
       22. DASHBOARD — Historial
       GET /dashboard/historial
       ============================================================ */
    public function dashboardHistorial()
    {
        $query = Entrega::with(['beneficiario', 'producto', 'user'])
            ->orderByDesc('fecha_entrega')
            ->orderByDesc('created_at');

        if (request()->q) {
            $q = request()->q;
            $query->whereHas('beneficiario', function ($sub) use ($q) {
                $sub->where('nombre', 'like', "%$q%")
                    ->orWhere('apellido', 'like', "%$q%")
                    ->orWhere('dni', 'like', "%$q%");
            });
        }

        if (request()->desde) {
            $query->whereDate('fecha_entrega', '>=', request()->desde);
        }

        if (request()->hasta) {
            $query->whereDate('fecha_entrega', '<=', request()->hasta);
        }

        $entregas = $query->paginate(20);

        return view('dashboard.historial', compact('entregas'));
    }
    /* ============================================================
       23. DASHBOARD — Alertas
       GET /dashboard/alertas
       ============================================================ */
    public function dashboardAlertas()
    {
        $stockCritico = Producto::whereColumn('stock_actual', '<=', 'stock_minimo')
            ->orderBy('stock_actual')
            ->get();

        $porVencer = Producto::whereNotNull('fecha_vencimiento')
            ->whereDate('fecha_vencimiento', '>=', now())
            ->whereDate('fecha_vencimiento', '<=', now()->addDays(30))
            ->orderBy('fecha_vencimiento')
            ->get();

        $vencidos = Producto::whereNotNull('fecha_vencimiento')
            ->whereDate('fecha_vencimiento', '<', now())
            ->orderBy('fecha_vencimiento')
            ->get();

        return view('dashboard.alertas', compact(
            'stockCritico',
            'porVencer',
            'vencidos'
        ));
    }

    /* ============================================================
       24. DASHBOARD — Reportes
       GET /dashboard/reportes
       ============================================================ */
    public function dashboardReportes()
    {
        return redirect()->route('admin.entregas');
    }

    /* ============================================================
       25. DASHBOARD — Actividad
       GET /dashboard/actividad
       ============================================================ */
    public function dashboardActividad()
    {
        $query = \App\Models\ActividadLog::with('user')
            ->orderByDesc('created_at');

        if (request()->q) {
            $q = request()->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('accion', 'like', "%$q%")
                    ->orWhere('descripcion', 'like', "%$q%");
            });
        }

        if (request()->accion) {
            $query->where('accion', request()->accion);
        }

        if (request()->desde) {
            $query->whereDate('created_at', '>=', request()->desde);
        }

        if (request()->hasta) {
            $query->whereDate('created_at', '<=', request()->hasta);
        }

        // Filtro por operador
        if (request()->operador_id) {
            $query->where('user_id', request()->operador_id);
        }

        $actividad        = $query->paginate(25);
        $totalActividad   = \App\Models\ActividadLog::count();
        $actividadHoy     = \App\Models\ActividadLog::whereDate('created_at', today())->count();
        $usuariosActivos  = \App\Models\ActividadLog::whereDate('created_at', today())
                             ->distinct('user_id')->count('user_id');
        $accionesCriticas = \App\Models\ActividadLog::whereIn('accion', [
            'BENEFICIARIO_ELIMINADO',
            'USUARIO_ELIMINADO',
            'PRODUCTO_ELIMINADO',
            'BENEFICIARIO_DADO_DE_BAJA',
        ])->whereDate('created_at', today())->count();
        $operadores = \App\Models\User::orderBy('name')->get();

        return view('dashboard.actividad', compact(
            'actividad',
            'totalActividad',
            'actividadHoy',
            'usuariosActivos',
            'accionesCriticas',
            'operadores'
        ));
    }

    /* ============================================================
       26. DASHBOARD — Configuración
       GET /dashboard/configuracion
       ============================================================ */
    public function dashboardConfiguracion()
    {
        if (Auth::user()->rol !== 'maestro') {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Acceso restringido.');
        }
        return view('configuracion.sistema');
    }

    public function guardarConfiguracion(Request $request)
    {
        if (Auth::user()->rol !== 'maestro') {
            return redirect()->back()->with('error', 'Acceso restringido.');
        }

        $campos = $request->except(['_token', '_method', 'grupo']);
        foreach ($campos as $llave => $valor) {
            \App\Models\Configuracion::set($llave, $valor);
        }

        return redirect()->route('dashboard.configuracion')
            ->with('success', 'Configuración guardada correctamente.');
    }
    

}
    