<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Departamento\Departamento;
use App\Domain\Departamento\DepartamentoRepository;
use App\Infrastructure\Persistence\Models\Departamento as DepartamentoModel;

class EloquentDepartamentoRepository implements DepartamentoRepository
{
    public function findAll(): array
    {
        return DepartamentoModel::all()
            ->map(fn($m) => $this->toEntity($m))
            ->all();
    }

    public function findById(int $id): ?Departamento
    {
        $model = DepartamentoModel::find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function save(Departamento $departamento): Departamento
    {
        $model = $departamento->id === 0
            ? new DepartamentoModel()
            : DepartamentoModel::findOrFail($departamento->id);

        $model->nombre = $departamento->nombre;
        $model->save();

        return $this->toEntity($model);
    }

    public function delete(int $id): void
    {
        DepartamentoModel::findOrFail($id)->delete();
    }

    private function toEntity(DepartamentoModel $model): Departamento
    {
        return new Departamento(
            id: $model->id,
            nombre: $model->nombre,
        );
    }
}
