<?php

namespace App\Application\Departamento;

use App\Application\Departamento\Dtos\DepartamentoResultDto;
use App\Domain\Departamento\DepartamentoRepository;

class ListarDepartamentosUseCase
{
    public function __construct(
        private readonly DepartamentoRepository $repository,
    ) {}

    /** @return DepartamentoResultDto[] */
    public function ejecutar(): array
    {
        return array_map(
            fn($d) => new DepartamentoResultDto(id: $d->id, nombre: $d->nombre),
            $this->repository->findAll(),
        );
    }
}
