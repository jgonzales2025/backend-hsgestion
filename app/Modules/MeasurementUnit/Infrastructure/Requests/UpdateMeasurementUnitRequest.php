<?php

namespace App\Modules\MeasurementUnit\Infrastructure\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateMeasurementUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $measurementUnitId = $this->route('id');

        return [
            'id' => 'required|exists:measurement_units,id',
            'name' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('measurement_units', 'name')->ignore($measurementUnitId),
            ],
            'abbreviation' => [
                'sometimes',
                'string',
                'max:50',
                Rule::unique('measurement_units', 'abbreviation')->ignore($measurementUnitId),
            ]
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        // Si el ID no existe, mostrar solo ese error
        if ($errors->has('id')) {
            $response = response()->json([
                'message' => $errors->first('id'),
                'errors' => [
                    'id' => $errors->get('id')
                ]
            ], 422);

            throw new HttpResponseException($response);
        }

        parent::failedValidation($validator);
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'id' => $this->route('id'),
        ]);
    }

    public function messages(): array
    {
        return [
            'id.exists' => 'La unidad de medida no existe.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede exceder los 255 caracteres.',
            'name.unique' => 'El nombre ya está registrado.',

            'abbreviation.string' => 'La abreviatura debe ser una cadena de texto.',
            'abbreviation.max' => 'La abreviatura no puede exceder los 50 caracteres.',
            'abbreviation.unique' => 'La abreviatura ya está registrada.'
        ];
    }
}
