<?php

namespace App\Application\ActividadInductorTiempo\Dtos;

class ActividadInductoTiempoViewDtop
{
    public function __construct(
        public readonly int $id,
        public readonly int $actividadId,
        public readonly int $inductorTiempoId,
        public readonly string $inductorTiempoNombre,
        public readonly string $tipoCalculo,
    ) {}
    public function toArray(){
        return [
            'id' => $this->id,
            'actividadId' => $this->actividadId,
            'inductor_tiempo_id' => $this->inductorTiempoId,
            'inductor_nombre' => $this->inductorTiempoNombre,
            'tipo_calculo' => $this->tipoCalculo,
        ];
    }
}
