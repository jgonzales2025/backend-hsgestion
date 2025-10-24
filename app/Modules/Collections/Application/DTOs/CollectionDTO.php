<?php

namespace App\Modules\Collections\Application\DTOs;

class CollectionDTO
{
    public int $company_id;
    public int $sale_id;
    public string $sale_document_type_id;
    public string $sale_serie;
    public string $sale_correlative;
    public int $payment_method_id;
    public string $payment_date;
    public int $currency_type_id;
    public float $parallel_rate;
    public float $amount;
    public ?float $change;
    public ?int $digital_wallet_id;
    public ?int $bank_id;
    public ?string $operation_date;
    public ?string $operation_number;
    public ?string $lote_number;
    public ?string $for_digits;

    public function __construct(array $data)
    {
        $this->company_id = $data['company_id'];
        $this->sale_id = $data['sale_id'];
        $this->sale_document_type_id = $data['sale_document_type_id'];
        $this->sale_serie = $data['sale_serie'];
        $this->sale_correlative = $data['sale_correlative'];
        $this->payment_method_id = $data['payment_method_id'];
        $this->payment_date = $data['payment_date'];
        $this->currency_type_id = $data['currency_type_id'];
        $this->parallel_rate = $data['parallel_rate'];
        $this->amount = $data['amount'];
        $this->change = $data['change'] ?? null;
        $this->digital_wallet_id = $data['digital_wallet_id'] ?? null;
        $this->bank_id = $data['bank_id'] ?? null;
        $this->operation_date = $data['operation_date'] ?? null;
        $this->operation_number = $data['operation_number'] ?? null;
        $this->lote_number = $data['lote_number'] ?? null;
        $this->for_digits = $data['for_digits'] ?? null;
    }
}
