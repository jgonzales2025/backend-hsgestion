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
            "customer_phone" => "required|string|max:9",
            "customer_email" => "nullable",
            "failure_description" => "required_if:document_type_warranty_id,2",
            "observations" => "nullable|string",
            "diagnosis" => "required_if:document_type_warranty_id,2",
            "supplier_id" => "required_if:document_type_warranty_id,1",
            "entry_guide_id" => "required_if:document_type_warranty_id,1",
            "contact" => "required|string",
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

    public function messages()
    {
        return [
            "document_type_warranty_id.required" => "El tipo de documento de garantía es obligatorio.",
            "company_id.required" => "La empresa es obligatoria.",
            "branch_id.required" => "La sucursal es obligatoria.",
            "branch_sale_id.required_if" => "La sucursal de venta es obligatoria para registrar la garantía.",
            "serie.required" => "La serie es obligatoria.",
            "article_id.required_if" => "El artículo es obligatorio para registrar la garantía.",
            "date.required" => "La fecha es obligatoria.",
            "reference_sale_id.required_if" => "La referencia de venta es obligatoria para registrar la garantía.",
            "customer_id.required_if" => "El cliente es obligatorio para registrar la garantía.",
            "customer_phone.required" => "El teléfono del cliente es obligatorio.",
            "customer_phone.string" => "El teléfono del cliente debe ser una cadena de texto.",
            "customer_phone.max" => "El teléfono del cliente no puede tener más de 9 caracteres.",
            "failure_description.required_if" => "La descripción de la falla es obligatoria para registrar el documento.",
            "observations.string" => "Las observaciones deben ser una cadena de texto.",
            "diagnosis.required_if" => "El diagnóstico es obligatorio para registrar el documento.",
            "supplier_id.required_if" => "El proveedor es obligatorio para registrar la garantía.",
            "entry_guide_id.required_if" => "La guía de ingreso es obligatoria para registrar la garantía.",
            "contact.required" => "El contacto es obligatorio.",
            "contact.string" => "El contacto debe ser una cadena de texto.",
        ];
    }
}