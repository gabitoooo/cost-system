<?php

namespace App\Application\RecursoCompartido;

use App\Application\RecursoCompartido\Dtos\CrearRecursoCompartidoDto;
use App\Application\RecursoCompartido\Dtos\RecursoCompartidoResultDto;
use App\Domain\Departamento\DepartamentoRepository;
use App\Domain\Departamento\Exceptions\DepartamentoNoEncontradoException;
use App\Domain\RecursoCompartido\RecursoCompartido;
use App\Domain\RecursoCompartido\RecursoCompartidoRepository;

class CrearRecursoCompartidoUseCase
{
    public function __construct(
        private readonly RecursoCompartidoRepository $repository,
        private readonly DepartamentoRepository      $departamentoRepository,
    ) {}

    /** @throws DepartamentoNoEncontradoException */
    public function ejecutar(CrearRecursoCompartidoDto $dto): RecursoCompartidoResultDto
    {
        $this->departamentoRepository->findById($dto->departamentoId)
            ?? throw new DepartamentoNoEncontradoException($dto->departamentoId);

        $recurso = new RecursoCompartido(
            id: 0,
            departamentoId: $dto->departamentoId,
            nombre: $dto->nombre,
            tipo: $dto->tipo,
            costoMensual: $dto->costoMensual,
        );

        $guardado = $this->repository->save($recurso);

        return $this->toDto($guardado);
    }

    private function toDto(RecursoCompartido $r): RecursoCompartidoResultDto
    {
        return new RecursoCompartidoResultDto(
            id: $r->id,
            departamentoId: $r->departamentoId,
            nombre: $r->nombre,
            tipo: $r->tipo,
            costoMensual: $r->costoMensual,
        );
    }
}
