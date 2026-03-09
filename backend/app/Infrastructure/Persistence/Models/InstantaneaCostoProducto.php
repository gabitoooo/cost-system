<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InstantaneaCostoProducto extends Model
{
    protected $table = 'instantaneas_costo_producto';

    const UPDATED_AT = null;

    protected $fillable = [
        'producto_id',
        'precio_producto_id',
        'cantidad_minima',
        'cantidad_maxima',
        'costo_indirecto',
        'costo_material_directo',
        'costo_unitario',
        'costo_total',
        'calculado_en',
    ];

    protected function casts(): array
    {
        return [
            'costo_indirecto'        => 'decimal:4',
            'costo_material_directo' => 'decimal:2',
            'costo_unitario'         => 'decimal:4',
            'costo_total'            => 'decimal:4',
        ];
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function precioProducto(): BelongsTo
    {
        return $this->belongsTo(PrecioProducto::class, 'precio_producto_id');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleInstantaneaCosto::class, 'instantanea_id');
    }
}
