<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductoActividad extends Model
{
    protected $table = 'producto_actividades';

    protected $fillable = [
        'producto_id',
        'actividad_id',
    ];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function actividad(): BelongsTo
    {
        return $this->belongsTo(Actividad::class, 'actividad_id');
    }

    public function valoresInductor(): HasMany
    {
        return $this->hasMany(ProductoActividadValorInductor::class, 'producto_actividad_id');
    }
}
