<?php

namespace App\Domain\RecursoCompartido;

interface RecursoCompartidoRepository
{
    public function findById(int $id): ?RecursoCompartido;

    public function findByDepartamento(int $departamentoId): array;

    public function save(RecursoCompartido $recurso): RecursoCompartido;

    public function delete(int $id): void;
}
