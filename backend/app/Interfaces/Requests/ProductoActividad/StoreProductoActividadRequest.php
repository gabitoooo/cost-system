<?php

namespace App\Interfaces\Requests\ProductoActividad;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductoActividadRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'actividad_id' => ['required', 'integer'],
        ];
    }
}
