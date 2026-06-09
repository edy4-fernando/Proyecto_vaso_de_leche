<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    use HasFactory;

    // Campos que permitimos registrar en bloque
    protected $fillable = [
        'beneficiario_id',
        'user_id',
        'fecha_entrega',
        'productos_entregados'
    ];

    /**
     * Relación con el Beneficiario que recibe la ración
     */
    public function beneficiario()
    {
        return $this->belongsTo(Beneficiario::class, 'beneficiario_id');
    }

    /**
     * Relación con el Usuario/Admin que registró la entrega
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * ✨ ESTA ES LA RELACIÓN QUE FALTA Y CAUSA EL ERROR
     * Vincula la entrega con la tabla de productos si tu controlador la invoca
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id'); // Enlaza de manera segura
    }
}