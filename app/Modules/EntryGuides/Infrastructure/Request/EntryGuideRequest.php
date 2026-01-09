<?php

namespace App\Modules\EntryGuides\Infrastructure\Request;

use App\Modules\Articles\Infrastructure\Models\EloquentArticle;
use Illuminate\Foundation\Http\FormRequest;




class EntryGuideRequest extends FormRequest
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
            'serie' => 'required|string',
            'date' => 'string',
            'customer_id' => 'required|integer|exists:customers,id',
            'observations' => 'nullable|string',
            'ingress_reason_id' => 'required|integer|exists:ingress_reasons,id',
            'reference_po_serie' => 'string',
            'reference_po_correlative' => 'string',
            'reference_document_id' => 'nullable',
            'subtotal' => 'nullable|numeric',
            'total_descuento' => 'nullable|numeric',
            'total' => 'nullable|numeric',
            'update_price' => 'nullable|boolean',
            'entry_guide_articles' => 'required|array|min:1',
            'entry_guide_articles.*.article_id' => 'required|integer|exists:articles,id',
            'entry_guide_articles.*.description' => 'required|string',
            'entry_guide_articles.*.quantity' => 'required|numeric',
            'entry_guide_articles.*.serials' => 'nullable|array',

            'entry_guide_articles.*.serials.*' => 'required|string|distinct',
            'entry_guide_articles.*.subtotal' => 'nullable|numeric',
            'entry_guide_articles.*.total' => 'nullable|numeric',
            'entry_guide_articles.*.precio_costo' => 'nullable|numeric',
            'entry_guide_articles.*.descuento' => 'nullable|numeric',
            'order_purchase_id' => 'nullable|array',


            'document_entry_guide' => 'required|array',
            'document_entry_guide.reference_document_id' => 'nullable',
            'document_entry_guide.reference_serie' => 'nullable|string',
            'document_entry_guide.reference_correlative' => 'nullable|string',
            'currency_id' => 'required|integer|exists:currency_types,id',
            'entry_igv' => 'nullable|numeric',
            'includ_igv' => 'nullable|boolean',
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
        $entryGuideArticles = $this->input('entry_guide_articles', []);
        $allSerials = [];

        foreach ($entryGuideArticles as $index => $entryGuideArticle) {
            $article = EloquentArticle::find($entryGuideArticle['article_id']);

            if (!$article) {
                continue;
            }

            $serials = $entryGuideArticle['serials'] ?? [];
            $quantity = $entryGuideArticle['quantity'];

            // Validar artículos que requieren serie
            if ($article->series_enabled) {
                if (empty($serials)) {
                    $validator->errors()->add(
                        "entry_guide_articles.{$index}.serials",
                        "El artículo '{$article->description}' requiere series."
                    );
                } elseif (count($serials) !== $quantity) {
                    $validator->errors()->add(
                        "entry_guide_articles.{$index}.serials",
                        "El artículo '{$article->description}' requiere {$quantity} series, pero se proporcionaron " . count($serials) . "."
                    );
                }

                $allSerials = array_merge($allSerials, $serials);
            } else {
                // Validar que artículos sin serie no tengan series
                if (!empty($serials)) {
                    $validator->errors()->add(
                        "entry_guide_articles.{$index}.serials",
                        "El artículo '{$article->description}' no maneja series."
                    );
                }
            }
        }

        // Validar que no haya series duplicadas en toda la venta
        if (count($allSerials) !== count(array_unique($allSerials))) {
            $validator->errors()->add(
                'entry_guide_articles',
                'Hay números de serie duplicados en la guía de ingreso.'
            );
        }
    }
    public function messages()
    {
        return [
            'company_id.required' => 'La compañia es obligatoria.',
            'currency_id.required' => 'La moneda es obligatoria.',
            'branch_id.required' => 'La sucursal es obligatoria.',
            'customer_id.required' => 'El cliente es obligatorio.',
            'ingress_reason_id.required' => 'La razón de ingreso es obligatoria.',
            'reference_document_id.required' => 'El documento de referencia es obligatorio.',
            'entry_guide_articles.required' => 'Los artículos de la guía de ingreso son obligatorios.',
            'entry_guide_articles.*.article_id.required' => 'El artículo es obligatorio.',
            'entry_guide_articles.*.quantity.required' => 'La cantidad es obligatoria.',
            // 'entry_guide_articles.*.serials.required' => 'Los números de serie son obligatorios.',
            // 'entry_guide_articles.*.serials.*.required' => 'El número de serie es obligatorio.',
            'entry_guide_articles.*.serials.*.distinct' => 'No se permiten números de serie duplicados.',
            // 'entry_guide_articles.*.subtotal.required' => 'El subtotal es obligatorio.',
            // 'entry_guide_articles.*.total.required' => 'El total es obligatorio.',
            // 'entry_guide_articles.*.precio_costo.required' => 'El precio de costo es obligatorio.',
            // 'entry_guide_articles.*.descuento.required' => 'El descuento es obligatorio.',
            'document_entry_guide.reference_document_id.required' => 'El documento de referencia es obligatorio.',
            // 'document_entry_guide.reference_serie.required' => 'La serie de referencia es obligatoria.',
            // 'document_entry_guide.reference_correlative.required' => 'El correlativo de referencia es obligatorio.',
            // 'document_entry_guide.reference_document_id.exists' => 'El documento de referencia no existe.',
            // 'document_entry_guide.reference_serie.exists' => 'La serie de referencia no existe.',
            // 'document_entry_guide.reference_correlative.exists' => 'El correlativo de referencia no existe.',
        ];
    }
}
