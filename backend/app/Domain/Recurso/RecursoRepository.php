<?php

namespace App\Domain\Recurso;

interface RecursoRepository
{
    public function findById(int $id): ?Recurso;

    /** @return Recurso[] */
    public function findAll(): array;

    public function save(Recurso $recurso): Recurso;

    public function delete(int $id): void;
}
