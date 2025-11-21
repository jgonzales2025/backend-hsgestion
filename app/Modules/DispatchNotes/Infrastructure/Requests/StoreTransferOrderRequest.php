<?php

namespace App\Modules\DispatchNotes\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTransferOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'company_id' => 'integer|exists:companies,id',
            'branch_id' => ['required', 'integer', 'exists:branches,id'],
            'serie' => ['required', 'string', 'max:10'],
            'emission_reason_id' => ['required', 'integer', 'exists:emission_reasons,id'],
            'destination_branch_id' => ['required', 'integer', 'exists:branches,id'],
            'observations' => ['nullable', 'string', 'max:255'],
            'dispatch_articles' => 'required|array|min:1',
            'dispatch_articles.*.article_id' => 'required|integer|exists:articles,id',
            'dispatch_articles.*.name' => 'required|string',
            'dispatch_articles.*.quantity' => 'required|integer',
            'dispatch_articles.*.serials' => [
                'nullable',
                'array',
                function ($attribute, $value, $fail) {
                    $existing = \Illuminate\Support\Facades\DB::table('dispatch_article_serials')
                        ->whereIn('serial', $value)
                        ->value('serial');

                    if ($existing) {
                        $fail("Hay seriales que ya están siendo usados.");
                    }
                },
            ],
            'dispatch_articles.*.serials.*' => [
                'required',
                'distinct',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            // Company
            'company_id.integer' => 'El ID de la empresa debe ser un número entero.',
            'company_id.exists' => 'La empresa seleccionada no existe.',

            // Branch
            'branch_id.required' => 'La sucursal de origen es obligatoria.',
            'branch_id.integer' => 'El ID de la sucursal debe ser un número entero.',
            'branch_id.exists' => 'La sucursal seleccionada no existe.',

            // Serie
            'serie.required' => 'La serie es obligatoria.',
            'serie.string' => 'La serie debe ser una cadena de texto.',
            'serie.max' => 'La serie no puede tener más de 10 caracteres.',

            // Emission Reason
            'emission_reason_id.required' => 'El motivo de emisión es obligatorio.',
            'emission_reason_id.integer' => 'El ID del motivo de emisión debe ser un número entero.',
            'emission_reason_id.exists' => 'El motivo de emisión seleccionado no existe.',

            // Destination Branch
            'destination_branch_id.required' => 'La sucursal de destino es obligatoria.',
            'destination_branch_id.integer' => 'El ID de la sucursal de destino debe ser un número entero.',
            'destination_branch_id.exists' => 'La sucursal de destino seleccionada no existe.',

            // Observations
            'observations.string' => 'Las observaciones deben ser una cadena de texto.',
            'observations.max' => 'Las observaciones no pueden tener más de 255 caracteres.',

            // Dispatch Articles
            'dispatch_articles.required' => 'Debe agregar al menos un artículo al despacho.',
            'dispatch_articles.array' => 'Los artículos deben ser un arreglo.',
            'dispatch_articles.min' => 'Debe agregar al menos un artículo al despacho.',

            // Article ID
            'dispatch_articles.*.article_id.required' => 'El ID del artículo es obligatorio.',
            'dispatch_articles.*.article_id.integer' => 'El ID del artículo debe ser un número entero.',
            'dispatch_articles.*.article_id.exists' => 'El artículo seleccionado no existe.',

            // Article Name
            'dispatch_articles.*.name.required' => 'El nombre del artículo es obligatorio.',
            'dispatch_articles.*.name.string' => 'El nombre del artículo debe ser una cadena de texto.',

            // Quantity
            'dispatch_articles.*.quantity.required' => 'La cantidad del artículo es obligatoria.',
            'dispatch_articles.*.quantity.integer' => 'La cantidad debe ser un número entero.',

            // Serials
            'dispatch_articles.*.serials.array' => 'Los seriales deben ser un arreglo.',
            'dispatch_articles.*.serials.*.required' => 'El serial no puede estar vacío.',
            'dispatch_articles.*.serials.*.distinct' => 'Los seriales no pueden estar duplicados.',
            'dispatch_articles.*.serials.*.unique' => 'El serial ya está siendo usado.',
        ];
    }
}