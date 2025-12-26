<?php

namespace App\Modules\DispatchNotes\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class RequestStore extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    protected function prepareForValidation(): void
    {
       
        $companyId = request()->get('company_id');

        $this->merge([
            'cia_id' => $companyId,
        ]);
    }

    public function rules(): array
    {
        return [
            'cia_id' => 'integer|exists:companies,id',
            'branch_id' => 'required|integer|exists:branches,id',
            'serie' => 'nullable|string|max:10',
            'emission_reason_id' => 'required|integer|exists:emission_reasons,id',
            'description' => 'nullable|string|max:255',
            'destination_branch_id' => 'nullable|integer|exists:branches,id',
            'transport_id' => 'nullable|integer',
            'observations' => 'nullable|string|max:255',
            'num_orden_compra' => 'nullable|string|max:50',
            'doc_referencia' => 'nullable|string|max:50',
            'num_referencia' => 'nullable|string|max:50',
            'date_referencia' => 'nullable|date',
            'cod_conductor' => 'nullable|integer|exists:drivers,id',
            'license_plate' => 'string',
            'total_weight' => 'required|numeric',
            'transfer_type' => 'required|int|in:1,2',
            'vehicle_type' => 'required|boolean',
            'reference_document_type_id' => 'nullable|integer|exists:document_types,id',
            'destination_branch_client_id' => 'nullable|integer|exists:customer_addresses,id',
            'dispatch_articles' => 'required|array|min:1',
            'dispatch_articles.*.article_id' => 'required|integer|exists:articles,id',
            'dispatch_articles.*.quantity' => 'required|integer',
            'dispatch_articles.*.weight' => 'required|numeric',
            'dispatch_articles.*.saldo' => 'required|numeric',
            'dispatch_articles.*.name' => 'required|string',
            'dispatch_articles.*.subtotal_weight' => 'required|numeric',
            'dispatch_articles.*.serials' => 'nullable|array',
            'dispatch_articles.*.serials.*' => 'required|distinct',
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
            'customer_id.exists' => 'Selecciona un cliente',
            'reference_document_type_id.required' => 'Debe seleccionar un tipo de documento.',
            'reference_document_type_id.exists' => 'Selecciona un tipo de documento',
            'destination_branch_client_id.required' => 'Debe seleccionar un cliente.',
            'destination_branch_client_id.exists' => 'Selecciona un cliente',

            //dispatch_articles
            'dispatch_articles.required' => 'Debe seleccionar al menos un artículo.',
            'dispatch_articles.array' => 'Los artículos deben ser un array.',
            'dispatch_articles.min' => 'Debe seleccionar al menos un artículo.',
            'dispatch_articles.*.article_id.required' => 'Debe seleccionar un artículo.',
            'dispatch_articles.*.article_id.exists' => 'Selecciona un artículo',
            'dispatch_articles.*.quantity.required' => 'Debe ingresar la cantidad.',
            'dispatch_articles.*.quantity.integer' => 'La cantidad debe ser un número entero.',
            'dispatch_articles.*.quantity.min' => 'La cantidad debe ser mayor o igual a 1.',
            'dispatch_articles.*.weight.required' => 'Debe ingresar el peso.',
            'dispatch_articles.*.weight.numeric' => 'El peso debe ser un número.',
            'dispatch_articles.*.weight.min' => 'El peso debe ser mayor o igual a 0.',
            'dispatch_articles.*.saldo.required' => 'Debe ingresar el saldo.',
            'dispatch_articles.*.saldo.numeric' => 'El saldo debe ser un número.',
            'dispatch_articles.*.saldo.min' => 'El saldo debe ser mayor o igual a 0.',
            'dispatch_articles.*.name.required' => 'Debe ingresar el nombre.',
            'dispatch_articles.*.name.string' => 'El nombre debe ser una cadena de texto.',
            'dispatch_articles.*.subtotal_weight.required' => 'Debe ingresar el peso subtotal.',
            'dispatch_articles.*.subtotal_weight.numeric' => 'El peso subtotal debe ser un número.',
            'dispatch_articles.*.subtotal_weight.min' => 'El peso subtotal debe ser mayor o igual a 0.',
            'dispatch_articles.*.serials.array' => 'Los serials deben ser un array.',
            'dispatch_articles.*.serials.distinct' => 'Los serials no pueden repetirse.',
            'dispatch_articles.*.serials.*.required' => 'Debe ingresar un serial.',
            'dispatch_articles.*.serials.*.distinct' => 'Los serials no pueden repetirse.',

            //customer
            'customer_id.required' => 'Debe seleccionar un cliente.',
            'customer_id.exists' => 'Selecciona un cliente',
            'address_supplier_id.required' => 'Debe seleccionar un cliente.',
            'address_supplier_id.exists' => 'Selecciona un cliente',
            'supplier_id.required' => 'Debe seleccionar un proveedor.',
            'supplier_id.exists' => 'Selecciona un proveedor',
        ]; 
    }

    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $dispatchArticles = $this->input('dispatch_articles', []);
            $allSerials = [];

            // Recopilar todos los serials de todos los artículos
            foreach ($dispatchArticles as $article) {
                if (isset($article['serials']) && is_array($article['serials'])) {
                    $allSerials = array_merge($allSerials, $article['serials']);
                }
            }

            // Si hay serials, verificar si alguno ya existe en la base de datos
            if (!empty($allSerials)) {
                $existingSerials = DB::table('dispatch_article_serials')
                    ->whereIn('serial', $allSerials)
                    ->exists();

                if ($existingSerials) {
                    $validator->errors()->add(
                        'dispatch_articles',
                        'Esta venta ya tiene una guía de remisión asignada.'
                    );
                }
            }
        });
    }
} 
