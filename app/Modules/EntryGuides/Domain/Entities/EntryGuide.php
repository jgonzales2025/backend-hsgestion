<?php

namespace App\Modules\EntryGuides\Domain\Entities;

use App\Modules\Branch\Domain\Entities\Branch;
use App\Modules\Company\Domain\Entities\Company;
use App\Modules\CurrencyType\Domain\Entities\CurrencyType;
use App\Modules\Customer\Domain\Entities\Customer;
use App\Modules\IngressReason\Domain\Entities\IngressReason;
use App\Modules\PaymentType\Domain\Entities\PaymentType;

class EntryGuide
{
    private ?int $id;
    private ?Company $cia;
    private ?Branch $branch;
    private ?string $serie;
    private ?string $correlative;
    private string $date;
    private ?Customer $customer;
    private ?string $observations;
    private ?IngressReason $ingressReason;
    private ?string $reference_serie; //opcional purchase order
    private ?string $reference_correlative; //opcional purchase order
    private ?bool $status;
    private float $subtotal;
    private float $total_descuento;
    private float $total;
    private bool $update_price;
    private float $entry_igv;
    private ?CurrencyType $currency;
    private bool $includ_igv;
    private ?int $reference_document_id;
    private float $saldo;
    private ?int $nc_document_id;
    private ?string $nc_reference_serie;
    private ?string $nc_reference_correlative;
    private ?PaymentType $payment_type;
    private ?int $days;
    private ?string $date_ven;

    public function __construct(
        ?int $id,
        ?Company $cia,
        ?Branch $branch,
        ?string $serie,
        ?string $correlative,
        string $date,
        ?Customer $customer,
        ?string $observations,
        ?IngressReason $ingressReason,
        ?string $reference_serie, //opcional purchase order
        ?string $reference_correlative, //opcional purchase order
        ?bool $status,
        float $subtotal,
        float $total_descuento,
        float $total,
        bool $update_price = false,
        float $entry_igv,
        ?CurrencyType $currency,
        bool $includ_igv,
        ?int $reference_document_id,
        float $saldo = 0,
        ?int $nc_document_id = null,
        ?string $nc_reference_serie = null,
        ?string $nc_reference_correlative = null,
        ?PaymentType $payment_type = null,
        ?int $days = null,
        ?string $date_ven = null
    ) {
        $this->id = $id;
        $this->cia = $cia;
        $this->branch = $branch;
        $this->serie = $serie;
        $this->correlative = $correlative;
        $this->date = $date;
        $this->customer = $customer;
        $this->observations = $observations;
        $this->ingressReason = $ingressReason;
        $this->reference_serie = $reference_serie;
        $this->reference_correlative = $reference_correlative;
        $this->status = $status;
        $this->subtotal = $subtotal;
        $this->total_descuento = $total_descuento;
        $this->total = $total;
        $this->update_price = $update_price;
        $this->entry_igv = $entry_igv;
        $this->currency = $currency;
        $this->includ_igv = $includ_igv;
        $this->reference_document_id = $reference_document_id;
        $this->saldo = $saldo;
        $this->nc_document_id = $nc_document_id;
        $this->nc_reference_serie = $nc_reference_serie;
        $this->nc_reference_correlative = $nc_reference_correlative;
        $this->payment_type = $payment_type;
        $this->days = $days;
        $this->date_ven = $date_ven;
    }
    public function getId(): int|null
    {
        return $this->id;
    }
    public function getNcDocumentId(): ?int
    {
        return $this->nc_document_id;
    }

    public function getNcReferenceSerie(): ?string
    {
        return $this->nc_reference_serie;
    }

    public function getNcReferenceCorrelative(): ?string
    {
        return $this->nc_reference_correlative;
    }
    public function getSerie(): ?string
    {
        return $this->serie;
    }
    public function getCorrelativo(): ?string
    {
        return $this->correlative;
    }

    public function getDate(): string
    {
        return $this->date;
    }
    public function getObservations(): ?string
    {
        return $this->observations;
    }
    public function getReferenceSerie(): string|null
    {
        return $this->reference_serie;
    }
    public function getReferenceCorrelative(): string|null
    {
        return $this->reference_correlative;
    }
    public function getStatus(): ?bool
    {
        return $this->status;
    }
    public function getIngressReason(): IngressReason|null
    {
        return $this->ingressReason;
    }
    public function getBranch(): Branch|null
    {
        return $this->branch;
    }
    public function getCompany(): Company|null
    {
        return $this->cia;
    }
    public function getCustomer(): Customer|null
    {
        return $this->customer;
    }
    public function getSubtotal(): float
    {
        return $this->subtotal;
    }
    public function getTotalDescuento(): float
    {
        return $this->total_descuento;
    }
    public function getTotal(): float
    {
        return $this->total;
    }

    public function getUpdatePrice(): bool
    {
        return $this->update_price;
    }
    public function getEntryIgv()
    {
        return $this->entry_igv;
    }
    public function getCurrency(): ?CurrencyType
    {
        return $this->currency;
    }
    public function getIncludIgv()
    {
        return $this->includ_igv;
    }
    public function getReferenceDocument(): int|null
    {
        return $this->reference_document_id;
    }
    public function getIgv(): float
    {
        return $this->entry_igv;
    }
    public function getSupplier(): Customer|null
    {
        return $this->customer;
    }
    public function getSaldo(): float
    {
        return $this->saldo;
    }
    public function getPaymentType(): ?PaymentType
    {
        return $this->payment_type;
    }
    public function getDays(): ?int
    {
        return $this->days;
    }
    public function getDateVen(): ?string
    {
        return $this->date_ven;
    }
}
