<?php

namespace App\Providers;

use App\Application\Contracts\TransactionManager;
use App\Domain\Actividad\ActividadRepository;
use App\Domain\ActividadInductorTiempo\ActividadInductorTiempoRepository;
use App\Domain\AsignacionCostoCompartido\AsignacionCostoCompartidoRepository;
use App\Domain\Departamento\DepartamentoRepository;
use App\Domain\GrupoRecurso\GrupoRecursoRepository;
use App\Domain\InductorTiempo\InductorTiempoRepository;
use App\Domain\Recurso\RecursoRepository;
use App\Domain\RecursoCompartido\RecursoCompartidoRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentActividadInductorTiempoRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentActividadRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentAsignacionCostoCompartidoRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentDepartamentoRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentGrupoRecursoRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentInductorTiempoRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentRecursoCompartidoRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentRecursoRepository;
use App\Infrastructure\Services\EloquentTransactionManager;
use Illuminate\Support\ServiceProvider;

class TdabcServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TransactionManager::class, EloquentTransactionManager::class);
        $this->app->bind(DepartamentoRepository::class, EloquentDepartamentoRepository::class);
        $this->app->bind(GrupoRecursoRepository::class, EloquentGrupoRecursoRepository::class);
        $this->app->bind(RecursoRepository::class, EloquentRecursoRepository::class);
        $this->app->bind(RecursoCompartidoRepository::class, EloquentRecursoCompartidoRepository::class);
        $this->app->bind(AsignacionCostoCompartidoRepository::class, EloquentAsignacionCostoCompartidoRepository::class);
        $this->app->bind(ActividadRepository::class, EloquentActividadRepository::class);
        $this->app->bind(InductorTiempoRepository::class, EloquentInductorTiempoRepository::class);
        $this->app->bind(ActividadInductorTiempoRepository::class, EloquentActividadInductorTiempoRepository::class);
    }
}
