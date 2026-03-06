<?php

namespace App\Interfaces\Requests\Recurso;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRecursoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nombre'        => ['required', 'string', 'max:255'],
            'tipo'          => ['required', Rule::in(['humano', 'maquina', 'infraestructura'])],
            'costo_mensual' => ['required', 'numeric', 'min:0'],
        ];
    }
}
