<?php

namespace App\Application\Actividad;

use App\Application\Actividad\Dtos\ActividadResultDto;
use App\Domain\Actividad\ActividadRepository;

class ListarTodasActividadesUseCase
{
    public function __construct(
        private readonly ActividadRepository $repository,
    ) {}

    /**
     * @return ActividadResultDto[]
     */
    public function ejecutar(): array
    {
        return array_map(
            fn($a) => new ActividadResultDto(
                id: $a->id,
                grupoRecursosId: $a->grupoRecursosId,
                nombre: $a->nombre,
                tiempoBaseMinutos: $a->tiempoBaseMinutos,
            ),
            $this->repository->findAll(),
        );
    }
}
