<?php

namespace App\Interfaces\Requests\Costeo;

use Illuminate\Foundation\Http\FormRequest;

class CalcularCostoProductoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'cantidad_pedido' => ['required', 'integer', 'min:1'],
        ];
    }
}
