<?php

namespace App\Application\InductorTiempo;

use App\Application\InductorTiempo\Dtos\ActualizarInductorTiempoDto;
use App\Application\InductorTiempo\Dtos\InductorTiempoResultDto;
use App\Domain\InductorTiempo\Exceptions\InductorTiempoNoEncontradoException;
use App\Domain\InductorTiempo\InductorTiempo;
use App\Domain\InductorTiempo\InductorTiempoRepository;

class ActualizarInductorTiempoUseCase
{
    public function __construct(
        private readonly InductorTiempoRepository $repository,
    ) {}

    /** @throws InductorTiempoNoEncontradoException */
    public function ejecutar(ActualizarInductorTiempoDto $dto): InductorTiempoResultDto
    {
        $this->repository->findById($dto->id)
            ?? throw new InductorTiempoNoEncontradoException($dto->id);

        $inductor = new InductorTiempo(
            id: $dto->id,
            nombre: $dto->nombre,
            descripcion: $dto->descripcion,
            tipoCalculo: $dto->tipoCalculo,
        );

        $guardado = $this->repository->save($inductor);

        return new InductorTiempoResultDto(
            id: $guardado->id,
            nombre: $guardado->nombre,
            descripcion: $guardado->descripcion,
            tipoCalculo: $guardado->tipoCalculo,
        );
    }
}
