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
            'delivery_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'days' => 'nullable|integer',
            'currency_type_id' => 'required|integer|exists:currency_types,id',
            'parallel_rate' => 'required|numeric',
            'contact_name' => 'nullable|string|max:100',
            'contact_phone' => 'nullable|string|max:20',
            'payment_type_id' => 'required|integer|exists:payment_types,id',
            'observations' => 'nullable|string|max:255',
            'supplier_id' => 'required|integer|exists:customers,id',
            'percentage_igv' => 'required|integer',
            'is_igv_included' => 'required|boolean',
            'subtotal' => 'required|numeric|min:0',
            'igv' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',

            // Articulos
            'articles' => 'required|array|min:1',
            'articles.*.article_id' => 'required|integer|exists:articles,id',
            'articles.*.description' => 'nullable|string|max:150',
            'articles.*.weight' => 'nullable|numeric|min:0',
            'articles.*.quantity' => 'required|integer|min:1',
            'articles.*.purchase_price' => 'required|numeric|min:0',
            'articles.*.subtotal' => 'required|numeric|min:0',
            'det_entry_guide_purchase_order' => 'required|array|min:1|exists:entry_guides,id',
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
            'observations.string' => 'El campo observaciones debe ser una cadena de texto.',
            'observations.max' => 'El campo observaciones no puede tener más de 255 caracteres.',
            'supplier_id.required' => 'El campo proveedor es obligatorio.',
            'supplier_id.integer' => 'El campo proveedor debe ser un número entero.',
            'supplier_id.exists' => 'El proveedor seleccionado no existe.',
            'subtotal.required' => 'El campo subtotal es obligatorio.',
            'subtotal.numeric' => 'El campo subtotal debe ser un número.',
            'subtotal.min' => 'El campo subtotal debe ser mayor o igual a 0.',
            'igv.required' => 'El campo igv es obligatorio.',
            'igv.numeric' => 'El campo igv debe ser un número.',
            'igv.min' => 'El campo igv debe ser mayor o igual a 0.',
            'total.required' => 'El campo total es obligatorio.',
            'total.numeric' => 'El campo total debe ser un número.',
            'total.min' => 'El campo total debe ser mayor o igual a 0.',
        ];
    }
}
