<?php

namespace App\Interfaces\Requests\InductorTiempo;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInductorTiempoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nombre'       => ['required', 'string', 'max:255'],
            'descripcion'  => ['nullable', 'string'],
            'tipo_calculo' => ['required', Rule::in(['manual', 'por_cantidad', 'por_lote'])],
        ];
    }
}
