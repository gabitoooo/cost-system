<?php

namespace App\Interfaces\Requests\Actividad;

use Illuminate\Foundation\Http\FormRequest;

class StoreActividadRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'grupo_recursos_id'   => ['required', 'integer'],
            'nombre'              => ['required', 'string', 'max:255'],
            'tiempo_base_minutos' => ['required', 'numeric', 'min:0'],
        ];
    }
}
