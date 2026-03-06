<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TramoReglaPrecio extends Model
{
    protected $table = 'tramos_regla_precio';

    // Solo tiene created_at, sin updated_at
    const UPDATED_AT = null;

    protected $fillable = [
        'regla_precio_id',
        'cantidad_minima',
        'cantidad_maxima',
        'tipo_calculo',
        'valor',
    ];

    protected function casts(): array
    {
        return [
            'valor' => 'decimal:4',
        ];
    }

    public function reglaPrecio(): BelongsTo
    {
        return $this->belongsTo(ReglaPrecio::class, 'regla_precio_id');
    }

    public function preciosProducto(): HasMany
    {
        return $this->hasMany(PrecioProducto::class, 'tramo_id');
    }
}
