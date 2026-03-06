<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AsignacionCostoCompartido extends Model
{
    protected $table = 'asignaciones_costo_compartido';

    protected $fillable = [
        'recurso_compartido_id',
        'grupo_recursos_id',
        'porcentaje',
    ];

    protected function casts(): array
    {
        return [
            'porcentaje' => 'decimal:2',
        ];
    }

    public function recursoCompartido(): BelongsTo
    {
        return $this->belongsTo(RecursoCompartido::class, 'recurso_compartido_id');
    }

    public function grupoRecurso(): BelongsTo
    {
        return $this->belongsTo(GrupoRecurso::class, 'grupo_recursos_id');
    }
}
