<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleInstantaneaCosto extends Model
{
    protected $table = 'detalles_instantanea_costo';

    const UPDATED_AT = null;

    protected $fillable = [
        'instantanea_id',
        'actividad_id',
        'tiempo_consumido_min',
        'tasa_costo_por_minuto',
        'costo_actividad',
    ];

    protected function casts(): array
    {
        return [
            'tiempo_consumido_min'  => 'decimal:4',
            'tasa_costo_por_minuto' => 'decimal:4',
            'costo_actividad'       => 'decimal:4',
        ];
    }

    public function instantanea(): BelongsTo
    {
        return $this->belongsTo(InstantaneaCostoProducto::class, 'instantanea_id');
    }

    public function actividad(): BelongsTo
    {
        return $this->belongsTo(Actividad::class, 'actividad_id');
    }
}
