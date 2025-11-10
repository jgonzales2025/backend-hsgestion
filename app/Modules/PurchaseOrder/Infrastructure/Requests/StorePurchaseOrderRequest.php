<?php

namespace App\Modules\PurchaseOrder\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'branch_id' => 'required|integer|exists:branches,id',
            'serie' => 'required|string|max:6',
            'date' => 'required|date',
            'delivery_date' => 'required|date',
            'contact' => 'required|string|max:100',
            'order_number_supplier' => 'required|string|max:30',
            'supplier_id' => 'required|integer|exists:customers,id',

            // Articulos
            'articles' => 'required|array|min:1',
            'articles.*.article_id' => 'required|integer|exists:articles,id',
            'articles.*.description' => 'nullable|string|max:150',
            'articles.*.weight' => 'nullable|numeric|min:0',
            'articles.*.quantity' => 'required|integer|min:1',
            'articles.*.purchase_price' => 'required|numeric|min:0',
            'articles.*.subtotal' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'company_id.required' => 'El campo compañía es obligatorio.',
            'company_id.integer' => 'El campo compañía debe ser un número entero.',
            'company_id.exists' => 'La compañía seleccionada no existe.',
            'branch_id.required' => 'El campo sucursal es obligatorio.',
            'branch_id.integer' => 'El campo sucursal debe ser un número entero.',
            'branch_id.exists' => 'La sucursal seleccionada no existe.',
            'serie.required' => 'El campo serie es obligatorio.',
            'serie.string' => 'El campo serie debe ser una cadena de texto.',
            'serie.max' => 'El campo serie no puede tener más de 6 caracteres.',
            'date.required' => 'El campo fecha es obligatorio.',
            'date.date' => 'El campo fecha debe ser una fecha válida.',
            'delivery_date.required' => 'El campo fecha de entrega es obligatorio.',
            'delivery_date.date' => 'El campo fecha de entrega debe ser una fecha válida.',
            'contact.required' => 'El campo contacto es obligatorio.',
            'contact.string' => 'El campo contacto debe ser una cadena de texto.',
            'contact.max' => 'El campo contacto no puede tener más de 100 caracteres.',
            'order_number_supplier.required' => 'El campo número de orden del proveedor es obligatorio.',
            'order_number_supplier.string' => 'El campo número de orden del proveedor debe ser una cadena de texto.',
            'order_number_supplier.max' => 'El campo número de orden del proveedor no puede tener más de 30 caracteres.',
            'supplier_id.required' => 'El campo proveedor es obligatorio.',
            'supplier_id.integer' => 'El campo proveedor debe ser un número entero.',
            'supplier_id.exists' => 'El proveedor seleccionado no existe.',
        ];
    }
}
