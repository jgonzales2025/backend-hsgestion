<?php

namespace App\Modules\Sale\Application\DTOs;

class SaleDTO
{
    public $company_id;
    public $document_type_id;
    public $serie;
    public $document_number;
    public $parallel_rate;
    public $customer_id;
    public $date;
    public $due_date;
    public $days;
    public $user_id;
    public $user_sale_id;
    public $payment_type_id;
    public ?string $observations;
    public $currency_type_id;
    public $subtotal;
    public $inafecto;
    public $igv;
    public $total;
    public ?int $status;
    public ?bool $is_locked;
    public ?string $serie_prof;
    public ?string $correlative_prof;
    public ?string $purchase_order;

    public function __construct(array $data)
    {
        $this->company_id = $data['company_id'];
        $this->document_type_id = $data['document_type_id'];
        $this->serie = $data['serie'];
        $this->document_number = $data['document_number'];
        $this->parallel_rate = $data['parallel_rate'];
        $this->customer_id = $data['customer_id'];
        $this->date = $data['date'];
        $this->due_date = $data['due_date'];
        $this->days = $data['days'];
        $this->user_id = $data['user_id'];
        $this->user_sale_id = $data['user_sale_id'];
        $this->payment_type_id = $data['payment_type_id'];
        $this->observations = $data['observations'] ?? null;
        $this->currency_type_id = $data['currency_type_id'];
        $this->subtotal = $data['subtotal'];
        $this->inafecto = $data['inafecto'];
        $this->igv = $data['igv'];
        $this->total = $data['total'];
        $this->status = $data['status'] ?? null;
        $this->is_locked = $data['is_locked'] ?? null;
        $this->serie_prof = $data['serie_prof'] ?? null;
        $this->correlative_prof = $data['correlative_prof'] ?? null;
        $this->purchase_order = $data['purchase_order'] ?? null;
    }
}
