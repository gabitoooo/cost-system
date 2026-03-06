<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PrecioProducto extends Model
{
    protected $table = 'precios_producto';

    protected $fillable = [
        'producto_id',
        'regla_precio_id',
        'tramo_id',
        'cantidad_referencia',
        'costo_material_directo',
        'costo_indirecto',
        'costo_unitario',
        'precio_unitario',
        'estado',
        'calculado_en',
        'archivado_en',
    ];

    protected function casts(): array
    {
        return [
            'costo_material_directo' => 'decimal:2',
            'costo_indirecto'        => 'decimal:4',
            'costo_unitario'         => 'decimal:4',
            'precio_unitario'        => 'decimal:4',
            'calculado_en'           => 'datetime',
            'archivado_en'           => 'datetime',
        ];
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function reglaPrecio(): BelongsTo
    {
        return $this->belongsTo(ReglaPrecio::class, 'regla_precio_id');
    }

    public function tramo(): BelongsTo
    {
        return $this->belongsTo(TramoReglaPrecio::class, 'tramo_id');
    }

    public function detalleInstantanea(): HasOne
    {
        return $this->hasOne(DetalleInstantaneaCosto::class, 'precio_producto_id');
    }

    public function itemsCotizacion(): HasMany
    {
        return $this->hasMany(ItemCotizacion::class, 'precio_producto_id');
    }
}
