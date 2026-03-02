<?php

namespace App\Domain\Usuario;

interface UsuarioRepository
{
    public function findByEmail(string $email): ?Usuario;

    public function findById(int $id): ?Usuario;
}
