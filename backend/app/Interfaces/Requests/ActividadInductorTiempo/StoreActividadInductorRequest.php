<?php

namespace App\Interfaces\Requests\ActividadInductorTiempo;

use Illuminate\Foundation\Http\FormRequest;

class StoreActividadInductorRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'inductor_tiempo_id' => ['required', 'integer'],
            'beta_minutos'       => ['required', 'numeric', 'min:0'],
            'tamano_lote'        => ['nullable', 'numeric', 'min:0.01'],
        ];
    }
}
