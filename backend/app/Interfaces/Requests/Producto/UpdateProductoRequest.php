<?php

namespace App\Interfaces\Requests\Producto;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'categoria_id'           => ['nullable', 'integer'],
            'nombre'                 => ['required', 'string', 'max:255'],
            'descripcion'            => ['nullable', 'string'],
            'costo_material_directo' => ['required', 'numeric', 'min:0'],
        ];
    }
}
