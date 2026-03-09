<?php

namespace App\Infrastructure\Persistence\Models;

use App\Domain\InductorTiempo\Enums\InductorTiempoTipoCalculoEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InductorTiempo extends Model
{
    protected $table = 'inductores_tiempo';

    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo_calculo',
    ];

    protected function casts(): array
    {
        return [
            'tipo_calculo' => InductorTiempoTipoCalculoEnum::class,
        ];
    }

    public function actividadInductores(): HasMany
    {
        return $this->hasMany(ActividadInductorTiempo::class, 'inductor_tiempo_id');
    }

    public function valoresInductor(): HasMany
    {
        return $this->hasMany(ProductoActividadValorInductor::class, 'inductor_tiempo_id');
    }
}
