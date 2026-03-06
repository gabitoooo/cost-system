<?php

namespace App\Domain\InductorTiempo;

interface InductorTiempoRepository
{
    public function findAll(): array;

    public function findById(int $id): ?InductorTiempo;

    public function save(InductorTiempo $inductor): InductorTiempo;

    public function delete(int $id): void;
}
