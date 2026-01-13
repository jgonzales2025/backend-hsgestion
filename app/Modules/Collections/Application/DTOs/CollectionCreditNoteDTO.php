<?php

namespace App\Modules\Collections\Application\DTOs;

class CollectionCreditNoteDTO
{
    public int $company_id;
    public int $sale_id;
    public string $sale_document_type_id;
    public string $sale_serie;
    public string $sale_correlative;
    public int $payment_method_id;
    public string $payment_date;
    public float $amount;
    public ?int $credit_document_type_id;
    public ?string $credit_serie;
    public ?string $credit_correlative;

    public function __construct(array $data)
    {
        $this->company_id = $data['company_id'];
        $this->sale_id = $data['sale_id'];
        $this->sale_document_type_id = $data['sale_document_type_id'];
        $this->sale_serie = $data['sale_serie'];
        $this->sale_correlative = $data['sale_correlative'];
        $this->payment_method_id = 5;
        $this->payment_date = $data['payment_date'];
        $this->amount = $data['amount'];
        $this->credit_document_type_id = $data['credit_document_type_id'] ?? null;
        $this->credit_serie = $data['credit_serie'] ?? null;
        $this->credit_correlative = $data['credit_correlative'] ?? null;
    }
}
