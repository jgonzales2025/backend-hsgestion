<?php

namespace App\Modules\DispatchNotes\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestUpdate extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta solicitud.
     */
    public function authorize(): bool
    {
        return true;
    }
   protected function prepareForValidation(): void
    {
        $payload = auth('api')->payload();
        $companyId = $payload->get('company_id');

        $this->merge([
            // 'user_id' => auth('api')->id(),
            'cia_id' => $companyId,
        ]);
    }
    /**
     * Reglas de validación para crear una nota de despacho.
     */
public function rules(): array
{
    return [
        'cia_id' => 'integer|exists:companies,id',
        'branch_id' => ['required', 'integer', 'exists:branches,id'],
        'serie' => ['required', 'string', 'max:10'],
        'emission_reason_id' => ['required', 'integer', 'exists:emission_reasons,id'],
        'description' => ['nullable', 'string', 'max:255'],
        'destination_branch_id' => ['required', 'integer', 'exists:branches,id'],
        'destination_address_customer' => ['required', 'string', 'max:255'],
        'transport_id' => ['required', 'integer'],
        'observations' => ['nullable', 'string', 'max:255'],
        'num_orden_compra' => ['nullable', 'string', 'max:50'],
        'doc_referencia' => ['nullable', 'string', 'max:50'],
        'num_referencia' => ['nullable', 'string', 'max:50'],
        'date_referencia' => ['nullable', 'date'],
        'status' => ['required', 'boolean'],
        'cod_conductor' => ['required', 'integer', 'exists:drivers,id'],
        'license_plate' => ['required', 'string', 'max:15'],
        'total_weight' => ['required', 'numeric'],
        'transfer_type' => ['required', 'string', 'max:50'],
        'vehicle_type' => ['required', 'boolean'],
        'document_type_id' => ['required', 'integer', 'exists:document_types,id'],
        'destination_branch_client_id' => ['required', 'integer', 'exists:branches,id'],
        'customer_id' => ['required', 'integer', 'exists:customers,id'],
        // Validación para los artículos
        'dispatch_articles' => ['required', 'array', 'min:1'],
        'dispatch_articles.*.article_id' => ['required', 'integer', 'exists:articles,id'],
        'dispatch_articles.*.quantity' => ['required', 'numeric', 'min:0.01'],
        'dispatch_articles.*.weight' => ['required', 'numeric', 'min:0'],
        'dispatch_articles.*.saldo' => ['required', 'numeric', 'min:0'],
        'dispatch_articles.*.name' => ['required', 'string', 'max:255'],
        'dispatch_articles.*.subtotal_weight' => ['required', 'numeric', 'min:0']
    ];
}
    /**
     * Mensajes personalizados (opcional).
     */
    public function messages(): array
    {
        return [
            'cia_id.required' => 'El campo compañía es obligatorio.',
            'branch_id.required' => 'El campo sucursal es obligatorio.',
            'serie.required' => 'Debe indicar la serie del documento.',
            'date.required' => 'Debe ingresar una fecha.',
            'destination_address_customer.required' => 'Debe ingresar la dirección de destino.',
            'license_plate.required' => 'Debe ingresar la placa del vehículo.',
            'total_weight.required' => 'Debe ingresar el peso total.',
        ];
    }
}
