<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entrega extends Model
{
    use SoftDeletes;
    protected $table = 'entregas';

    protected $fillable = [
        'beneficiario_id',
        'user_id',
        'producto_id',
        'fecha_entrega',
        'hora_entrega',
        'cantidad',
        'observaciones_incidencias',
    ];

    protected $casts = [
        'fecha_entrega' => 'date',
        'cantidad'      => 'integer',
    ];

    public function scopeDeHoy($query)
    {
        return $query->whereDate('fecha_entrega', today());
    }

    public function beneficiario(): BelongsTo
    {
        return $this->belongsTo(Beneficiario::class, 'beneficiario_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}