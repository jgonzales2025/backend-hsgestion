<?php

namespace App\Modules\EntryGuides\Infrastructure\Request;

use App\Modules\Articles\Infrastructure\Models\EloquentArticle;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGuideRequest extends FormRequest
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
            'user_id' => 'required|integer|exists:users,id',
            'date' => 'string',
            'customer_id' => 'required|integer|exists:customers,id',
            'observations' => 'nullable|string',
            'ingress_reason_id' => 'required|integer|exists:ingress_reasons,id',
            'reference_serie' => 'nullable|string',
            'reference_correlative' => 'nullable|string',
            'subtotal' => 'nullable|numeric',
            'total_descuento' => 'nullable|numeric',
            'reference_document_id' => 'required|integer|exists:document_types,id',
            'total' => 'nullable|numeric',
            'descuento' => 'nullable|numeric',
            'update_price' => 'nullable|boolean',
            'entry_guide_articles' => 'required|array|min:1',
            'entry_guide_articles.*.article_id' => 'required|integer|exists:articles,id',
            'entry_guide_articles.*.description' => 'required|string',
            'entry_guide_articles.*.quantity' => 'required|numeric',
            'entry_guide_articles.*.serials' => 'nullable|array',
            'entry_guide_articles.*.serials.*' => 'required|string|distinct',
            'entry_guide_articles.*.precio_costo' => 'nullable|numeric',
            'order_purchase_id' => 'nullable|array',
            // 'order_purchase_id.*.entry_guide_id' => 'required|integer',

            'document_entry_guide'=> 'required|array',
            'document_entry_guide.reference_document_id' => 'required|integer|exists:document_types,id',
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
}
