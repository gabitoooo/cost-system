<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Recurso\Recurso;
use App\Domain\Recurso\RecursoRepository;
use App\Infrastructure\Persistence\Models\Recurso as RecursoModel;

class EloquentRecursoRepository implements RecursoRepository
{
    public function findById(int $id): ?Recurso
    {
        $model = RecursoModel::find($id);
        return $model ? $this->toEntity($model) : null;
    }

    public function findAll(): array
    {
        return RecursoModel::orderBy('nombre')
            ->get()
            ->map(fn($m) => $this->toEntity($m))
            ->all();
    }

    public function save(Recurso $recurso): Recurso
    {
        $model = $recurso->id === 0
            ? new RecursoModel()
            : RecursoModel::findOrFail($recurso->id);
        $model->nombre        = $recurso->nombre;
        $model->tipo          = $recurso->tipo;
        $model->costo_mensual = $recurso->costoMensual;
        $model->save();

        return $this->toEntity($model);
    }

    public function delete(int $id): void
    {
        RecursoModel::findOrFail($id)->delete();
    }

    private function toEntity(RecursoModel $model): Recurso
    {
        return new Recurso(
            id: $model->id,
            nombre: $model->nombre,
            tipo: $model->tipo,
            costoMensual: (float) $model->costo_mensual,
        );
    }
}
