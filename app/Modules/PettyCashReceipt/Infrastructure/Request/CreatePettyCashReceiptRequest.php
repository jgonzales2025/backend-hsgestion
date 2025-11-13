<?php

namespace App\Modules\PettyCashReceipt\Infrastructure\Request;

use Illuminate\Foundation\Http\FormRequest;

class CreatePettyCashReceiptRequest extends FormRequest{
    public function rules(): array{
        return [
            'company'=> 'required|integer',
            'document_type'=> 'required|integer',
            'series'=> 'required|string',
            'correlative'=> 'required|string',
            'date'=> 'required|string',
            'delivered_to'=> 'required|string',
            'reason_code'=> 'required|integer',
            'currency_type'=> 'required|integer',
            'amount'=> 'required|integer',
            'observation'=> 'required|string',
            'status'=> 'required|integer',
            'created_by'=> 'required|integer',
            'created_at_manual'=> 'required|string',
            'updated_by'=> 'required|integer',
            'updated_at_manual'=> 'required|string',
        ];
    }
}