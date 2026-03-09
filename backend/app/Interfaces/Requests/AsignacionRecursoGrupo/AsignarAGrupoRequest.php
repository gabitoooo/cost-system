<?php

namespace App\Interfaces\Requests\AsignacionRecursoGrupo;

use Illuminate\Foundation\Http\FormRequest;

class AsignarAGrupoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'recurso_id' => ['required', 'integer'],
            'porcentaje' => ['required', 'numeric', 'min:0.01', 'max:100'],
        ];
    }
}
