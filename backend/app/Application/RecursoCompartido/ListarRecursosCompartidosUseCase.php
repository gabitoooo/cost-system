<?php

namespace App\Application\RecursoCompartido;

use App\Application\RecursoCompartido\Dtos\RecursoCompartidoResultDto;
use App\Domain\Departamento\DepartamentoRepository;
use App\Domain\Departamento\Exceptions\DepartamentoNoEncontradoException;
use App\Domain\RecursoCompartido\RecursoCompartidoRepository;

class ListarRecursosCompartidosUseCase
{
    public function __construct(
        private readonly RecursoCompartidoRepository $repository,
        private readonly DepartamentoRepository      $departamentoRepository,
    ) {}

    /**
     * @return RecursoCompartidoResultDto[]
     * @throws DepartamentoNoEncontradoException
     */
    public function ejecutar(int $departamentoId): array
    {
        $this->departamentoRepository->findById($departamentoId)
            ?? throw new DepartamentoNoEncontradoException($departamentoId);

        return array_map(
            fn($r) => new RecursoCompartidoResultDto(
                id: $r->id,
                departamentoId: $r->departamentoId,
                nombre: $r->nombre,
                tipo: $r->tipo,
                costoMensual: $r->costoMensual,
            ),
            $this->repository->findByDepartamento($departamentoId),
        );
    }
}
