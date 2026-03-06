<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departamento extends Model
{
    protected $table = 'departamentos';

    protected $fillable = [
        'nombre',
    ];

    public function gruposRecursos(): HasMany
    {
        return $this->hasMany(GrupoRecurso::class, 'departamento_id');
    }

    public function recursosCompartidos(): HasMany
    {
        return $this->hasMany(RecursoCompartido::class, 'departamento_id');
    }
}
