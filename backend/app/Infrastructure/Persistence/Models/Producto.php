<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    protected $table = 'productos';

    protected $fillable = [
        'categoria_id',
        'nombre',
        'descripcion',
        'costo_material_directo',
    ];

    protected function casts(): array
    {
        return [
            'costo_material_directo' => 'decimal:2',
        ];
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(CategoriaProducto::class, 'categoria_id');
    }

    public function productoActividades(): HasMany
    {
        return $this->hasMany(ProductoActividad::class, 'producto_id');
    }

    public function preciosProducto(): HasMany
    {
        return $this->hasMany(PrecioProducto::class, 'producto_id');
    }

    public function reglasPrecio(): HasMany
    {
        return $this->hasMany(ReglaPrecio::class, 'producto_id');
    }
}
