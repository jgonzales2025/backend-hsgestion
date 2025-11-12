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
            'cia_id' => 'required|integer|exists:companies,id',
            'branch_id' => 'required|integer|exists:branches,id',
            'serie' => 'required|string',
            'date' => 'string',
            'customer_id' => 'required|integer|exists:customers,id',
            'observations' => 'string',
            'ingress_reason_id' => 'required|integer|exists:ingress_reasons,id',
            'reference_po_serie' => 'string',
            'reference_po_correlative' => 'string',
            'status' => 'integer',
            'entry_guide_articles'=> 'required|array|min:1',
            'entry_guide_articles.*.article_id' => 'required|integer|exists:articles,id',
            'entry_guide_articles.*.description' => 'required|string',
            'entry_guide_articles.*.quantity' => 'required|numeric',
            'entry_guide_articles.*.serials' => 'nullable|array',
            'entry_guide_articles.*.serials.*' => 'required|string|distinct',

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