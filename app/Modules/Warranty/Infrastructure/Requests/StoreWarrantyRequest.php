<?php

namespace App\Modules\Warranty\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWarrantyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            "document_type_warranty_id" => "required",
            "company_id" => "required",
            "branch_id" => "required",
            "branch_sale_id" => "required_if:document_type_warranty_id,1",
            "serie" => "required",
            "article_id" => "required_if:document_type_warranty_id,1",
            "serie_art" => "nullable",
            "date" => "required",
            "reference_sale_id" => "required_if:document_type_warranty_id,1",
            "customer_id" => "required_if:document_type_warranty_id,1",
            "customer_phone" => "required_if:document_type_warranty_id,2",
            "customer_email" => "required_if:document_type_warranty_id,2",
            "failure_description" => "required_if:document_type_warranty_id,2",
            "observations" => "nullable",
            "diagnosis" => "required_if:document_type_warranty_id,2",
            "supplier_id" => "required_if:document_type_warranty_id,1",
            "entry_guide_id" => "required_if:document_type_warranty_id,1",
            "contact" => "nullable",
            "follow_up_diagnosis" => "nullable",
            "follow_up_status" => "nullable",
            "solution" => "nullable",
            "solution_date" => "nullable",
            "delivery_description" => "nullable",
            "delivery_serie_art" => "nullable",
            "credit_note_serie" => "nullable",
            "credit_note_correlative" => "nullable",
            "delivery_date" => "nullable",
            "dispatch_note_serie" => "nullable",
            "dispatch_note_correlative" => "nullable",
            "dispatch_note_date" => "nullable"
        ];
    }
}