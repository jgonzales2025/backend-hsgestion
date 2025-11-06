<?php

namespace App\Modules\Sale\Application\DTOs;

class SaleCreditNoteDTO
{
    public $company_id;
    public $branch_id;
    public $document_type_id;
    public ?string $serie;
    public ?string $document_number;
    public $parallel_rate;
    public $customer_id;
    public $date;
    public $due_date;
    public $days;
    public $user_id;
    public $payment_type_id;
    public $currency_type_id;
    public $subtotal;
    public $inafecto;
    public $igv;
    public $total;
    public ?float $saldo;
    public ?float $amount_amortized;
    public ?int $payment_status;
    public ?bool $is_locked;
    public ?int $reference_document_type_id;
    public ?string $reference_serie;
    public ?string $reference_correlative;
    public ?int $note_reason_id;

    public function __construct(array $data)
    {
        $this->company_id = $data['company_id'];
        $this->branch_id = $data['branch_id'];
        $this->document_type_id = $data['document_type_id'];
        $this->serie = $data['serie'] ?? null;
        $this->document_number = $data['document_number'] ?? null;
        $this->parallel_rate = $data['parallel_rate'];
        $this->customer_id = $data['customer_id'];
        $this->date = $data['date'];
        $this->due_date = $data['due_date'];
        $this->days = $data['days'];
        $this->user_id = $data['user_id'];
        $this->payment_type_id = $data['payment_type_id'];
        $this->currency_type_id = $data['currency_type_id'];
        $this->subtotal = $data['subtotal'];
        $this->inafecto = $data['inafecto'];
        $this->igv = $data['igv'];
        $this->total = $data['total'];
        $this->saldo = $data['saldo'] ?? null;
        $this->amount_amortized = $data['amount_amortized'] ?? null;
        $this->payment_status = $data['payment_status'] ?? null;
        $this->is_locked = $data['is_locked'] ?? null;
        $this->reference_document_type_id = $data['reference_document_type_id'] ?? null;
        $this->reference_serie = $data['reference_serie'] ?? null;
        $this->reference_correlative = $data['reference_correlative'] ?? null;
        $this->note_reason_id = $data['note_reason_id'] ?? null;
    }
}
