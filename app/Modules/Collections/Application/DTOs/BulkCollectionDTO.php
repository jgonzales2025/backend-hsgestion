<?php

namespace App\Modules\Collections\Application\DTOs;

class BulkCollectionDTO
{
    public int $company_id;
    public int $customer_id;
    public int $payment_method_id;
    public string $payment_date;
    public float $parallel_rate;
    public int $bank_id;
    public int $currency_type_id;
    public string $operation_date;
    public string $operation_number;
    public ?int $advance_id;

    public function __construct(array $data)
    {
        $this->company_id = $data['company_id'];
        $this->customer_id = $data['customer_id'];
        $this->payment_method_id = $data['payment_method_id'];
        $this->payment_date = $data['payment_date'];
        $this->parallel_rate = $data['parallel_rate'];
        $this->bank_id = $data['bank_id'];
        $this->currency_type_id = $data['currency_type_id'];
        $this->operation_date = $data['operation_date'];
        $this->operation_number = $data['operation_number'];
        $this->advance_id = $data['advance_id'] ?? null;
    }
}