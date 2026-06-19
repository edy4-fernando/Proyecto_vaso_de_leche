<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use SoftDeletes;
    protected $table = 'productos';

    protected $fillable = [
        'tipo_insumo',
        'nombre',
        'marca',
        'numero_lote',
        'fecha_vencimiento',
        'unidad_medida',
        'stock_actual',
        'stock_minimo',
    ];

    protected $casts = [
        'fecha_vencimiento' => 'date',
        'stock_actual'      => 'integer',
        'stock_minimo'      => 'integer',
    ];

    public function scopeConStock($query)
    {
        return $query->where('stock_actual', '>', 0);
    }

    public function scopeStockCritico($query)
    {
        return $query->whereColumn('stock_actual', '<=', 'stock_minimo');
    }

    public function scopeProximosAVencer($query, int $dias = 30)
    {
        return $query
            ->whereNotNull('fecha_vencimiento')
            ->whereDate('fecha_vencimiento', '>=', now())
            ->whereDate('fecha_vencimiento', '<=', now()->addDays($dias));
    }

    public function scopeVencidos($query)
    {
        return $query
            ->whereNotNull('fecha_vencimiento')
            ->whereDate('fecha_vencimiento', '<', now());
    }

    public function getEsStockCriticoAttribute(): bool
    {
        return $this->stock_actual <= $this->stock_minimo;
    }

    public function getEtiquetaAttribute(): string
    {
        return $this->marca
            ? $this->nombre . ' — ' . $this->marca
            : $this->nombre;
    }

    public function entregas(): HasMany
    {
        return $this->hasMany(Entrega::class, 'producto_id');
    }
}