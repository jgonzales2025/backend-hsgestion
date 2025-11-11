<?php

namespace App\Modules\Sale\Application\DTOs;

class SaleCreditNoteDTO
{
    public $company_id;
    public ?int $branch_id;//5
    public ?int $document_type_id;//1
    public ?string $serie;//7
    public ?string $document_number;//8
    public $parallel_rate;//9
    public ?int $customer_id;//4
    public $date;
    public $due_date;
    public $days;
    public $user_id;
    public ?int $payment_type_id;//3
    public ?int $currency_type_id;//2
    public $subtotal;
    public float $igv;
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
        $this->branch_id = $data['branch_id'] ?? null;
        $this->document_type_id = $data['document_type_id'] ?? null;
        $this->serie = $data['serie'] ?? null;
        $this->document_number = $data['document_number'] ?? null;
        $this->parallel_rate = $data['parallel_rate'] ?? null;
        $this->customer_id = $data['customer_id'] ?? null;
        $this->date = $data['date'];
        $this->due_date = $data['due_date'];
        $this->days = $data['days'];
        $this->user_id = $data['user_id'];
        $this->payment_type_id = $data['payment_type_id'] ?? null;
        $this->currency_type_id = $data['currency_type_id'] ?? null;
        $this->subtotal = $data['subtotal'];
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
