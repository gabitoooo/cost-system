<?php

namespace App\Application\Recurso;

use App\Application\Recurso\Dtos\RecursoResultDto;
use App\Domain\Recurso\RecursoRepository;

class ListarRecursosUseCase
{
    public function __construct(
        private readonly RecursoRepository $repository,
    ) {}

    /** @return RecursoResultDto[] */
    public function ejecutar(): array
    {
        return array_map(
            fn($r) => new RecursoResultDto(
                id: $r->id,
                nombre: $r->nombre,
                tipo: $r->tipo,
                costoMensual: $r->costoMensual,
            ),
            $this->repository->findAll(),
        );
    }
}
