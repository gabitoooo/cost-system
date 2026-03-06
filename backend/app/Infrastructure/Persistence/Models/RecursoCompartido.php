<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RecursoCompartido extends Model
{
    protected $table = 'recursos_compartidos';

    protected $fillable = [
        'departamento_id',
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

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }

    public function asignaciones(): HasMany
    {
        return $this->hasMany(AsignacionCostoCompartido::class, 'recurso_compartido_id');
    }
}
