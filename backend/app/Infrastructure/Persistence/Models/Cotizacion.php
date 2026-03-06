<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cotizacion extends Model
{
    protected $table = 'cotizaciones';

    protected $fillable = [
        'usuario_id',
        'lista_precio_id',
        'nombre_cliente',
        'estado',
        'es_pedido_directo',
        'costo_total',
        'precio_total',
        'notas',
    ];

    protected function casts(): array
    {
        return [
            'es_pedido_directo' => 'boolean',
            'costo_total'       => 'decimal:4',
            'precio_total'      => 'decimal:4',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function listaPrecio(): BelongsTo
    {
        return $this->belongsTo(ListaPrecio::class, 'lista_precio_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ItemCotizacion::class, 'cotizacion_id');
    }

    public function pedido(): HasOne
    {
        return $this->hasOne(Pedido::class, 'cotizacion_id');
    }
}
