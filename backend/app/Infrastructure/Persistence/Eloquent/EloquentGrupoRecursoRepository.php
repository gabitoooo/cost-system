<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\GrupoRecurso\GrupoRecurso;
use App\Domain\GrupoRecurso\GrupoRecursoRepository;
use App\Infrastructure\Persistence\Models\GrupoRecurso as GrupoRecursoModel;

class EloquentGrupoRecursoRepository implements GrupoRecursoRepository
{
    public function findById(int $id): ?GrupoRecurso
    {
        $model = GrupoRecursoModel::find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function findByDepartamento(int $departamentoId): array
    {
        return GrupoRecursoModel::where('departamento_id', $departamentoId)
            ->get()
            ->map(fn($m) => $this->toEntity($m))
            ->all();
    }

    public function save(GrupoRecurso $grupo): GrupoRecurso
    {
        $model = $grupo->id === 0
            ? new GrupoRecursoModel()
            : GrupoRecursoModel::findOrFail($grupo->id);

        $model->departamento_id            = $grupo->departamentoId;
        $model->nombre                     = $grupo->nombre;
        $model->capacidad_practica_minutos = $grupo->capacidadPracticaMinutos;
        $model->tasa_costo_por_minuto      = $grupo->tasaCostoPorMinuto;
        $model->save();

        return $this->toEntity($model);
    }

    public function delete(int $id): void
    {
        GrupoRecursoModel::findOrFail($id)->delete();
    }

    public function recalcularCcr(int $grupoId): GrupoRecurso
    {
        $modelo = GrupoRecursoModel::with([
            'recursos',
            'asignacionesCostoCompartido.recursoCompartido',
        ])->findOrFail($grupoId);

        // Infrastructure agrega los datos desde la BD
        $costoRecursos = (float) $modelo->recursos->sum('costo_mensual');

        $costoCompartido = (float) $modelo->asignacionesCostoCompartido->sum(
            fn($a) => $a->recursoCompartido->costo_mensual * ($a->porcentaje / 100)
        );

        // La fórmula CCR vive en el dominio
        $entidad = $this->toEntity($modelo);
        $tasa    = $entidad->calcularCcr($costoRecursos, $costoCompartido);

        $modelo->tasa_costo_por_minuto = $tasa;
        $modelo->save();

        return $this->toEntity($modelo);
    }

    private function toEntity(GrupoRecursoModel $model): GrupoRecurso
    {
        return new GrupoRecurso(
            id: $model->id,
            departamentoId: $model->departamento_id,
            nombre: $model->nombre,
            capacidadPracticaMinutos: (float) $model->capacidad_practica_minutos,
            tasaCostoPorMinuto: $model->tasa_costo_por_minuto !== null
                ? (float) $model->tasa_costo_por_minuto
                : null,
        );
    }
}
