<?php

namespace App\Modules\Collections\Domain\Entities;

use App\Modules\PaymentMethod\Domain\Entities\PaymentMethod;

class Collection
{
    private int $id;
    private int $company_id;
    private int $sale_id;
    private int $sale_document_type_id;
    private string $sale_serie;
    private string $sale_correlative;
    private PaymentMethod $payment_method;
    private string $payment_date;
    private int $currency_type_id;
    private float $parallel_rate;
    private float $amount;
    private ?float $change;
    private ?int $digital_wallet_id;
    private ?int $bank_id;
    private ?string $operation_date;
    private ?string $operation_number;
    private ?string $lote_number;
    private ?string $for_digits;

    public function __construct(
        int $id,
        int $company_id,
        int $sale_id,
        int $sale_document_type_id,
        string $sale_serie,
        string $sale_correlative,
        PaymentMethod $payment_method,
        string $payment_date,
        int $currency_type_id,
        float $parallel_rate,
        float $amount,
        ?float $change,
        ?int $digital_wallet_id,
        ?int $bank_id,
        ?string $operation_date,
        ?string $operation_number,
        ?string $lote_number,
        ?string $for_digits,
    ){
        $this->id = $id;
        $this->company_id = $company_id;
        $this->sale_id = $sale_id;
        $this->sale_document_type_id = $sale_document_type_id;
        $this->sale_serie = $sale_serie;
        $this->sale_correlative = $sale_correlative;
        $this->payment_method = $payment_method;
        $this->payment_date = $payment_date;
        $this->currency_type_id = $currency_type_id;
        $this->parallel_rate = $parallel_rate;
        $this->amount = $amount;
        $this->change = $change;
        $this->digital_wallet_id = $digital_wallet_id;
        $this->bank_id = $bank_id;
        $this->operation_date = $operation_date;
        $this->operation_number = $operation_number;
        $this->lote_number = $lote_number;
        $this->for_digits = $for_digits;
    }

    public function getId(): int { return $this->id; }
    public function getCompanyId(): int { return $this->company_id; }
    public function getSaleId(): int { return $this->sale_id; }
    public function getSaleDocumentTypeId(): int { return $this->sale_document_type_id; }
    public function getSaleSerie(): string { return $this->sale_serie; }
    public function getSaleCorrelative(): string { return $this->sale_correlative; }
    public function getPaymentMethod(): PaymentMethod { return $this->payment_method; }
    public function getPaymentDate(): string { return $this->payment_date; }
    public function getCurrencyTypeId(): int { return $this->currency_type_id; }
    public function getParallelRate(): float { return $this->parallel_rate; }
    public function getAmount(): float { return $this->amount; }
    public function getChange(): ?float { return $this->change; }
    public function getDigitalWalletId(): ?int { return $this->digital_wallet_id; }
    public function getBankId(): ?int { return $this->bank_id; }
    public function getOperationDate(): ?string { return $this->operation_date; }
    public function getOperationNumber(): ?string { return $this->operation_number; }
    public function getLoteNumber(): ?string { return $this->lote_number; }
    public function getForDigits(): ?string { return $this->for_digits; }

}
