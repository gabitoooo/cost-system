<?php

namespace App\Application\InductorTiempo;

use App\Application\InductorTiempo\Dtos\CrearInductorTiempoDto;
use App\Application\InductorTiempo\Dtos\InductorTiempoResultDto;
use App\Domain\InductorTiempo\InductorTiempo;
use App\Domain\InductorTiempo\InductorTiempoRepository;

class CrearInductorTiempoUseCase
{
    public function __construct(
        private readonly InductorTiempoRepository $repository,
    ) {}

    public function ejecutar(CrearInductorTiempoDto $dto): InductorTiempoResultDto
    {
        $inductor = new InductorTiempo(
            id: 0,
            nombre: $dto->nombre,
            descripcion: $dto->descripcion,
            tipoCalculo: $dto->tipoCalculo,
        );

        $guardado = $this->repository->save($inductor);

        return $this->toDto($guardado);
    }

    private function toDto(InductorTiempo $i): InductorTiempoResultDto
    {
        return new InductorTiempoResultDto(
            id: $i->id,
            nombre: $i->nombre,
            descripcion: $i->descripcion,
            tipoCalculo: $i->tipoCalculo,
        );
    }
}
