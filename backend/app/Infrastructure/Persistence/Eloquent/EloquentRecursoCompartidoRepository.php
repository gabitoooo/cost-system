<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\RecursoCompartido\RecursoCompartido;
use App\Domain\RecursoCompartido\RecursoCompartidoRepository;
use App\Infrastructure\Persistence\Models\RecursoCompartido as RecursoCompartidoModel;

class EloquentRecursoCompartidoRepository implements RecursoCompartidoRepository
{
    public function findById(int $id): ?RecursoCompartido
    {
        $model = RecursoCompartidoModel::find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function findByDepartamento(int $departamentoId): array
    {
        return RecursoCompartidoModel::where('departamento_id', $departamentoId)
            ->get()
            ->map(fn($m) => $this->toEntity($m))
            ->all();
    }

    public function save(RecursoCompartido $recurso): RecursoCompartido
    {
        $model = $recurso->id === 0
            ? new RecursoCompartidoModel()
            : RecursoCompartidoModel::findOrFail($recurso->id);

        $model->departamento_id = $recurso->departamentoId;
        $model->nombre          = $recurso->nombre;
        $model->tipo            = $recurso->tipo;
        $model->costo_mensual   = $recurso->costoMensual;
        $model->save();

        return $this->toEntity($model);
    }

    public function delete(int $id): void
    {
        RecursoCompartidoModel::findOrFail($id)->delete();
    }

    private function toEntity(RecursoCompartidoModel $model): RecursoCompartido
    {
        return new RecursoCompartido(
            id: $model->id,
            departamentoId: $model->departamento_id,
            nombre: $model->nombre,
            tipo: $model->tipo,
            costoMensual: (float) $model->costo_mensual,
        );
    }
}
