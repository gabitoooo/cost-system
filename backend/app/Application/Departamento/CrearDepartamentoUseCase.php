<?php

namespace App\Application\Departamento;

use App\Application\Departamento\Dtos\CrearDepartamentoDto;
use App\Application\Departamento\Dtos\DepartamentoResultDto;
use App\Domain\Departamento\Departamento;
use App\Domain\Departamento\DepartamentoRepository;

class CrearDepartamentoUseCase
{
    public function __construct(
        private readonly DepartamentoRepository $repository,
    ) {}

    public function ejecutar(CrearDepartamentoDto $dto): DepartamentoResultDto
    {
        $departamento = new Departamento(id: 0, nombre: $dto->nombre);
        $guardado = $this->repository->save($departamento);

        return new DepartamentoResultDto(id: $guardado->id, nombre: $guardado->nombre);
    }
}
