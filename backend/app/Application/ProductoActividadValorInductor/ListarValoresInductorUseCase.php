<?php

namespace App\Application\ProductoActividadValorInductor;

use App\Application\ProductoActividadValorInductor\Dtos\ValorInductorResultDto;
use App\Domain\ProductoActividadValorInductor\ProductoActividadValorInductor;
use App\Domain\ProductoActividadValorInductor\ProductoActividadValorInductorRepository;

class ListarValoresInductorUseCase
{
    public function __construct(
        private readonly ProductoActividadValorInductorRepository $repository,
    ) {}

    /** @return ValorInductorResultDto[] */
    public function ejecutar(int $productoActividadId): array
    {
        return array_map(
            fn(ProductoActividadValorInductor $v) => new ValorInductorResultDto(
                id: $v->id,
                productoActividadId: $v->productoActividadId,
                inductorTiempoId: $v->inductorTiempoId,
                valorX: $v->valorX,
                betaMinutos: $v->betaMinutos,
                tamanoLote: $v->tamanoLote,
            ),
            $this->repository->findByProductoActividad($productoActividadId)
        );
    }
}
