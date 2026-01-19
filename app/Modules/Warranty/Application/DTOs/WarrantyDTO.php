<?php

namespace App\Modules\Warranty\Application\DTOs;

class WarrantyDTO
{
    public int $document_type_warranty_id;
    public int $company_id;
    public int $branch_id;
    public int $branch_sale_id;
    public string $serie;
    public string $correlative;
    public int $article_id;
    public ?string $serie_art;
    public string $date;
    public int $reference_sale_id;
    public int $customer_id;
    public ?string $customer_phone;
    public ?string $customer_email;
    public ?string $failure_description;
    public ?string $observations;
    public ?string $diagnosis;
    public int $supplier_id;
    public int $entry_guide_id;
    public ?string $contact;
    public ?string $follow_up_diagnosis;
    public ?string $follow_up_status;
    public ?string $solution;
    public int $warranty_status_id = 1;
    public ?string $solution_date;
    public ?string $delivery_description;
    public ?string $delivery_serie_art;
    public ?string $credit_note_serie;
    public ?string $credit_note_correlative;
    public ?string $delivery_date;
    public ?string $dispatch_note_serie;
    public ?string $dispatch_note_correlative;
    public ?string $dispatch_note_date;

    public function __construct(array $data) {
        $this->document_type_warranty_id = $data['document_type_warranty_id'];
        $this->company_id = $data['company_id'];
        $this->branch_id = $data['branch_id'];
        $this->branch_sale_id = $data['branch_sale_id'];
        $this->serie = $data['serie'];
        $this->article_id = $data['article_id'];
        $this->serie_art = $data['serie_art'] ?? null;
        $this->date = $data['date'];
        $this->reference_sale_id = $data['reference_sale_id'];
        $this->customer_id = $data['customer_id'];
        $this->customer_phone = $data['customer_phone'] ?? null;
        $this->customer_email = $data['customer_email'] ?? null;
        $this->failure_description = $data['failure_description'] ?? null;
        $this->observations = $data['observations'] ?? null;
        $this->diagnosis = $data['diagnosis'] ?? null;
        $this->supplier_id = $data['supplier_id'];
        $this->entry_guide_id = $data['entry_guide_id'];
        $this->contact = $data['contact'] ?? null;
        $this->follow_up_diagnosis = $data['follow_up_diagnosis'] ?? null;
        $this->follow_up_status = $data['follow_up_status'] ?? null;
        $this->solution = $data['solution'] ?? null;
        $this->warranty_status_id = 1;
        $this->solution_date = $data['solution_date'] ?? null;
        $this->delivery_description = $data['delivery_description'] ?? null;
        $this->delivery_serie_art = $data['delivery_serie_art'] ?? null;
        $this->credit_note_serie = $data['credit_note_serie'] ?? null;
        $this->credit_note_correlative = $data['credit_note_correlative'] ?? null;
        $this->delivery_date = $data['delivery_date'] ?? null;
        $this->dispatch_note_serie = $data['dispatch_note_serie'] ?? null;
        $this->dispatch_note_correlative = $data['dispatch_note_correlative'] ?? null;
        $this->dispatch_note_date = $data['dispatch_note_date'] ?? null;
    }
}