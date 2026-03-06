<?php

namespace App\Interfaces\Requests\RecursoCompartido;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRecursoCompartidoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nombre'        => ['required', 'string', 'max:255'],
            'tipo'          => ['required', Rule::in(['infraestructura', 'servicio'])],
            'costo_mensual' => ['required', 'numeric', 'min:0'],
        ];
    }
}
