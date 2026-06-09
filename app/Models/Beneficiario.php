<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beneficiario extends Model
{
    protected $table = 'beneficiarios';
    
    protected $fillable = ['dni', 'nombre', 'apellido', 'fecha_nacimiento', 'direccion', 'estado'];

    // NUEVO: Conectamos al Beneficiario con todas sus Entregas (Asistencias)
    public function entregas()
    {
        return $this->hasMany(Entrega::class, 'beneficiario_id');
    }
}