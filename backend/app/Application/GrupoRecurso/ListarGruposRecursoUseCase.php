<?php

namespace App\Application\GrupoRecurso;

use App\Application\GrupoRecurso\Dtos\GrupoRecursoResultDto;
use App\Domain\Departamento\DepartamentoRepository;
use App\Domain\Departamento\Exceptions\DepartamentoNoEncontradoException;
use App\Domain\GrupoRecurso\GrupoRecursoRepository;

class ListarGruposRecursoUseCase
{
    public function __construct(
        private readonly GrupoRecursoRepository $repository,
        private readonly DepartamentoRepository $departamentoRepository,
    ) {}

    /**
     * @return GrupoRecursoResultDto[]
     * @throws DepartamentoNoEncontradoException
     */
    public function ejecutar(int $departamentoId): array
    {
        $this->departamentoRepository->findById($departamentoId)
            ?? throw new DepartamentoNoEncontradoException($departamentoId);

        return array_map(
            fn($g) => new GrupoRecursoResultDto(
                id: $g->id,
                departamentoId: $g->departamentoId,
                nombre: $g->nombre,
                capacidadPracticaMinutos: $g->capacidadPracticaMinutos,
                tasaCostoPorMinuto: $g->tasaCostoPorMinuto,
            ),
            $this->repository->findByDepartamento($departamentoId),
        );
    }
}
