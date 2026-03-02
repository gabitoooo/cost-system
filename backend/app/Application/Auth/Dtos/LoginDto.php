<?php

namespace App\Application\Auth\Dtos;

class LoginDto
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {}
}
