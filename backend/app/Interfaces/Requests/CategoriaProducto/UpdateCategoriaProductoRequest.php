<?php

namespace App\Interfaces\Requests\CategoriaProducto;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoriaProductoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nombre'      => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
        ];
    }
}
