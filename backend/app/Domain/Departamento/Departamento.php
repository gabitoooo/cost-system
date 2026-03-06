<?php

namespace App\Domain\Departamento;

class Departamento
{
    public function __construct(
        public readonly int    $id,
        public readonly string $nombre,
    ) {}
}
