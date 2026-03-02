<?php

namespace App\Providers;

use App\Domain\Usuario\TokenService;
use App\Domain\Usuario\AutenticacionService;
use App\Domain\Usuario\UsuarioRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentUsuarioRepository;
use App\Infrastructure\Services\EloquentAutenticacionService;
use App\Infrastructure\Services\SanctumTokenService;
use Illuminate\Support\ServiceProvider;

class UsuarioServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UsuarioRepository::class, EloquentUsuarioRepository::class);
        $this->app->bind(AutenticacionService::class, EloquentAutenticacionService::class);
        $this->app->bind(TokenService::class, SanctumTokenService::class);
    }
}
