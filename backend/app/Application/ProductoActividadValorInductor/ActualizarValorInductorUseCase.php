<?php

namespace App\Application\ProductoActividadValorInductor;

use App\Application\ProductoActividadValorInductor\Dtos\ActualizarValorInductorDto;
use App\Application\ProductoActividadValorInductor\Dtos\ValorInductorResultDto;
use App\Domain\ProductoActividadValorInductor\Exceptions\ValorInductorNoEncontradoException;
use App\Domain\ProductoActividadValorInductor\ProductoActividadValorInductor;
use App\Domain\ProductoActividadValorInductor\ProductoActividadValorInductorRepository;

class ActualizarValorInductorUseCase
{
    public function __construct(
        private readonly ProductoActividadValorInductorRepository $repository,
    ) {}

    /** @throws ValorInductorNoEncontradoException */
    public function ejecutar(ActualizarValorInductorDto $dto): ValorInductorResultDto
    {
        $existente = $this->repository->findByProductoActividadAndInductor(
            $dto->productoActividadId,
            $dto->inductorTiempoId
        );

        if ($existente === null) {
            throw new ValorInductorNoEncontradoException($dto->inductorTiempoId);
        }

        $valor = new ProductoActividadValorInductor(
            id: $existente->id,
            productoActividadId: $dto->productoActividadId,
            inductorTiempoId: $dto->inductorTiempoId,
            valorX: $dto->valorX,
            betaMinutos: $dto->betaMinutos,
            tamanoLote: $dto->tamanoLote,
        );

        $guardado = $this->repository->save($valor);

        return new ValorInductorResultDto(
            id: $guardado->id,
            productoActividadId: $guardado->productoActividadId,
            inductorTiempoId: $guardado->inductorTiempoId,
            valorX: $guardado->valorX,
            betaMinutos: $guardado->betaMinutos,
            tamanoLote: $guardado->tamanoLote,
        );
    }
}
