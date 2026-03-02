<?php

namespace App\Application\Auth;

use App\Application\Auth\Dtos\LoginDto;
use App\Application\Auth\Dtos\LoginResultDto;
use App\Domain\Usuario\AutenticacionService;
use App\Domain\Usuario\Exceptions\CredencialesInvalidasException;
use App\Domain\Usuario\TokenService;

class AutenticarUsuarioUseCase
{
    public function __construct(
        private readonly AutenticacionService $autenticacionService,
        private readonly TokenService $tokenService,
    ) {}

    /**
     * @throws CredencialesInvalidasException
     */
    public function ejecutar(LoginDto $dto): LoginResultDto
    {
        $usuario = $this->autenticacionService->verificarCredenciales($dto->email, $dto->password);

        if (! $usuario) {
            throw new CredencialesInvalidasException();
        }

        $token = $this->tokenService->crearToken($usuario->id);

        return new LoginResultDto(token: $token, usuario: $usuario);
    }
}
