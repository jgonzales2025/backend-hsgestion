<?php

namespace App\Modules\Purchases\Domain\Entities;

use App\Modules\Branch\Domain\Entities\Branch;
use App\Modules\CurrencyType\Domain\Entities\CurrencyType;
use App\Modules\Customer\Domain\Entities\Customer;
use App\Modules\PaymentMethod\Domain\Entities\PaymentMethod;

class Purchase
{
    private ?int $id;
    private ?Branch $branch;
    private ?Customer $supplier;
    private string $serie;
    private string $correlative;
    private float $exchange_type;
    private ?PaymentMethod $methodpaymentO;
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

    public function __construct(
        ?int $id,
        ?Branch $branch,
        ?Customer $supplier,
        string $serie,
        string $correlative,
        float $exchange_type,
        ?PaymentMethod $methodpaymentO,
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
        float $total
    ) {
        $this->id = $id;
        $this->branch = $branch;
        $this->supplier = $supplier;
        $this->serie = $serie;
        $this->correlative = $correlative;
        $this->exchange_type = $exchange_type;
        $this->methodpaymentO = $methodpaymentO;
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
    public function getMethodpayment(): PaymentMethod | null
    {
        return $this->methodpaymentO;
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

}