<?php

namespace App\Modules\Installment\Application\DTOs;

class InstallmentDTO
{
    public int $installment_number;
    public int $sale_id;
    public float $amount;
    public string $due_date;

    public function __construct(array $data)
    {
        $this->installment_number = $data['installment_number'];
        $this->sale_id = $data['sale_id'];
        $this->amount = $data['amount'];
        $this->due_date = $data['due_date'];
    }
}