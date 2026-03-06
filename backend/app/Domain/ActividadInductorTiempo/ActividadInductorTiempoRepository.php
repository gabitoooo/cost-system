<?php

namespace App\Domain\ActividadInductorTiempo;

interface ActividadInductorTiempoRepository
{
    /** @return ActividadInductorTiempo[] */
    public function findByActividad(int $actividadId): array;

    public function findByActividadAndInductor(int $actividadId, int $inductorTiempoId): ?ActividadInductorTiempo;

    public function save(ActividadInductorTiempo $ait): ActividadInductorTiempo;

    public function delete(int $actividadId, int $inductorTiempoId): void;
}
