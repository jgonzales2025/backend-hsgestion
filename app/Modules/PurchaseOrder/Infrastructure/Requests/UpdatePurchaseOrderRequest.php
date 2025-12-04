<?php

namespace App\Modules\PurchaseOrder\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchaseOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => 'required|integer',
            'branch_id' => 'required|integer',
            'serie' => 'required|string|max:10',
            'date' => 'sometimes|date',
            'delivery_date' => 'nullable|date',
            'due_date' => 'sometimes|date',
            'days' => 'sometimes|integer',
            'contact' => 'nullable|string|max:100',
            'currency_type_id' => 'required|integer|exists:currency_types,id',
            'parallel_rate' => 'required|numeric',
            'contact_name' => 'nullable|string|max:100',
            'contact_phone' => 'nullable|string|max:20',
            'payment_type_id' => 'required|integer|exists:payment_types,id',
            'order_number_supplier' => 'nullable|string|max:50',
            'observations' => 'nullable|string|max:255',
            'supplier_id' => 'required|integer|exists:customers,id',
            'status' => 'required|integer|in:0,1',
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

        ];
    }

    public function messages(): array
    {
        return [
            'company_id.required' => 'La empresa es obligatoria.',
            'company_id.integer' => 'La empresa debe ser un número entero.',
            
            'branch_id.required' => 'La sucursal es obligatoria.',
            'branch_id.integer' => 'La sucursal debe ser un número entero.',
            
            'serie.required' => 'La serie es obligatoria.',
            'serie.string' => 'La serie debe ser texto.',
            'serie.max' => 'La serie no puede exceder 10 caracteres.',
            
            'date.sometimes' => 'La fecha debe ser válida.',
            'date.date' => 'La fecha debe ser una fecha válida.',
            
            'delivery_date.nullable' => 'La fecha de entrega debe ser válida.',
            'delivery_date.date' => 'La fecha de entrega debe ser una fecha válida.',
            
            'due_date.sometimes' => 'La fecha de vencimiento debe ser válida.',
            'due_date.date' => 'La fecha de vencimiento debe ser una fecha válida.',
            
            'days.sometimes' => 'Los días deben ser un número entero.',
            'days.integer' => 'Los días debe ser un número entero.',
            
            'contact.nullable' => 'El contacto debe ser texto.',
            'contact.string' => 'El contacto debe ser texto.',
            'contact.max' => 'El contacto no puede exceder 100 caracteres.',
            
            'currency_type_id.required' => 'El tipo de moneda es obligatorio.',
            'currency_type_id.integer' => 'El tipo de moneda debe ser un número entero.',
            'currency_type_id.exists' => 'El tipo de moneda seleccionado no existe.',
            
            'parallel_rate.required' => 'La tasa paralela es obligatoria.',
            'parallel_rate.numeric' => 'La tasa paralela debe ser un número.',
            
            'contact_name.nullable' => 'El nombre del contacto debe ser texto.',
            'contact_name.string' => 'El nombre del contacto debe ser texto.',
            'contact_name.max' => 'El nombre del contacto no puede exceder 100 caracteres.',
            
            'contact_phone.nullable' => 'El teléfono del contacto debe ser texto.',
            'contact_phone.string' => 'El teléfono del contacto debe ser texto.',
            'contact_phone.max' => 'El teléfono del contacto no puede exceder 20 caracteres.',
            
            'payment_type_id.required' => 'El tipo de pago es obligatorio.',
            'payment_type_id.integer' => 'El tipo de pago debe ser un número entero.',
            'payment_type_id.exists' => 'El tipo de pago seleccionado no existe.',
            
            'order_number_supplier.nullable' => 'El número de orden del proveedor debe ser texto.',
            'order_number_supplier.string' => 'El número de orden del proveedor debe ser texto.',
            'order_number_supplier.max' => 'El número de orden del proveedor no puede exceder 50 caracteres.',
            
            'observations.nullable' => 'Las observaciones deben ser texto.',
            'observations.string' => 'Las observaciones deben ser texto.',
            'observations.max' => 'Las observaciones no pueden exceder 255 caracteres.',
            
            'supplier_id.required' => 'El proveedor es obligatorio.',
            'supplier_id.integer' => 'El proveedor debe ser un número entero.',
            'supplier_id.exists' => 'El proveedor seleccionado no existe.',
            
            'status.required' => 'El estado es obligatorio.',
            'status.integer' => 'El estado debe ser un número entero.',
            'status.in' => 'El estado debe ser 0 o 1.',
            
            'percentage_igv.required' => 'El porcentaje de IGV es obligatorio.',
            'percentage_igv.integer' => 'El porcentaje de IGV debe ser un número entero.',
            
            'is_igv_included.required' => 'Debe indicar si el IGV está incluido.',
            'is_igv_included.boolean' => 'El campo IGV incluido debe ser verdadero o falso.',
            
            'subtotal.required' => 'El subtotal es obligatorio.',
            'subtotal.numeric' => 'El subtotal debe ser un número.',
            'subtotal.min' => 'El subtotal no puede ser negativo.',
            
            'igv.required' => 'El IGV es obligatorio.',
            'igv.numeric' => 'El IGV debe ser un número.',
            'igv.min' => 'El IGV no puede ser negativo.',
            
            'total.required' => 'El total es obligatorio.',
            'total.numeric' => 'El total debe ser un número.',
            'total.min' => 'El total no puede ser negativo.',
            
            // Artículos
            'articles.required' => 'Debe incluir al menos un artículo.',
            'articles.array' => 'Los artículos deben ser un array.',
            'articles.min' => 'Debe incluir al menos un artículo.',
            
            'articles.*.article_id.required' => 'El artículo es obligatorio en cada línea.',
            'articles.*.article_id.integer' => 'El artículo debe ser un número entero.',
            'articles.*.article_id.exists' => 'El artículo seleccionado no existe.',
            
            'articles.*.description.nullable' => 'La descripción debe ser texto.',
            'articles.*.description.string' => 'La descripción debe ser texto.',
            'articles.*.description.max' => 'La descripción no puede exceder 150 caracteres.',
            
            'articles.*.weight.nullable' => 'El peso debe ser un número.',
            'articles.*.weight.numeric' => 'El peso debe ser un número.',
            'articles.*.weight.min' => 'El peso no puede ser negativo.',
            
            'articles.*.quantity.required' => 'La cantidad es obligatoria en cada artículo.',
            'articles.*.quantity.integer' => 'La cantidad debe ser un número entero.',
            'articles.*.quantity.min' => 'La cantidad debe ser mínimo 1.',
            
            'articles.*.purchase_price.required' => 'El precio de compra es obligatorio en cada artículo.',
            'articles.*.purchase_price.numeric' => 'El precio de compra debe ser un número.',
            'articles.*.purchase_price.min' => 'El precio de compra no puede ser negativo.',
            
            'articles.*.subtotal.required' => 'El subtotal es obligatorio en cada artículo.',
            'articles.*.subtotal.numeric' => 'El subtotal debe ser un número.',
            'articles.*.subtotal.min' => 'El subtotal no puede ser negativo.',
        ];
    }
}
