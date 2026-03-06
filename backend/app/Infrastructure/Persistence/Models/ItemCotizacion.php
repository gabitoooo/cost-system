<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ItemCotizacion extends Model
{
    protected $table = 'items_cotizacion';

    protected $fillable = [
        'cotizacion_id',
        'producto_id',
        'precio_producto_id',
        'cantidad',
        'costo_unitario',
        'precio_unitario',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'costo_unitario'  => 'decimal:4',
            'precio_unitario' => 'decimal:4',
            'subtotal'        => 'decimal:4',
        ];
    }

    public function cotizacion(): BelongsTo
    {
        return $this->belongsTo(Cotizacion::class, 'cotizacion_id');
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function precioProducto(): BelongsTo
    {
        return $this->belongsTo(PrecioProducto::class, 'precio_producto_id');
    }

    public function logDeterminacion(): HasOne
    {
        return $this->hasOne(LogDeterminacionPrecio::class, 'item_cotizacion_id');
    }

    public function itemPedido(): HasOne
    {
        return $this->hasOne(ItemPedido::class, 'item_cotizacion_id');
    }
}
