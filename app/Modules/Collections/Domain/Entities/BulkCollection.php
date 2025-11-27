<?php

namespace App\Modules\Collections\Domain\Entities;

class BulkCollection
{
    private int $id;
    private int $company_id;
    private int $customer_id;
    private int $payment_method_id;
    private string $payment_date;
    private float $parallel_rate;
    private int $bank_id;
    private int $currency_type_id;
    private string $operation_date;
    private string $operation_number;
    private ?int $advance_id;

    public function __construct(
        int $id,
        int $company_id,
        int $customer_id,
        int $payment_method_id,
        string $payment_date,
        float $parallel_rate,
        int $bank_id,
        int $currency_type_id,
        string $operation_date,
        string $operation_number,
        ?int $advance_id = null
    ) {
        $this->id = $id;
        $this->company_id = $company_id;
        $this->customer_id = $customer_id;
        $this->payment_method_id = $payment_method_id;
        $this->payment_date = $payment_date;
        $this->parallel_rate = $parallel_rate;
        $this->bank_id = $bank_id;
        $this->currency_type_id = $currency_type_id;
        $this->operation_date = $operation_date;
        $this->operation_number = $operation_number;
        $this->advance_id = $advance_id;
    }

    public function getId(): int { return $this->id; }
    public function getCompanyId(): int { return $this->company_id; }
    public function getCustomerId(): int { return $this->customer_id; }
    public function getPaymentMethodId(): int { return $this->payment_method_id; }
    public function getPaymentDate(): string { return $this->payment_date; }
    public function getParallelRate(): float { return $this->parallel_rate; }
    public function getBankId(): int { return $this->bank_id; }
    public function getCurrencyTypeId(): int { return $this->currency_type_id; }
    public function getOperationDate(): string { return $this->operation_date; }
    public function getOperationNumber(): string { return $this->operation_number; }
    public function getAdvanceId(): ?int { return $this->advance_id; }
}