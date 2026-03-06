<?php

namespace App\Interfaces\Requests\GrupoRecurso;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGrupoRecursoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nombre'                     => ['required', 'string', 'max:255'],
            'capacidad_practica_minutos' => ['required', 'numeric', 'min:0.01'],
        ];
    }
}
