<?php

namespace App\Domain\GrupoRecurso;

interface GrupoRecursoRepository
{
    public function findById(int $id): ?GrupoRecurso;

    public function findByDepartamento(int $departamentoId): array;

    public function save(GrupoRecurso $grupo): GrupoRecurso;

    public function delete(int $id): void;
}
