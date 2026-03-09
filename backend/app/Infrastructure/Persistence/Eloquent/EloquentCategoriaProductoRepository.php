<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\CategoriaProducto\CategoriaProducto;
use App\Domain\CategoriaProducto\CategoriaProductoRepository;
use App\Infrastructure\Persistence\Models\CategoriaProducto as CategoriaProductoModel;

class EloquentCategoriaProductoRepository implements CategoriaProductoRepository
{
    public function findAll(): array
    {
        return CategoriaProductoModel::all()
            ->map(fn($m) => $this->toEntity($m))
            ->all();
    }

    public function findById(int $id): ?CategoriaProducto
    {
        $model = CategoriaProductoModel::find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function save(CategoriaProducto $categoria): CategoriaProducto
    {
        $model = $categoria->id === 0
            ? new CategoriaProductoModel()
            : CategoriaProductoModel::findOrFail($categoria->id);

        $model->nombre      = $categoria->nombre;
        $model->descripcion = $categoria->descripcion;
        $model->save();

        return $this->toEntity($model);
    }

    public function delete(int $id): void
    {
        CategoriaProductoModel::findOrFail($id)->delete();
    }

    private function toEntity(CategoriaProductoModel $model): CategoriaProducto
    {
        return new CategoriaProducto(
            id: $model->id,
            nombre: $model->nombre,
            descripcion: $model->descripcion,
        );
    }
}
