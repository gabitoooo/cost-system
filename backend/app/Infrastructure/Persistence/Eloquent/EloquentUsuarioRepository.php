<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Usuario\Usuario;
use App\Domain\Usuario\UsuarioRepository;
use App\Infrastructure\Persistence\Models\User;

class EloquentUsuarioRepository implements UsuarioRepository
{
    public function findByEmail(string $email): ?Usuario
    {
        $user = User::where('email', $email)->first();

        return $user ? $this->toEntity($user) : null;
    }

    public function findById(int $id): ?Usuario
    {
        $user = User::find($id);

        return $user ? $this->toEntity($user) : null;
    }

    private function toEntity(User $user): Usuario
    {
        return new Usuario(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            roles: $user->getRoleNames()->toArray(),
        );
    }
}
