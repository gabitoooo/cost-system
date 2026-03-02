<?php

namespace App\Infrastructure\Services;

use App\Domain\Usuario\AutenticacionService;
use App\Domain\Usuario\Usuario;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Support\Facades\Hash;

class EloquentAutenticacionService implements AutenticacionService
{
    public function verificarCredenciales(string $email, string $password): ?Usuario
    {
        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            return null;
        }

        return new Usuario(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            roles: $user->getRoleNames()->toArray(),
        );
    }
}
