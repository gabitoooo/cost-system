<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Producto\Producto;
use App\Domain\Producto\ProductoRepository;
use App\Infrastructure\Persistence\Models\Producto as ProductoModel;

class EloquentProductoRepository implements ProductoRepository
{
    public function findAll(): array
    {
        return ProductoModel::all()
            ->map(fn($m) => $this->toEntity($m))
            ->all();
    }

    public function findById(int $id): ?Producto
    {
        $model = ProductoModel::find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function save(Producto $producto): Producto
    {
        $model = $producto->id === 0
            ? new ProductoModel()
            : ProductoModel::findOrFail($producto->id);

        $model->categoria_id            = $producto->categoriaId;
        $model->nombre                  = $producto->nombre;
        $model->descripcion             = $producto->descripcion;
        $model->costo_material_directo  = $producto->costoMaterialDirecto;
        $model->save();

        return $this->toEntity($model);
    }

    public function delete(int $id): void
    {
        ProductoModel::findOrFail($id)->delete();
    }

    private function toEntity(ProductoModel $model): Producto
    {
        return new Producto(
            id: $model->id,
            categoriaId: $model->categoria_id,
            nombre: $model->nombre,
            descripcion: $model->descripcion,
            costoMaterialDirecto: (float) $model->costo_material_directo,
        );
    }
}
