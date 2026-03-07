<?php

namespace App\Domain\AsignacionCostoCompartido;

interface AsignacionCostoCompartidoRepository
{
    /** @return AsignacionCostoCompartido[] */
    public function findByRecursoCompartido(int $recursoCompartidoId): array;

    public function save(AsignacionCostoCompartido $asignacion): AsignacionCostoCompartido;

    public function deleteByRecursoCompartido(int $recursoCompartidoId): void;

    public function sumCostoCompartidoByGrupo(int $grupoId): float;
}
