<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use App\Models\ActividadLog;

class ActividadLog extends Model
{
    protected $table = 'actividad_logs';

    protected $fillable = [
        'user_id',
        'accion',
        'descripcion',
        'ip',
        'metadata',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'metadata'   => 'array',
    ];

    /* ── Relación con usuario ── */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /* ── Registrar actividad estáticamente ── */
    public static function registrar(string $accion, string $descripcion, array $metadata = []): void
    {
        static::create([
            'user_id'     => Auth::id(),
            'accion'      => $accion,
            'descripcion' => $descripcion,
            'ip'          => request()->ip(),
            'metadata'    => !empty($metadata) ? $metadata : null,
        ]);
    }
}