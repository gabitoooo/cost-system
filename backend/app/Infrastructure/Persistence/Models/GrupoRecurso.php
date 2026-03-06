<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GrupoRecurso extends Model
{
    protected $table = 'grupos_recursos';

    protected $fillable = [
        'departamento_id',
        'nombre',
        'capacidad_practica_minutos',
        'tasa_costo_por_minuto',
    ];

    protected function casts(): array
    {
        return [
            'capacidad_practica_minutos' => 'decimal:2',
            'tasa_costo_por_minuto'      => 'decimal:4',
        ];
    }

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }

    public function recursos(): HasMany
    {
        return $this->hasMany(Recurso::class, 'grupo_recursos_id');
    }

    public function actividades(): HasMany
    {
        return $this->hasMany(Actividad::class, 'grupo_recursos_id');
    }

    public function asignacionesCostoCompartido(): HasMany
    {
        return $this->hasMany(AsignacionCostoCompartido::class, 'grupo_recursos_id');
    }
}
