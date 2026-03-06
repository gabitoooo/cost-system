<?php

namespace App\Application\InductorTiempo;

use App\Application\InductorTiempo\Dtos\InductorTiempoResultDto;
use App\Domain\InductorTiempo\Exceptions\InductorTiempoNoEncontradoException;
use App\Domain\InductorTiempo\InductorTiempoRepository;

class MostrarInductorTiempoUseCase
{
    public function __construct(
        private readonly InductorTiempoRepository $repository,
    ) {}

    /** @throws InductorTiempoNoEncontradoException */
    public function ejecutar(int $id): InductorTiempoResultDto
    {
        $inductor = $this->repository->findById($id)
            ?? throw new InductorTiempoNoEncontradoException($id);

        return new InductorTiempoResultDto(
            id: $inductor->id,
            nombre: $inductor->nombre,
            descripcion: $inductor->descripcion,
            tipoCalculo: $inductor->tipoCalculo,
        );
    }
}
