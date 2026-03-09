<?php

namespace App\Interfaces\Requests\AsignacionRecursoGrupo;

use Illuminate\Foundation\Http\FormRequest;

class ActualizarPorcentajeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'porcentaje' => ['required', 'numeric', 'min:0.01', 'max:100'],
        ];
    }
}
