<?php

namespace App\Modules\DispatchNotes\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestStore extends FormRequest
{ 
    public function authorize(): bool
    {
        return true;
    }
    protected function prepareForValidation(): void
    {
        $payload = auth('api')->payload();
        $companyId = $payload->get('company_id');

        $this->merge([ 
            'cia_id' => $companyId,
        ]);
    }

    public function rules(): array
    {
        return [
            'cia_id' => 'integer|exists:companies,id',
            'branch_id' => ['required', 'integer', 'exists:branches,id'],
            'serie' => ['required', 'string', 'max:10'],
            'emission_reason_id' => ['required', 'integer', 'exists:emission_reasons,id'],
            'description' => ['nullable', 'string', 'max:255'],
            'destination_branch_id' => ['nullable', 'integer', 'exists:branches,id'],
            'transport_id' => ['nullable', 'integer'],
            'observations' => ['nullable', 'string', 'max:255'],
            'num_orden_compra' => ['nullable', 'string', 'max:50'],
            'doc_referencia' => ['nullable', 'string', 'max:50'],
            'num_referencia' => ['nullable', 'string', 'max:50'],
            'date_referencia' => ['nullable', 'date'],
            'status' => ['required', 'boolean'],
            'cod_conductor' => ['nullable', 'integer', 'exists:drivers,id'],
            'license_plate' =>'string',
            'total_weight' => ['required', 'numeric'],
            'transfer_type' => ['required', 'string', 'max:50'],
            'vehicle_type' => ['required', 'boolean'],
            'document_type_id' => ['required', 'integer', 'exists:document_types,id'],
            'destination_branch_client_id' => ['nullable', 'integer', 'exists:customer_addresses,id'],
            'dispatch_articles' => 'required|array|min:1',
            'customer_id' => 'required|integer|exists:customers,id',
            'supplier_id' => 'nullable|integer|exists:customers,id',
            'address_supplier_id' => 'nullable|integer|exists:customers,id'
        ];
    } 
    public function messages(): array
    {
        return [
            'cia_id.required' => 'El campo compañía es obligatorio.',
            'branch_id.required' => 'El campo sucursal es obligatorio.',
            'serie.required' => 'Debe indicar la serie del documento.',
            'date.required' => 'Debe ingresar una fecha.',
            'total_weight.required' => 'Debe ingresar el peso total.',
            'customer_id.exists' =>'Selecciona un cliente' ,
            // 'destination_branch_client_id.exists' =>'la direccion seleccionada no existe en el sistema',
            // 'address_supplier_id.exists' => 'El cliente seleccionado no existe',
            // 'supplier_id.exists' => 'El cliente seleccionado no existe',
            // 'cod_conductor.exists' => 'El conductor seleccionado no existe',
            // 'destination_branch_id.exists'=>'la destanacion de la sucursal no existe en el sistema',
        ];
    }
}
