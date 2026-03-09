<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AsignacionRecursoGrupo extends Model
{
    protected $table = 'asignaciones_recursos_grupos';

    protected $fillable = [
        'recurso_id',
        'grupo_recursos_id',
        'porcentaje',
    ];

    protected function casts(): array
    {
        return [
            'porcentaje' => 'decimal:2',
        ];
    }

    public function recurso(): BelongsTo
    {
        return $this->belongsTo(Recurso::class, 'recurso_id');
    }

    public function grupoRecurso(): BelongsTo
    {
        return $this->belongsTo(GrupoRecurso::class, 'grupo_recursos_id');
    }
}
