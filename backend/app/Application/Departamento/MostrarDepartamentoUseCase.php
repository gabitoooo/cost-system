<?php

namespace App\Application\Departamento;

use App\Application\Departamento\Dtos\DepartamentoResultDto;
use App\Domain\Departamento\DepartamentoRepository;
use App\Domain\Departamento\Exceptions\DepartamentoNoEncontradoException;

class MostrarDepartamentoUseCase
{
    public function __construct(
        private readonly DepartamentoRepository $repository,
    ) {}

    /** @throws DepartamentoNoEncontradoException */
    public function ejecutar(int $id): DepartamentoResultDto
    {
        $departamento = $this->repository->findById($id)
            ?? throw new DepartamentoNoEncontradoException($id);

        return new DepartamentoResultDto(id: $departamento->id, nombre: $departamento->nombre);
    }
}
