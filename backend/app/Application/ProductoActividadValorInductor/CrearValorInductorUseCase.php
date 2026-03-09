<?php

namespace App\Application\ProductoActividadValorInductor;

use App\Application\ProductoActividadValorInductor\Dtos\CrearValorInductorDto;
use App\Application\ProductoActividadValorInductor\Dtos\ValorInductorResultDto;
use App\Domain\InductorTiempo\Exceptions\InductorTiempoNoEncontradoException;
use App\Domain\InductorTiempo\InductorTiempoRepository;
use App\Domain\ProductoActividad\Exceptions\ProductoActividadNoEncontradaException;
use App\Domain\ProductoActividad\ProductoActividadRepository;
use App\Domain\ProductoActividadValorInductor\Exceptions\InductorYaConfiguradoException;
use App\Domain\ProductoActividadValorInductor\ProductoActividadValorInductor;
use App\Domain\ProductoActividadValorInductor\ProductoActividadValorInductorRepository;

class CrearValorInductorUseCase
{
    public function __construct(
        private readonly ProductoActividadValorInductorRepository $repository,
        private readonly ProductoActividadRepository              $productoActividadRepository,
        private readonly InductorTiempoRepository                 $inductorRepository,
    ) {}

    /**
     * @throws ProductoActividadNoEncontradaException
     * @throws InductorTiempoNoEncontradoException
     * @throws InductorYaConfiguradoException
     */
    public function ejecutar(CrearValorInductorDto $dto): ValorInductorResultDto
    {
        $pa = $this->productoActividadRepository->findById($dto->productoActividadId);
        if ($pa === null) {
            throw new ProductoActividadNoEncontradaException(0, $dto->productoActividadId);
        }

        $this->inductorRepository->findById($dto->inductorTiempoId)
            ?? throw new InductorTiempoNoEncontradoException($dto->inductorTiempoId);

        $existente = $this->repository->findByProductoActividadAndInductor(
            $dto->productoActividadId,
            $dto->inductorTiempoId
        );
        if ($existente !== null) {
            throw new InductorYaConfiguradoException($dto->inductorTiempoId);
        }

        $valor = new ProductoActividadValorInductor(
            id: 0,
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
