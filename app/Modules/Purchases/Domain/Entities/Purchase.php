<?php

namespace App\Modules\Purchases\Domain\Entities;

use App\Modules\Branch\Domain\Entities\Branch;
use App\Modules\CurrencyType\Domain\Entities\CurrencyType;
use App\Modules\Customer\Domain\Entities\Customer;
use App\Modules\PaymentMethod\Domain\Entities\PaymentMethod;
use App\Modules\PaymentType\Domain\Entities\PaymentType;
use App\Modules\DocumentType\Domain\Entities\DocumentType;

class Purchase
{
    private ?int $id;
    private int $company_id;
    private ?Branch $branch;
    private ?Customer $supplier;
    private string $serie;
    private string $correlative;
    private float $exchange_type;
    private ?PaymentType $payment_type;
    private ?CurrencyType $currency;
    private string $date;
    private string $date_ven;
    private int $days;
    private string $observation;
    private string $detraccion;
    private string $fech_detraccion;
    private float $amount_detraccion;
    private bool $is_detracion;
    private float $subtotal;
    private float $total_desc;
    private float $inafecto;
    private float $igv;
    private float $total;
    private bool $is_igv;
    private ?DocumentType $type_document_id;
    private string $reference_serie;
    private string $reference_correlative;
    private float $saldo;

    public function __construct(
        ?int $id,
        ?Branch $branch,
        ?Customer $supplier,
        string $serie,
        string $correlative,
        float $exchange_type,
        ?PaymentType $payment_type,
        ?CurrencyType $currency,
        string $date,
        string $date_ven,
        int $days,
        string $observation,
        string $detraccion,
        string $fech_detraccion,
        float $amount_detraccion,
        bool $is_detracion,
        float $subtotal,
        float $total_desc,
        float $inafecto,
        float $igv,
        float $total,
        bool $is_igv,
        ?DocumentType $type_document_id,
        string $reference_serie,
        string $reference_correlative,
        int $company_id,
        float $saldo = 0
    ) {
        $this->id = $id;
        $this->branch = $branch;
        $this->supplier = $supplier;
        $this->serie = $serie;
        $this->correlative = $correlative;
        $this->exchange_type = $exchange_type;
        $this->payment_type = $payment_type;
        $this->currency = $currency;
        $this->date = $date;
        $this->date_ven = $date_ven;
        $this->days = $days;
        $this->observation = $observation;
        $this->detraccion = $detraccion;
        $this->fech_detraccion = $fech_detraccion;
        $this->amount_detraccion = $amount_detraccion;
        $this->is_detracion = $is_detracion;
        $this->subtotal = $subtotal;
        $this->total_desc = $total_desc;
        $this->inafecto = $inafecto;
        $this->igv = $igv;
        $this->total = $total;
        $this->is_igv = $is_igv;
        $this->type_document_id = $type_document_id;
        $this->reference_serie = $reference_serie;
        $this->reference_correlative = $reference_correlative;
        $this->company_id = $company_id;
        $this->saldo = $saldo;
    }

    public function getId(): int|null
    {
        return $this->id;
    }
    public function getBranch(): Branch|null
    {
        return $this->branch;
    }
    public function getSupplier(): Customer | null
    {
        return $this->supplier;
    }
    public function getSerie(): string
    {
        return $this->serie;
    }
    public function getCorrelative(): string
    {
        return $this->correlative;
    }
    public function getExchangeType(): string
    {
        return $this->exchange_type;
    }
    public function getPaymentType(): PaymentType | null
    {
        return $this->payment_type;
    }
    public function getCurrency(): CurrencyType | null
    {
        return $this->currency;
    }
    public function getDate(): string
    {
        return $this->date;
    }
    public function getDateVen(): string
    {
        return $this->date_ven;
    }
    public function getDays(): int
    {
        return $this->days;
    }
    public function getObservation(): string
    {
        return $this->observation;
    }
    public function getDetraccion(): string
    {
        return $this->detraccion;
    }
    public function getFechDetraccion(): string
    {
        return $this->fech_detraccion;
    }
    public function getAmountDetraccion(): float
    {
        return $this->amount_detraccion;
    }
    public function getIsDetracion(): bool
    {
        return $this->is_detracion;
    }
    public function getSubtotal(): float
    {
        return $this->subtotal;
    }
    public function getTotalDesc(): float
    {
        return $this->total_desc;
    }
    public function getInafecto(): float
    {
        return $this->inafecto;
    }
    public function getIgv(): float
    {
        return $this->igv;
    }
    public function getTotal(): float
    {
        return $this->total;
    }
    public function getIsIgv(): bool
    {
        return $this->is_igv;
    }
    public function getTypeDocumentId(): ?DocumentType
    {
        return $this->type_document_id;
    }
    public function getReferenceSerie(): string
    {
        return $this->reference_serie;
    }
    public function getReferenceCorrelative(): string
    {
        return $this->reference_correlative;
    }
    public function getCompanyId(): int
    {
        return $this->company_id;
    }
    public function getSaldo(): float
    {
        return $this->saldo;
    }
}
