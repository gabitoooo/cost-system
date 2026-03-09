<?php

namespace App\Interfaces\Controllers;

use App\Application\Auth\AutenticarUsuarioUseCase;
use App\Application\Auth\CerrarSesionUseCase;
use App\Application\Auth\Dtos\LoginDto;
use App\Domain\Usuario\Exceptions\CredencialesInvalidasException;
use App\Infrastructure\Persistence\Models\User;
use App\Interfaces\Requests\Auth\LoginRequest;
use App\Interfaces\Resources\Usuario\UsuarioResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly AutenticarUsuarioUseCase $autenticarUsuario,
        private readonly CerrarSesionUseCase $cerrarSesion,
    ) {}

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $dto = new LoginDto(
                email: $request->validated('email'),
                password: $request->validated('password'),
            );

            $result = $this->autenticarUsuario->ejecutar($dto);

            return response()->json(['data' =>[
                'token' => $result->token,
                'user'  => new UsuarioResource($result->usuario),
            ]]);
        } catch (CredencialesInvalidasException $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        // El ID del token activo es información HTTP → se extrae aquí en la capa Interfaces
        $this->cerrarSesion->ejecutar($request->user()->currentAccessToken()->id);

        return response()->json(['message' => 'Sesión cerrada correctamente.']);
    }

    public function me(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        return response()->json(['data' => [
            'user' => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'roles' => $user->getRoleNames()->toArray(),
            ],
        ]]);
    }
}
