<?php

use App\Infrastructure\Persistence\Models\Departamento;
use App\Infrastructure\Persistence\Models\User;
use App\Interfaces\Controllers\ActividadController;
use App\Interfaces\Controllers\ActividadInductorTiempoController;
use App\Interfaces\Controllers\AsignacionCostoCompartidoController;
use App\Interfaces\Controllers\AuthController;
use App\Interfaces\Controllers\DepartamentoController;
use App\Interfaces\Controllers\GrupoRecursoController;
use App\Interfaces\Controllers\InductorTiempoController;
use App\Interfaces\Controllers\RecursoCompartidoController;
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

    // Recursos compartidos de un departamento
    Route::get('departamentos/{departamentoId}/recursos-compartidos', [RecursoCompartidoController::class, 'index']);

    // TDABC: Grupos de Recursos 
    Route::post('grupos-recursos', [GrupoRecursoController::class, 'store']);
    Route::get('grupos-recursos/{id}', [GrupoRecursoController::class, 'show']);
    Route::put('grupos-recursos/{id}', [GrupoRecursoController::class, 'update']);
    Route::delete('grupos-recursos/{id}', [GrupoRecursoController::class, 'destroy']);

    // Recursos de un grupo
    Route::get('grupos-recursos/{grupoRecursoId}/recursos', [RecursoController::class, 'index']);

    // Actividades de un grupo
    Route::get('grupos-recursos/{grupoRecursoId}/actividades', [ActividadController::class, 'index']);

    // TDABC: Recursos 
    Route::post('recursos', [RecursoController::class, 'store']);
    Route::put('recursos/{id}', [RecursoController::class, 'update']);
    Route::delete('recursos/{id}', [RecursoController::class, 'destroy']);

    // TDABC: Recursos Compartidos 
    Route::post('recursos-compartidos', [RecursoCompartidoController::class, 'store']);
    Route::put('recursos-compartidos/{id}', [RecursoCompartidoController::class, 'update']);
    Route::delete('recursos-compartidos/{id}', [RecursoCompartidoController::class, 'destroy']);

    // Asignaciones de un recurso compartido
    Route::get('recursos-compartidos/{recursoCompartidoId}/asignaciones', [AsignacionCostoCompartidoController::class, 'index']);
    Route::put('recursos-compartidos/{recursoCompartidoId}/asignaciones', [AsignacionCostoCompartidoController::class, 'sync']);

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
});

Route::get('p',function(){
    return Departamento::all();
});