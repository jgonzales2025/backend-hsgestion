<?php

namespace App\Modules\Advance\Application\DTOs;

class AdvanceDTO
{
    public int $id;
    public int $customer_id;
    public int $payment_method_id;
    public int $bank_id;
    public string $operation_number;
    public string $operation_date;
    public float $parallel_rate;
    public int $currency_type_id;
    public float $amount;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->customer_id = $data['customer_id'];
        $this->payment_method_id = $data['payment_method_id'];
        $this->bank_id = $data['bank_id'];
        $this->operation_number = $data['operation_number'];
        $this->operation_date = $data['operation_date'];
        $this->parallel_rate = $data['parallel_rate'];
        $this->currency_type_id = $data['currency_type_id'];
        $this->amount = $data['amount'];
    }
}