<?php

namespace App\Domain\DetalleInstantaneaCosto;

interface DetalleInstantaneaCostoRepository
{
    public function save(DetalleInstantaneaCosto $detalle): DetalleInstantaneaCosto;
}
