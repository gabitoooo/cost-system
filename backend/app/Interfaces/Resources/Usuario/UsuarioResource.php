<?php

namespace App\Interfaces\Resources\Usuario;

use App\Domain\Usuario\Usuario;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsuarioResource extends JsonResource
{
    public function __construct(private readonly Usuario $usuario) {}

    public function toArray(Request $request): array
    {
        return [
            'id'    => $this->usuario->id,
            'name'  => $this->usuario->name,
            'email' => $this->usuario->email,
            'roles' => $this->usuario->roles,
        ];
    }
}
