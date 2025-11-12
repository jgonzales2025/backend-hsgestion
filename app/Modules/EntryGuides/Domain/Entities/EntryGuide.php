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
    private ?string $serie;
    private ?string $correlative;
    private string $date;
    private ?Customer $customer;
    private string $observations;
    private ?IngressReason $ingressReason;
    private ?string $reference_po_serie; //opcional purchase order
    private ?string $reference_po_correlative; //opcional purchase order
    private bool $status;

    public function __construct(
        ?int $id,
        ?Company $cia,
        ?Branch $branch,
        ?string $serie,
        ?string $correlative,
        string $date,
        ?Customer $customer,
        string $observations,
        ?IngressReason $ingressReason,
        ?string $reference_po_serie, //opcional purchase order
        ?string $reference_po_correlative, //opcional purchase order
        bool $status,
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
        $this->reference_po_serie = $reference_po_serie;
        $this->reference_po_correlative = $reference_po_correlative;
        $this->status = $status;

    }
    public function getId(): int|null
    {
        return $this->id;
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
    public function getObservations(): string
    {
        return $this->observations;
    }
    public function getReferenceSerie(): string|null
    {
        return $this->reference_po_serie;
    }
    public function getReferenceCorrelative(): string|null
    {
        return $this->reference_po_correlative;
    }
    public function getStatus(): bool
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

}