<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductoActividadValorInductor extends Model
{
    protected $table = 'producto_actividad_valores_inductor';

    protected $fillable = [
        'producto_actividad_id',
        'inductor_tiempo_id',
        'valor_x',
        'beta_minutos',
        'tamano_lote',
    ];

    protected function casts(): array
    {
        return [
            'valor_x'      => 'decimal:2',
            'beta_minutos' => 'decimal:4',
            'tamano_lote'  => 'decimal:2',
        ];
    }

    public function productoActividad(): BelongsTo
    {
        return $this->belongsTo(ProductoActividad::class, 'producto_actividad_id');
    }

    public function inductorTiempo(): BelongsTo
    {
        return $this->belongsTo(InductorTiempo::class, 'inductor_tiempo_id');
    }
}
