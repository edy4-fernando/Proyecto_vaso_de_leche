<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'dni',
        'email',
        'password',
        'telefono',
        'rol',
        'estado_cuenta',
        'ultimo_ingreso',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'ultimo_ingreso'    => 'datetime',
        'estado_cuenta'     => 'boolean',
        'password'          => 'hashed',
    ];

    public function entregas(): HasMany
    {
        return $this->hasMany(Entrega::class, 'user_id');
    }

    public function esMaestro(): bool
    {
        return $this->rol === 'maestro';
    }
}