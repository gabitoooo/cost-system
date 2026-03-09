<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recurso extends Model
{
    protected $table = 'recursos';

    protected $fillable = [     
        'nombre',
        'tipo',
        'costo_mensual',
    ];

    protected function casts(): array
    {
        return [
            'costo_mensual' => 'decimal:2',
        ];
    }

    public function asignacionRecursoGrupo(): HasMany
    {
        return $this->hasMany(AsignacionRecursoGrupo::class, 'recurso_id');
    }
}
