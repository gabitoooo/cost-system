<?php

namespace App\Domain\Usuario;

interface AutenticacionService
{
    /**
     * Verifica las credenciales y retorna el Usuario si son válidas, null si no.
     */
    public function verificarCredenciales(string $email, string $password): ?Usuario;
}
