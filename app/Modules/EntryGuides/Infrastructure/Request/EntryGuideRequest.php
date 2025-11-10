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
            'user_id' => 'required|integer|exists:users,id',
            'serie' => 'string',
            'correlative' => 'string',
            'date' => 'string',
            'customer_id' => 'required|integer|exists:customers,id',
            'guide_serie_supplier' => 'string',
            'guide_correlative_supplier' => 'string',
            'invoice_serie_supplier' => 'string',
            'invoice_correlative_supplier' => 'string',
            'observations' => 'string',
            'ingress_reason_id' => 'required|integer|exists:ingress_reasons,id',
            'reference_serie' => 'string',
            'reference_correlative' => 'string',
            'status' => 'integer',
            'purchase_guide_articles'=> 'required|array|min:1',
            'purchase_item_serial'=> 'required|array|min:1',

        ];
    }

}