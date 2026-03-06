<?php

namespace App\Application\Departamento;

use App\Domain\Departamento\DepartamentoRepository;
use App\Domain\Departamento\Exceptions\DepartamentoNoEncontradoException;

class EliminarDepartamentoUseCase
{
    public function __construct(
        private readonly DepartamentoRepository $repository,
    ) {}

    /** @throws DepartamentoNoEncontradoException */
    public function ejecutar(int $id): void
    {
        $this->repository->findById($id)
            ?? throw new DepartamentoNoEncontradoException($id);

        $this->repository->delete($id);
    }
}
