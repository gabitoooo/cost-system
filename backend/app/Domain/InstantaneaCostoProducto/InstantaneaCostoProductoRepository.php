<?php

namespace App\Domain\InstantaneaCostoProducto;

interface InstantaneaCostoProductoRepository
{
    public function save(InstantaneaCostoProducto $instantanea): InstantaneaCostoProducto;
}
