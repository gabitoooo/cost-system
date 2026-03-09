<?php

namespace App\Application\ProductoActividad;

use App\Application\ProductoActividad\Dtos\AsignarActividadProductoDto;
use App\Application\ProductoActividad\Dtos\ProductoActividadResultDto;
use App\Domain\Actividad\ActividadRepository;
use App\Domain\Actividad\Exceptions\ActividadNoEncontradaException;
use App\Domain\Producto\Exceptions\ProductoNoEncontradoException;
use App\Domain\Producto\ProductoRepository;
use App\Domain\ProductoActividad\Exceptions\ActividadYaAsignadaAlProductoException;
use App\Domain\ProductoActividad\ProductoActividad;
use App\Domain\ProductoActividad\ProductoActividadRepository;

class AsignarActividadProductoUseCase
{
    public function __construct(
        private readonly ProductoActividadRepository $repository,
        private readonly ProductoRepository          $productoRepository,
        private readonly ActividadRepository         $actividadRepository,
    ) {}

    /**
     * @throws ProductoNoEncontradoException
     * @throws ActividadNoEncontradaException
     * @throws ActividadYaAsignadaAlProductoException
     */
    public function ejecutar(AsignarActividadProductoDto $dto): ProductoActividadResultDto
    {
        $this->productoRepository->findById($dto->productoId)
            ?? throw new ProductoNoEncontradoException($dto->productoId);

        $this->actividadRepository->findById($dto->actividadId)
            ?? throw new ActividadNoEncontradaException($dto->actividadId);

        $existente = $this->repository->findByProductoAndActividad($dto->productoId, $dto->actividadId);
        if ($existente !== null) {
            throw new ActividadYaAsignadaAlProductoException($dto->actividadId);
        }

        $pa = new ProductoActividad(
            id: 0,
            productoId: $dto->productoId,
            actividadId: $dto->actividadId,
        );

        $guardado = $this->repository->save($pa);

        return new ProductoActividadResultDto(
            id: $guardado->id,
            productoId: $guardado->productoId,
            actividadId: $guardado->actividadId,
        );
    }
}
