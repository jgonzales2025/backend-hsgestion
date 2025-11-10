<?php

namespace App\Modules\Sale\Infrastructure\Requests;

use App\Modules\Articles\Infrastructure\Models\EloquentArticle;
use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
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
            'document_type_id' => 'required|integer|exists:document_types,id',
            'serie' => 'required|string|max:10',
            'parallel_rate' => 'required|numeric|min:0',
            'customer_id' => 'required|integer|exists:customers,id',
            'date' => 'required|date',
            'due_date' => 'required|date',
            'days' => 'required|integer',
            'user_id' => 'required|integer|exists:users,id',
            'user_sale_id' => 'nullable|integer|exists:users,id',
            'payment_type_id' => 'required|integer|exists:payment_types,id',
            'observations' => 'nullable|string',
            'currency_type_id' => 'required|integer|exists:currency_types,id',
            'subtotal' => 'required|numeric|min:0',
            'inafecto' => 'required|numeric|min:0',
            'igv' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'serie_prof' => 'nullable|string|max:10',
            'correlative_prof' => 'nullable|string|max:10',
            'purchase_order' => 'nullable|string|max:10',
            'user_authorized_id' => 'nullable|integer|exists:users,id',

            'sale_articles' => 'required|array|min:1',
            'sale_articles.*.article_id' => 'required|integer|exists:articles,id',
            'sale_articles.*.description' => 'required|string',
            'sale_articles.*.quantity' => 'required|integer|min:1',
            'sale_articles.*.unit_price' => 'required|numeric|min:0',
            'sale_articles.*.public_price' => 'required|numeric|min:0',
            'sale_articles.*.subtotal' => 'required|numeric|min:0',
            'sale_articles.*.serials' => 'nullable|array',
            'sale_articles.*.serials.*' => 'string|distinct',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $this->validateSerials($validator);
        });
    }

    protected function validateSerials($validator)
    {
        $saleArticles = $this->input('sale_articles', []);
        $allSerials = [];

        foreach ($saleArticles as $index => $saleArticle) {
            $article = EloquentArticle::find($saleArticle['article_id']);

            if (!$article) {
                continue;
            }

            $serials = $saleArticle['serials'] ?? [];
            $quantity = $saleArticle['quantity'];

            // Validar artículos que requieren serie
            if ($article->series_enabled) {
                if (empty($serials)) {
                    $validator->errors()->add(
                        "sale_articles.{$index}.serials",
                        "El artículo '{$article->description}' requiere series."
                    );
                } elseif (count($serials) !== $quantity) {
                    $validator->errors()->add(
                        "sale_articles.{$index}.serials",
                        "El artículo '{$article->description}' requiere {$quantity} series, pero se proporcionaron " . count($serials) . "."
                    );
                }

                $allSerials = array_merge($allSerials, $serials);
            } else {
                // Validar que artículos sin serie no tengan series
                if (!empty($serials)) {
                    $validator->errors()->add(
                        "sale_articles.{$index}.serials",
                        "El artículo '{$article->description}' no maneja series."
                    );
                }
            }
        }

        // Validar que no haya series duplicadas en toda la venta
        if (count($allSerials) !== count(array_unique($allSerials))) {
            $validator->errors()->add(
                'sale_articles',
                'Hay números de serie duplicados en la venta.'
            );
        }

    }


    public function messages(): array
    {
        return [
            'company_id.required' => 'La empresa es obligatoria.',
            'company_id.exists' => 'La empresa seleccionada no existe.',
            'branch_id.required' => 'La sucursal es obligatoria.',
            'branch_id.exists' => 'La sucursal seleccionada no existe.',
            'document_type_id.required' => 'El tipo de documento es obligatorio.',
            'document_type_id.exists' => 'El tipo de documento seleccionado no existe.',
            'serie.required' => 'La serie es obligatoria.',
            'serie.max' => 'La serie no puede exceder :max caracteres.',
            'parallel_rate.required' => 'El tipo de cambio es obligatorio.',
            'parallel_rate.numeric' => 'El tipo de cambio debe ser un número.',
            'parallel_rate.min' => 'El tipo de cambio debe ser mayor o igual a 0.',
            'customer_id.required' => 'El cliente es obligatorio.',
            'customer_id.exists' => 'El cliente seleccionado no existe.',
            'date.required' => 'La fecha es obligatoria.',
            'date.date' => 'La fecha debe ser una fecha válida.',
            'due_date.required' => 'La fecha de vencimiento es obligatoria.',
            'due_date.date' => 'La fecha de vencimiento debe ser una fecha válida.',
            'days.required' => 'Los días son obligatorios.',
            'days.integer' => 'Los días deben ser un número entero.',
            'user_id.required' => 'El usuario es obligatorio.',
            'user_id.exists' => 'El usuario seleccionado no existe.',
            'user_sale_id.exists' => 'El usuario vendedor seleccionado no existe.',
            'payment_type_id.required' => 'El tipo de pago es obligatorio.',
            'payment_type_id.exists' => 'El tipo de pago seleccionado no existe.',
            'currency_type_id.required' => 'La moneda es obligatoria.',
            'currency_type_id.exists' => 'La moneda seleccionada no existe.',
            'subtotal.required' => 'El subtotal es obligatorio.',
            'subtotal.numeric' => 'El subtotal debe ser un número.',
            'subtotal.min' => 'El subtotal debe ser mayor o igual a 0.',
            'inafecto.required' => 'El monto inafecto es obligatorio.',
            'inafecto.numeric' => 'El monto inafecto debe ser un número.',
            'inafecto.min' => 'El monto inafecto debe ser mayor o igual a 0.',
            'igv.required' => 'El IGV es obligatorio.',
            'igv.numeric' => 'El IGV debe ser un número.',
            'igv.min' => 'El IGV debe ser mayor o igual a 0.',
            'total.required' => 'El total es obligatorio.',
            'total.numeric' => 'El total debe ser un número.',
            'total.min' => 'El total debe ser mayor o igual a 0.',
            'serie_prof.max' => 'La serie de proforma no puede exceder :max caracteres.',
            'correlative_prof.max' => 'El correlativo de proforma no puede exceder :max caracteres.',
            'purchase_order.max' => 'La orden de compra no puede exceder :max caracteres.',
            'user_authorized_id.exists' => 'El usuario autorizado seleccionado no existe.',
            'sale_articles.required' => 'Debe agregar al menos un artículo.',
            'sale_articles.array' => 'Los artículos deben ser un arreglo.',
            'sale_articles.min' => 'Debe agregar al menos un artículo.',
            'sale_articles.*.article_id.required' => 'El artículo es obligatorio.',
            'sale_articles.*.article_id.exists' => 'El artículo seleccionado no existe.',
            'sale_articles.*.description.required' => 'La descripción del artículo es obligatoria.',
            'sale_articles.*.quantity.required' => 'La cantidad es obligatoria.',
            'sale_articles.*.quantity.min' => 'La cantidad debe ser al menos 1.',
            'sale_articles.*.unit_price.required' => 'El precio unitario es obligatorio.',
            'sale_articles.*.unit_price.min' => 'El precio unitario debe ser mayor o igual a 0.',
            'sale_articles.*.public_price.required' => 'El precio público es obligatorio.',
            'sale_articles.*.public_price.min' => 'El precio público debe ser mayor o igual a 0.',
            'sale_articles.*.subtotal.required' => 'El subtotal del artículo es obligatorio.',
            'sale_articles.*.subtotal.min' => 'El subtotal del artículo debe ser mayor o igual a 0.',
            'sale_articles.*.serials.*.distinct' => 'Hay series duplicadas en el mismo artículo.',
        ];
    }
}
