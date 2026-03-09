<?php

namespace App\Domain\Producto;

interface ProductoRepository
{
    public function findAll(): array;
    public function findById(int $id): ?Producto;
    public function save(Producto $producto): Producto;
    public function delete(int $id): void;
}
