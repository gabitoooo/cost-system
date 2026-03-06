<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemPedido extends Model
{
    protected $table = 'items_pedido';

    protected $fillable = [
        'pedido_id',
        'item_cotizacion_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'precio_unitario' => 'decimal:4',
            'subtotal'        => 'decimal:4',
        ];
    }

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    public function itemCotizacion(): BelongsTo
    {
        return $this->belongsTo(ItemCotizacion::class, 'item_cotizacion_id');
    }
}
