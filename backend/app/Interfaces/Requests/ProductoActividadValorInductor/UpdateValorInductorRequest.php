<?php

namespace App\Interfaces\Requests\ProductoActividadValorInductor;

use Illuminate\Foundation\Http\FormRequest;

class UpdateValorInductorRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'beta_minutos' => ['required', 'numeric', 'min:0'],
            'valor_x'      => ['nullable', 'numeric'],
            'tamano_lote'  => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
