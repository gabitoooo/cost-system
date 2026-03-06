<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleInstantaneaCosto extends Model
{
    protected $table = 'detalles_instantanea_costo';

    // Solo tiene created_at, sin updated_at
    const UPDATED_AT = null;

    protected $fillable = [
        'precio_producto_id',
        'snapshot_tdabc',
    ];

    protected function casts(): array
    {
        return [
            'snapshot_tdabc' => 'array',
        ];
    }

    public function precioProducto(): BelongsTo
    {
        return $this->belongsTo(PrecioProducto::class, 'precio_producto_id');
    }
}
