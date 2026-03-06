<?php

namespace App\Application\Departamento;

use App\Application\Departamento\Dtos\ActualizarDepartamentoDto;
use App\Application\Departamento\Dtos\DepartamentoResultDto;
use App\Domain\Departamento\Departamento;
use App\Domain\Departamento\DepartamentoRepository;
use App\Domain\Departamento\Exceptions\DepartamentoNoEncontradoException;

class ActualizarDepartamentoUseCase
{
    public function __construct(
        private readonly DepartamentoRepository $repository,
    ) {}

    /** @throws DepartamentoNoEncontradoException */
    public function ejecutar(ActualizarDepartamentoDto $dto): DepartamentoResultDto
    {
        $this->repository->findById($dto->id)
            ?? throw new DepartamentoNoEncontradoException($dto->id);

        $departamento = new Departamento(id: $dto->id, nombre: $dto->nombre);
        $guardado = $this->repository->save($departamento);

        return new DepartamentoResultDto(id: $guardado->id, nombre: $guardado->nombre);
    }
}
