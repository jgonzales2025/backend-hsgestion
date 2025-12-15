<?php

namespace App\Modules\PettyCashReceipt\Infrastructure\Request;

use Illuminate\Foundation\Http\FormRequest;

class CreatePettyCashReceiptRequest extends FormRequest
{
    public function prepareForValidation(): void
    {
        $companyId = request()->get('company_id');

        $this->merge([
            'company_id' => $companyId,
        ]);
    }

    public function rules(): array
    {
        return [
            'company_id' => 'nullable|integer',
            'document_type' => 'required|integer',
            'series' => 'required|string',
            'correlative' => 'string',
            'date' => 'required|string',
            'delivered_to' => 'required|string',
            'reason_code' => 'required|integer',
            'currency_type' => 'required|integer',
            'amount' => 'required|numeric',
            'observation' => 'nullable|string',
            'status' => 'nullable|integer',
            'created_by' => 'nullable|integer',
            'created_at_manual' => 'nullable|string',
            'updated_by' => 'nullable|integer',
            'updated_at_manual' => 'nullable|string',
            'branch_id' => 'required|integer'
        ];
    }
    public function messages():array{
        return [
            'document_type.required' => 'El tipo de documento es obligatorio',
            'series.required' => 'La serie es obligatoria',
            'date.required' => 'La fecha es obligatoria',
            'delivered_to.required' => 'El destinatario es obligatorio',
            'reason_code.required' => 'El codigo de motivo es obligatorio',
            'currency_type.required' => 'El tipo de moneda es obligatorio',
            'amount.required' => 'El monto es obligatorio',
            'amount.numeric' => 'El monto debe ser un numero',
            'observation.string' => 'La observacion debe ser una cadena de texto',
            'status.integer' => 'El estado debe ser un numero',
            'created_by.integer' => 'El creado por debe ser un numero',
            'created_at_manual.string' => 'La fecha de creacion manual debe ser una cadena de texto',
            'updated_by.integer' => 'El actualizado por debe ser un numero',
            'updated_at_manual.string' => 'La fecha de actualizacion manual debe ser una cadena de texto',
            'branch_id.required' => 'El id de sucursal es obligatorio',
            'branch_id.integer' => 'El id de sucursal debe ser un numero',
        ];
    }
}