<?php

namespace App\Domain\Recurso;

interface RecursoRepository
{
    public function findById(int $id): ?Recurso;

    public function findByGrupo(int $grupoRecursosId): array;

    public function save(Recurso $recurso): Recurso;

    public function delete(int $id): void;
}
