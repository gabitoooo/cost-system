<?php

namespace App\Providers;

use App\Application\Contracts\TransactionManager;
use App\Domain\Actividad\ActividadRepository;
use App\Domain\ActividadInductorTiempo\ActividadInductorTiempoRepository;
use App\Domain\AsignacionRecursoGrupo\AsignacionRecursoGrupoRepository;
use App\Domain\CategoriaProducto\CategoriaProductoRepository;
use App\Domain\DetalleInstantaneaCosto\DetalleInstantaneaCostoRepository;
use App\Domain\Departamento\DepartamentoRepository;
use App\Domain\GrupoRecurso\GrupoRecursoRepository;
use App\Domain\InstantaneaCostoProducto\InstantaneaCostoProductoRepository;
use App\Domain\InductorTiempo\InductorTiempoRepository;
use App\Domain\Producto\ProductoRepository;
use App\Domain\ProductoActividad\ProductoActividadRepository;
use App\Domain\ProductoActividadValorInductor\ProductoActividadValorInductorRepository;
use App\Domain\Recurso\RecursoRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentActividadInductorTiempoRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentActividadRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentAsignacionRecursoGrupoRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentCategoriaProductoRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentDetalleInstantaneaCostoRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentDepartamentoRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentGrupoRecursoRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentInstantaneaCostoProductoRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentInductorTiempoRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentProductoActividadRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentProductoActividadValorInductorRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentProductoRepository;
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
        $this->app->bind(AsignacionRecursoGrupoRepository::class, EloquentAsignacionRecursoGrupoRepository::class);
        $this->app->bind(ActividadRepository::class, EloquentActividadRepository::class);
        $this->app->bind(InductorTiempoRepository::class, EloquentInductorTiempoRepository::class);
        $this->app->bind(ActividadInductorTiempoRepository::class, EloquentActividadInductorTiempoRepository::class);
        $this->app->bind(CategoriaProductoRepository::class, EloquentCategoriaProductoRepository::class);
        $this->app->bind(ProductoRepository::class, EloquentProductoRepository::class);
        $this->app->bind(ProductoActividadRepository::class, EloquentProductoActividadRepository::class);
        $this->app->bind(ProductoActividadValorInductorRepository::class, EloquentProductoActividadValorInductorRepository::class);
        $this->app->bind(InstantaneaCostoProductoRepository::class, EloquentInstantaneaCostoProductoRepository::class);
        $this->app->bind(DetalleInstantaneaCostoRepository::class, EloquentDetalleInstantaneaCostoRepository::class);
    }
}
