<?php

namespace App\Application\InductorTiempo;

use App\Application\InductorTiempo\Dtos\InductorTiempoResultDto;
use App\Domain\InductorTiempo\InductorTiempoRepository;

class ListarInductoresTiempoUseCase
{
    public function __construct(
        private readonly InductorTiempoRepository $repository,
    ) {}

    /** @return InductorTiempoResultDto[] */
    public function ejecutar(): array
    {
        return array_map(
            fn($i) => new InductorTiempoResultDto(
                id: $i->id,
                nombre: $i->nombre,
                descripcion: $i->descripcion,
                tipoCalculo: $i->tipoCalculo,
            ),
            $this->repository->findAll(),
        );
    }
}
