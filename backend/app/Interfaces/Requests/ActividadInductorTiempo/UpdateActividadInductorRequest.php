<?php

namespace App\Interfaces\Requests\ActividadInductorTiempo;

use Illuminate\Foundation\Http\FormRequest;

class UpdateActividadInductorRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'beta_minutos' => ['required', 'numeric', 'min:0'],
            'tamano_lote'  => ['nullable', 'numeric', 'min:0.01'],
        ];
    }
}
