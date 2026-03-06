<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Actividad extends Model
{
    protected $table = 'actividades';

    protected $fillable = [
        'grupo_recursos_id',
        'nombre',
        'tiempo_base_minutos',
    ];

    protected function casts(): array
    {
        return [
            'tiempo_base_minutos' => 'decimal:2',
        ];
    }

    public function grupoRecurso(): BelongsTo
    {
        return $this->belongsTo(GrupoRecurso::class, 'grupo_recursos_id');
    }

    public function inductoresTiempo(): HasMany
    {
        return $this->hasMany(ActividadInductorTiempo::class, 'actividad_id');
    }

    public function productoActividades(): HasMany
    {
        return $this->hasMany(ProductoActividad::class, 'actividad_id');
    }
}
