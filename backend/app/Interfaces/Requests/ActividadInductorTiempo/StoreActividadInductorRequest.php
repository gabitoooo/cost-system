<?php

namespace App\Interfaces\Requests\ActividadInductorTiempo;

use Illuminate\Foundation\Http\FormRequest;

class StoreActividadInductorRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'inductor_tiempo_id' => ['required', 'integer'],
        ];
    }
}
