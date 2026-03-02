<?php

namespace App\Domain\Usuario;

class Usuario
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $email,
        public readonly array $roles = [],
    ) {}
}
