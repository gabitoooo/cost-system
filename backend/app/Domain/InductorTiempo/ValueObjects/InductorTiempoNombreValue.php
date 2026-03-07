<?php

namespace App\Domain\InductorTiempo\ValueObjects;

use App\Domain\InductorTiempo\Exceptions\InductorTiempoDatoInvalidoException;

class InductorTiempoNombreValue
{
    public readonly string $valor;

    public function __construct(string $valor)
    {
        $valor = trim($valor);

        if ($valor === '') {
            throw new InductorTiempoDatoInvalidoException('nombre', $valor);
        }

        if (strlen($valor) > 255) {
            throw new InductorTiempoDatoInvalidoException('nombre', $valor);
        }

        $this->valor = $valor;
    }

    public function __toString(): string
    {
        return $this->valor;
    }
}
