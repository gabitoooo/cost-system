<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recurso extends Model
{
    protected $table = 'recursos';

    protected $fillable = [
        'grupo_recursos_id',
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

    public function grupoRecurso(): BelongsTo
    {
        return $this->belongsTo(GrupoRecurso::class, 'grupo_recursos_id');
    }
}
