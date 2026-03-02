<?php

namespace App\Application\Auth;

use App\Domain\Usuario\TokenService;

class CerrarSesionUseCase
{
    public function __construct(
        private readonly TokenService $tokenService,
    ) {}

    public function ejecutar(int $tokenId): void
    {
        $this->tokenService->revocarToken($tokenId);
    }
}
