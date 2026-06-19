<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Beneficiario extends Model
{
    use SoftDeletes;
    protected $table = 'beneficiarios';

    protected $fillable = [
        'dni',
        'nombre',
        'apellido',
        'fecha_nacimiento',
        'direccion',
        'telefono',
        'tipo_beneficiario',
        'sector_o_comite',
        'nombre_apoderado',
        'dni_apoderado',
        'estado',
        'observaciones_medicas',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'estado'           => 'boolean',
    ];

    public function scopeActivos($query)
    {
        return $query->where('estado', true);
    }

    public function getNombreCompletoAttribute(): string
    {
        return $this->nombre . ' ' . $this->apellido;
    }

    public function getEdadAttribute(): int
    {
        return $this->fecha_nacimiento->age;
    }

    public function entregas(): HasMany
    {
        return $this->hasMany(Entrega::class, 'beneficiario_id');
    }
}