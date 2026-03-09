<?php

namespace App\Interfaces\Requests\AsignacionRecursoGrupo;

use Illuminate\Foundation\Http\FormRequest;

class SyncAsignacionesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'asignaciones'                     => ['required', 'array', 'min:1'],
            'asignaciones.*.grupo_recursos_id' => ['required', 'integer'],
            'asignaciones.*.porcentaje'        => ['required', 'numeric', 'min:0.01', 'max:100'],
        ];
    }
}
