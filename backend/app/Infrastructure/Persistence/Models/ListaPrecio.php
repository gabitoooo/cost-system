<?php

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ListaPrecio extends Model
{
    protected $table = 'listas_precio';

    protected $fillable = [
        'nombre',
        'descripcion',
        'es_default',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'es_default' => 'boolean',
            'activo'     => 'boolean',
        ];
    }

    public function reglas(): HasMany
    {
        return $this->hasMany(ReglaPrecio::class, 'lista_precio_id');
    }

    public function cotizaciones(): HasMany
    {
        return $this->hasMany(Cotizacion::class, 'lista_precio_id');
    }
}
