<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActividadInductorTiempo extends Model
{
    protected $table = 'actividad_inductores_tiempo';

    protected $fillable = [
        'actividad_id',
        'inductor_tiempo_id',
        'beta_minutos',
        'tamano_lote',
    ];

    protected function casts(): array
    {
        return [
            'beta_minutos' => 'decimal:4',
            'tamano_lote'  => 'decimal:2',
        ];
    }

    public function actividad(): BelongsTo
    {
        return $this->belongsTo(Actividad::class, 'actividad_id');
    }

    public function inductorTiempo(): BelongsTo
    {
        return $this->belongsTo(InductorTiempo::class, 'inductor_tiempo_id');
    }
}
