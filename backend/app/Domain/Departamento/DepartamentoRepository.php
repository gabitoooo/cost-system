<?php

namespace App\Domain\Departamento;

interface DepartamentoRepository
{
    public function findAll(): array;

    public function findById(int $id): ?Departamento;

    public function save(Departamento $departamento): Departamento;

    public function delete(int $id): void;
}
