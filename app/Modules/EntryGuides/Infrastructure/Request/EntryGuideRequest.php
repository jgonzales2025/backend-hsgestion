<?php

namespace App\Modules\EntryGuides\Infrastructure\Request;

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

}