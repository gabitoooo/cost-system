<?php

namespace App\Application\Recurso;

use App\Application\Recurso\Dtos\CrearRecursoDto;
use App\Application\Recurso\Dtos\RecursoResultDto;
use App\Domain\GrupoRecurso\Exceptions\GrupoRecursoNoEncontradoException;
use App\Domain\GrupoRecurso\GrupoRecursoRepository;
use App\Domain\Recurso\Recurso;
use App\Domain\Recurso\RecursoRepository;

class CrearRecursoUseCase
{
    public function __construct(
        private readonly RecursoRepository      $repository,
        private readonly GrupoRecursoRepository $grupoRepository,
    ) {}

    /** @throws GrupoRecursoNoEncontradoException */
    public function ejecutar(CrearRecursoDto $dto): RecursoResultDto
    {
       
        $recurso = new Recurso(
            id: 0,          
            nombre: $dto->nombre,
            tipo: $dto->tipo,
            costoMensual: $dto->costoMensual,
        );

        $guardado = $this->repository->save($recurso);        

        return new RecursoResultDto(
            id: $guardado->id,         
            nombre: $guardado->nombre,
            tipo: $guardado->tipo,
            costoMensual: $guardado->costoMensual,
        );
    }
}
