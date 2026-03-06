<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogDeterminacionPrecio extends Model
{
    protected $table = 'log_determinacion_precio';

    // Tabla de auditoría sin timestamps de Laravel
    public $timestamps = false;

    protected $fillable = [
        'item_cotizacion_id',
        'regla_precio_id',
        'tramo_id',
        'tipo_calculo_aplicado',
        'costo_base_usado',
        'valor_regla_usado',
        'precio_calculado',
        'calculado_en',
    ];

    protected function casts(): array
    {
        return [
            'costo_base_usado'  => 'decimal:4',
            'valor_regla_usado' => 'decimal:4',
            'precio_calculado'  => 'decimal:4',
            'calculado_en'      => 'datetime',
        ];
    }

    public function itemCotizacion(): BelongsTo
    {
        return $this->belongsTo(ItemCotizacion::class, 'item_cotizacion_id');
    }

    public function reglaPrecio(): BelongsTo
    {
        return $this->belongsTo(ReglaPrecio::class, 'regla_precio_id');
    }

    public function tramo(): BelongsTo
    {
        return $this->belongsTo(TramoReglaPrecio::class, 'tramo_id');
    }
}
