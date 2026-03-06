<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\InductorTiempo\InductorTiempo;
use App\Domain\InductorTiempo\InductorTiempoRepository;
use App\Infrastructure\Persistence\Models\InductorTiempo as InductorTiempoModel;

class EloquentInductorTiempoRepository implements InductorTiempoRepository
{
    public function findAll(): array
    {
        return InductorTiempoModel::all()
            ->map(fn($m) => $this->toEntity($m))
            ->all();
    }

    public function findById(int $id): ?InductorTiempo
    {
        $model = InductorTiempoModel::find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function save(InductorTiempo $inductor): InductorTiempo
    {
        $model = $inductor->id === 0
            ? new InductorTiempoModel()
            : InductorTiempoModel::findOrFail($inductor->id);

        $model->nombre       = $inductor->nombre;
        $model->descripcion  = $inductor->descripcion;
        $model->tipo_calculo = $inductor->tipoCalculo;
        $model->save();

        return $this->toEntity($model);
    }

    public function delete(int $id): void
    {
        InductorTiempoModel::findOrFail($id)->delete();
    }

    private function toEntity(InductorTiempoModel $model): InductorTiempo
    {
        return new InductorTiempo(
            id: $model->id,
            nombre: $model->nombre,
            descripcion: $model->descripcion,
            tipoCalculo: $model->tipo_calculo,
        );
    }
}
