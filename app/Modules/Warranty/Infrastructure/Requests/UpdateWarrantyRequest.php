<?php

namespace App\Modules\Warranty\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWarrantyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "document_type_warranty_id" => "required",
            'customer_phone' => 'required|string',
            'customer_email' => 'nullable|email',
            'failure_description' => 'nullable|string',
            'observations' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'contact' => 'required|string',
            'follow_up_diagnosis' => 'nullable|string',
            'follow_up_status' => 'nullable|string',
            'solution' => 'nullable|string',
            'solution_date' => 'nullable|date',
            'delivery_description' => 'nullable|string',
            'delivery_serie_art' => 'nullable|string',
            'credit_note_serie' => 'nullable|string',
            'credit_note_correlative' => 'nullable|string',
            'delivery_date' => 'nullable|date',
            'dispatch_note_serie' => 'nullable|string',
            'dispatch_note_correlative' => 'nullable|string',
            'dispatch_note_date' => 'nullable|date',
        ];
    }
}