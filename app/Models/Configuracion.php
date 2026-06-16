<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'configuraciones';

    protected $fillable = [
        'llave',
        'valor',
        'tipo',
        'grupo',
    ];

    public static function get(string $llave, mixed $default = null): mixed
    {
        $config = static::where('llave', $llave)->first();

        if (!$config || $config->valor === null) {
            return $default;
        }

        return match ($config->tipo) {
            'integer' => (int) $config->valor,
            'boolean' => filter_var($config->valor, FILTER_VALIDATE_BOOLEAN),
            'json'    => json_decode($config->valor, true),
            'date'    => \Carbon\Carbon::parse($config->valor),
            default   => (string) $config->valor,
        };
    }

    public static function set(string $llave, mixed $valor): static
    {
        $valorFinal = is_array($valor) || is_object($valor)
            ? json_encode($valor)
            : (string) $valor;

        return static::updateOrCreate(
            ['llave' => $llave],
            ['valor' => $valorFinal, 'updated_at' => now()]
        );
    }

    public function scopePorGrupo($query, string $grupo)
    {
        return $query->where('grupo', $grupo);
    }
}