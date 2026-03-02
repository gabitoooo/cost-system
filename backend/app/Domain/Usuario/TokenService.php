<?php

namespace App\Domain\Usuario;

interface TokenService
{
    /**
     * Genera un nuevo token para el usuario dado y retorna el texto plano.
     */
    public function crearToken(int $userId): string;

    /**
     * Revoca el token identificado por su ID.
     */
    public function revocarToken(int $tokenId): void;
}
