<?php

namespace App\Domain\CategoriaProducto;

interface CategoriaProductoRepository
{
    public function findAll(): array;
    public function findById(int $id): ?CategoriaProducto;
    public function save(CategoriaProducto $categoria): CategoriaProducto;
    public function delete(int $id): void;
}
