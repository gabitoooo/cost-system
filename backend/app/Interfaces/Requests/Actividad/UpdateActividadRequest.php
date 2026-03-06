<?php

namespace App\Interfaces\Requests\Actividad;

use Illuminate\Foundation\Http\FormRequest;

class UpdateActividadRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nombre'              => ['required', 'string', 'max:255'],
            'tiempo_base_minutos' => ['required', 'numeric', 'min:0'],
        ];
    }
}
