<?php

namespace App\Modules\Advance\Domain\Entities;

use App\Modules\Bank\Domain\Entities\Bank;
use App\Modules\CurrencyType\Domain\Entities\CurrencyType;
use App\Modules\Customer\Domain\Entities\Customer;
use App\Modules\PaymentMethod\Domain\Entities\PaymentMethod;

class Advance
{
    private int $id;
    private string $correlative;
    private Customer $customer;
    private PaymentMethod $payment_method;
    private Bank $bank;
    private string $operation_number;
    private string $operation_date;
    private float $parallel_rate;
    private CurrencyType $currency_type;
    private float $amount;
    private ?float $saldo;

    public function __construct(
        int $id,
        string $correlative,
        Customer $customer,
        PaymentMethod $payment_method,
        Bank $bank,
        string $operation_number,
        string $operation_date,
        float $parallel_rate,
        CurrencyType $currency_type,
        float $amount,
        ?float $saldo = null
    ) {
        $this->id = $id;
        $this->correlative = $correlative;
        $this->customer = $customer;
        $this->payment_method = $payment_method;
        $this->bank = $bank;
        $this->operation_number = $operation_number;
        $this->operation_date = $operation_date;
        $this->parallel_rate = $parallel_rate;
        $this->currency_type = $currency_type;
        $this->amount = $amount;
        $this->saldo = $saldo;
    }

    public function getId(): int { return $this->id; }
    public function getCorrelative(): string { return $this->correlative; }
    public function getCustomer(): Customer { return $this->customer; }
    public function getPaymentMethod(): PaymentMethod { return $this->payment_method; }
    public function getBank(): Bank { return $this->bank; }
    public function getOperationNumber(): string { return $this->operation_number; }
    public function getOperationDate(): string { return $this->operation_date; }
    public function getParallelRate(): float { return $this->parallel_rate; }
    public function getCurrencyType(): CurrencyType { return $this->currency_type; }
    public function getAmount(): float { return $this->amount; }
    public function getSaldo(): ?float { return $this->saldo; }
}