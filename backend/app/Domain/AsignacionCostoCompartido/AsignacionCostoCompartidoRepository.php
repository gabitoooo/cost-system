<?php

namespace App\Domain\AsignacionCostoCompartido;

interface AsignacionCostoCompartidoRepository
{
    /** @return AsignacionCostoCompartido[] */
    public function findByRecursoCompartido(int $recursoCompartidoId): array;

    /** Reemplaza todas las asignaciones del recurso compartido en una transaccion. */
    public function syncAsignaciones(int $recursoCompartidoId, array $asignaciones): array;
}
