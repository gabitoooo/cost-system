<?php

namespace App\Domain\Recurso;

class Recurso
{
    public function __construct(
        public readonly int    $id,
        public readonly int    $grupoRecursosId,
        public readonly string $nombre,
        public readonly string $tipo,
        public readonly float  $costoMensual,
    ) {}
}
