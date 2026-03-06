<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReglaPrecio extends Model
{
    protected $table = 'reglas_precio';

    protected $fillable = [
        'lista_precio_id',
        'nombre',
        'prioridad',
        'aplica_a',
        'producto_id',
        'categoria_id',
        'tipo_calculo',
        'valor',
        'margen_minimo',
        'cantidad_minima',
        'cantidad_maxima',
        'vigente_desde',
        'vigente_hasta',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'valor'         => 'decimal:4',
            'margen_minimo' => 'decimal:2',
            'vigente_desde' => 'date',
            'vigente_hasta' => 'date',
            'activo'        => 'boolean',
        ];
    }

    public function listaPrecio(): BelongsTo
    {
        return $this->belongsTo(ListaPrecio::class, 'lista_precio_id');
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(CategoriaProducto::class, 'categoria_id');
    }

    public function tramos(): HasMany
    {
        return $this->hasMany(TramoReglaPrecio::class, 'regla_precio_id');
    }

    public function preciosProducto(): HasMany
    {
        return $this->hasMany(PrecioProducto::class, 'regla_precio_id');
    }
}
