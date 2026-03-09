<?php

use App\Infrastructure\Persistence\Models\Departamento;
use App\Infrastructure\Persistence\Models\User;
use App\Interfaces\Controllers\ActividadController;
use App\Interfaces\Controllers\ActividadInductorTiempoController;
use App\Interfaces\Controllers\AsignacionRecursoGrupoController;
use App\Interfaces\Controllers\AuthController;
use App\Interfaces\Controllers\CategoriaProductoController;
use App\Interfaces\Controllers\DepartamentoController;
use App\Interfaces\Controllers\GrupoRecursoController;
use App\Interfaces\Controllers\InductorTiempoController;
use App\Interfaces\Controllers\ProductoActividadController;
use App\Interfaces\Controllers\ProductoActividadValorInductorController;
use App\Interfaces\Controllers\ProductoController;
use App\Interfaces\Controllers\RecursoController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    // TDABC: Departamentos 
    Route::get('departamentos', [DepartamentoController::class, 'index']);
    Route::post('departamentos', [DepartamentoController::class, 'store']);
    Route::get('departamentos/{id}', [DepartamentoController::class, 'show']);
    Route::put('departamentos/{id}', [DepartamentoController::class, 'update']);
    Route::delete('departamentos/{id}', [DepartamentoController::class, 'destroy']);

    // Grupos de recursos de un departamento
    Route::get('departamentos/{departamentoId}/grupos-recursos', [GrupoRecursoController::class, 'index']);

    // TDABC: Grupos de Recursos 
    Route::post('grupos-recursos', [GrupoRecursoController::class, 'store']);
    Route::get('grupos-recursos/{id}', [GrupoRecursoController::class, 'show']);
    Route::put('grupos-recursos/{id}', [GrupoRecursoController::class, 'update']);
    Route::delete('grupos-recursos/{id}', [GrupoRecursoController::class, 'destroy']);

    // Recursos asignados a un grupo (flujo centrado en grupo)
    Route::get('grupos-recursos/{grupoId}/recursos', [AsignacionRecursoGrupoController::class, 'indexPorGrupo']);
    Route::post('grupos-recursos/{grupoId}/recursos', [AsignacionRecursoGrupoController::class, 'storeEnGrupo']);
    Route::put('grupos-recursos/{grupoId}/recursos/{recursoId}', [AsignacionRecursoGrupoController::class, 'updateEnGrupo']);
    Route::delete('grupos-recursos/{grupoId}/recursos/{recursoId}', [AsignacionRecursoGrupoController::class, 'destroyDeGrupo']);

    // Actividades de un grupo
    Route::get('grupos-recursos/{grupoRecursoId}/actividades', [ActividadController::class, 'index']);

    // TDABC: Recursos (catálogo global)
    Route::get('recursos', [RecursoController::class, 'index']);
    Route::post('recursos', [RecursoController::class, 'store']);
    Route::put('recursos/{id}', [RecursoController::class, 'update']);
    Route::delete('recursos/{id}', [RecursoController::class, 'destroy']);

    // Asignaciones de un recurso a grupos (flujo centrado en recurso)
    Route::get('recursos/{recursoId}/asignaciones', [AsignacionRecursoGrupoController::class, 'index']);
    Route::put('recursos/{recursoId}/asignaciones', [AsignacionRecursoGrupoController::class, 'sync']);

    // TDABC: Actividades 
    Route::post('actividades', [ActividadController::class, 'store']);
    Route::get('actividades/{id}', [ActividadController::class, 'show']);
    Route::put('actividades/{id}', [ActividadController::class, 'update']);
    Route::delete('actividades/{id}', [ActividadController::class, 'destroy']);

    // Inductores de una actividad
    Route::get('actividades/{actividadId}/inductores', [ActividadInductorTiempoController::class, 'index']);
    Route::post('actividades/{actividadId}/inductores', [ActividadInductorTiempoController::class, 'store']);
    Route::put('actividades/{actividadId}/inductores/{inductorId}', [ActividadInductorTiempoController::class, 'update']);
    Route::delete('actividades/{actividadId}/inductores/{inductorId}', [ActividadInductorTiempoController::class, 'destroy']);

    // TDABC: Inductores de Tiempo (catálogo global)
    Route::get('inductores-tiempo', [InductorTiempoController::class, 'index']);
    Route::post('inductores-tiempo', [InductorTiempoController::class, 'store']);
    Route::get('inductores-tiempo/{id}', [InductorTiempoController::class, 'show']);
    Route::put('inductores-tiempo/{id}', [InductorTiempoController::class, 'update']);
    Route::delete('inductores-tiempo/{id}', [InductorTiempoController::class, 'destroy']);

    // Categorias de Producto
    Route::get('categorias-producto', [CategoriaProductoController::class, 'index']);
    Route::post('categorias-producto', [CategoriaProductoController::class, 'store']);
    Route::get('categorias-producto/{id}', [CategoriaProductoController::class, 'show']);
    Route::put('categorias-producto/{id}', [CategoriaProductoController::class, 'update']);
    Route::delete('categorias-producto/{id}', [CategoriaProductoController::class, 'destroy']);

    // Productos
    Route::get('productos', [ProductoController::class, 'index']);
    Route::post('productos', [ProductoController::class, 'store']);
    Route::get('productos/{id}', [ProductoController::class, 'show']);
    Route::put('productos/{id}', [ProductoController::class, 'update']);
    Route::delete('productos/{id}', [ProductoController::class, 'destroy']);
    Route::post('productos/{id}/calcular-costo', [ProductoController::class, 'calcularCosto']);

    // Actividades de un producto
    Route::get('productos/{productoId}/actividades', [ProductoActividadController::class, 'index']);
    Route::post('productos/{productoId}/actividades', [ProductoActividadController::class, 'store']);
    Route::delete('productos/{productoId}/actividades/{actividadId}', [ProductoActividadController::class, 'destroy']);

    // Inductores configurados para una asignación producto-actividad
    Route::get('producto-actividades/{productoActividadId}/inductores', [ProductoActividadValorInductorController::class, 'index']);
    Route::post('producto-actividades/{productoActividadId}/inductores', [ProductoActividadValorInductorController::class, 'store']);
    Route::put('producto-actividades/{productoActividadId}/inductores/{inductorId}', [ProductoActividadValorInductorController::class, 'update']);
    Route::delete('producto-actividades/{productoActividadId}/inductores/{inductorId}', [ProductoActividadValorInductorController::class, 'destroy']);
});

Route::get('p',function(){
    return Departamento::all();
});