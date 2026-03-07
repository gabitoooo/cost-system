<?php

namespace App\Interfaces\Requests\InductorTiempo;

use App\Domain\InductorTiempo\Enums\InductorTiempoTipoCalculoEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInductorTiempoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nombre'       => ['required', 'string', 'max:255'],
            'descripcion'  => ['nullable', 'string'],
            'tipo_calculo' => ['required', Rule::enum(InductorTiempoTipoCalculoEnum::class)],
        ];
    }

    public function messages(): array
    {
        $valores = implode(', ', array_column(InductorTiempoTipoCalculoEnum::cases(), 'value'));

        return [
            'tipo_calculo.required' => 'El tipo de cálculo es obligatorio.',
            'tipo_calculo.enum'     => "El tipo de cálculo debe ser uno de: {$valores}.",
        ];
    }
}
