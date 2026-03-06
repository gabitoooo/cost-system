<?php

namespace App\Domain\ActividadInductorTiempo;

use App\Domain\ActividadInductorTiempo\Exceptions\TamanoLoteRequeridoException;

class ActividadInductorTiempo
{
    public function __construct(
        public readonly int    $id,
        public readonly int    $actividadId,
        public readonly int    $inductorTiempoId,
        public readonly float  $betaMinutos,
        public readonly ?float $tamanoLote,
    ) {}

    /**
     * Factory que aplica la regla de negocio: tamano_lote es obligatorio
     * cuando el tipo de cálculo del inductor es 'por_lote'.
     *
     * @throws TamanoLoteRequeridoException
     */
    public static function crear(
        int    $actividadId,
        int    $inductorTiempoId,
        string $tipoCalculo,
        float  $betaMinutos,
        ?float $tamanoLote,
    ): self {
        if ($tipoCalculo === 'por_lote' && $tamanoLote === null) {
            throw new TamanoLoteRequeridoException();
        }

        return new self(
            id: 0,
            actividadId: $actividadId,
            inductorTiempoId: $inductorTiempoId,
            betaMinutos: $betaMinutos,
            tamanoLote: $tamanoLote,
        );
    }
}
