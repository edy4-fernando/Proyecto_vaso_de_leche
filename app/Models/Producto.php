<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    // Nombre de la tabla en MySQL
    protected $table = 'productos';

    // Campos permitidos para llenado masivo
    protected $fillable = [
        'tipo_insumo', 
        'nombre', 
        'marca', 
        'numero_lote', 
        'fecha_vencimiento', 
        'unidad_medida', 
        'stock_actual', 
        'stock_minimo'
    ];

    /**
     * RELACIÓN: Un producto puede estar registrado en muchas entregas.
     */
    public function entregas()
    {
        return $this->hasMany(Entrega::class, 'producto_id');
    }
}