<?php

namespace App\Domain\AsignacionRecursoGrupo;

interface AsignacionRecursoGrupoRepository
{
    // --- flujo centrado en recurso ---

    /** @return AsignacionRecursoGrupo[] */
    public function findByRecurso(int $recursoId): array;

    public function deleteByRecurso(int $recursoId): void;

    /** Suma el costo prorrateado de todos los recursos asignados al grupo: SUM(costo_mensual * porcentaje/100) */
    public function sumCostoProrateadoByGrupo(int $grupoId): float;

    // --- flujo centrado en grupo ---

    /** @return AsignacionRecursoGrupo[] */
    public function findByGrupo(int $grupoId): array;

    public function findByRecursoAndGrupo(int $recursoId, int $grupoId): ?AsignacionRecursoGrupo;

    /** Suma de porcentajes ya asignados para un recurso (a todos sus grupos) */
    public function sumPorcentajeByRecurso(int $recursoId): float;

    public function deleteById(int $id): void;

    // --- compartido ---

    public function save(AsignacionRecursoGrupo $asignacion): AsignacionRecursoGrupo;
}
