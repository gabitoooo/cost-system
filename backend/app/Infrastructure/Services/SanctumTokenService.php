<?php

namespace App\Infrastructure\Services;

use App\Domain\Usuario\TokenService;
use App\Infrastructure\Persistence\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

class SanctumTokenService implements TokenService
{
    public function crearToken(int $userId): string
    {
        /** @var User $user */
        $user = User::findOrFail($userId);

        return $user->createToken('api-token')->plainTextToken;
    }

    public function revocarToken(int $tokenId): void
    {
        PersonalAccessToken::findOrFail($tokenId)->delete();
    }
}
