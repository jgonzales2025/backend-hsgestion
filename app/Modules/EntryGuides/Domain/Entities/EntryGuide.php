<?php
namespace App\Modules\EntryGuides\Domain\Entities;

use App\Modules\Branch\Domain\Entities\Branch;
use App\Modules\Company\Domain\Entities\Company;
use App\Modules\Customer\Domain\Entities\Customer;
use App\Modules\IngressReason\Domain\Entities\IngressReason;

class EntryGuide
{
    private ?int $id;
    private ?Company $cia;
    private ?Branch $branch;
    private string $serie;
    private string $correlative;
    private string $date;
    private ?Customer $customer;
    private string $guide_serie_supplier;
    private string $guide_correlative_supplier;
    private string $invoice_serie_supplier;
    private string $invoice_correlative_supplier;
    private string $observations;
    private ?IngressReason $ingressReason;
    private ?string $reference_serie; //opcional
    private ?string $reference_correlative; //opcional
    private bool $status;

    public function __construct(
        ?int $id,
        ?Company $cia,
        ?Branch $branch,
        string $serie,
        string $correlative,
        string $date,
        ?Customer $customer,
        string $guide_serie_supplier,
        string $guide_correlative_supplier,
        string $invoice_serie_supplier,
        string $invoice_correlative_supplier,
        string $observations,
        ?IngressReason $ingressReason,
        ?string $reference_serie, //opcional
        ?string $reference_correlative, //opcional
        bool $status,
    ) {
        $this->id = $id;
        $this->cia = $cia;
        $this->branch = $branch;
        $this->serie = $serie;
        $this->correlative = $correlative;
        $this->date = $date;
        $this->customer = $customer;
        $this->guide_serie_supplier = $guide_serie_supplier;
        $this->guide_correlative_supplier = $guide_correlative_supplier;
        $this->invoice_serie_supplier = $invoice_serie_supplier;
        $this->invoice_correlative_supplier = $invoice_correlative_supplier;
        $this->observations = $observations;
        $this->ingressReason = $ingressReason;
        $this->reference_serie = $reference_serie;
        $this->reference_correlative = $reference_correlative;
        $this->status = $status;

    }
    public function getId(): int|null
    {
        return $this->id;
    }
    public function getSerie(): string
    {
        return $this->serie;
    }
    public function getCorrelativo(): string
    {
        return $this->correlative;
    }

    public function getDate(): string
    {
        return $this->date;
    }
    public function getObservations(): string
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
    public function getStatus(): bool
    {
        return $this->status;
    }
    public function getGuideSerieSupplier(): string
    {
        return $this->guide_serie_supplier;
    }
    public function getGuideCorrelativeSupplier(): string
    {
        return $this->guide_correlative_supplier;
    }
    public function getInvoiceSerieSupplier(): string
    {
        return $this->invoice_serie_supplier;
    }
    public function getInvoiceCorrelativeSupplier(): string
    {
        return $this->invoice_correlative_supplier;
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

}