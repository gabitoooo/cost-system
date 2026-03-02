<?php

namespace App\Application\Auth\Dtos;

use App\Domain\Usuario\Usuario;

class LoginResultDto
{
    public function __construct(
        public readonly string $token,
        public readonly Usuario $usuario,
    ) {}
}
