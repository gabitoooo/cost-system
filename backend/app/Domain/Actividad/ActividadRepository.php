<?php

namespace App\Domain\Actividad;

interface ActividadRepository
{
    public function findById(int $id): ?Actividad;

    public function findByGrupo(int $grupoRecursosId): array;

    public function save(Actividad $actividad): Actividad;

    public function delete(int $id): void;
}
