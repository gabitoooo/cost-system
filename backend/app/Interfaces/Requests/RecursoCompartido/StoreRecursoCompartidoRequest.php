<?php

namespace App\Interfaces\Requests\RecursoCompartido;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRecursoCompartidoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'departamento_id' => ['required', 'integer'],
            'nombre'          => ['required', 'string', 'max:255'],
            'tipo'            => ['required', Rule::in(['infraestructura', 'servicio'])],
            'costo_mensual'   => ['required', 'numeric', 'min:0'],
        ];
    }
}
