<?php

namespace App\Modules\DispatchNotes\Domain\Entities;

use App\Modules\Branch\Domain\Entities\Branch;
use App\Modules\Company\Domain\Entities\Company;
use App\Modules\Customer\Domain\Entities\Customer;
use App\Modules\Driver\Domain\Entities\Driver;
use App\Modules\EmissionReason\Domain\Entities\EmissionReason;
use App\Modules\TransportCompany\Domain\Entities\TransportCompany;
use App\Modules\DocumentType\Domain\Entities\DocumentType;

class DispatchNote
{
    private ?int $id;
    private ?Company $company;
    private ?Branch $branch;
    private string $serie;
    private string $correlativo;
    private ?EmissionReason $emission_reason;
    private ?string $description;
    private ?Branch $destination_branch;
    // private ?int $destination_address_customer;
    private ?TransportCompany $transport;
    private ?string $observations;
    private ?string $num_orden_compra;
    private ?string $doc_referencia;
    private ?string $num_referencia;
    private ?string $date_referencia;
    private bool $status;
    private ?Driver $conductor;
    private ?string $license_plate;
    private ?float $total_weight;
    private ?int $transfer_type;
    private ?bool $vehicle_type;
    private ?int $destination_branch_client;
    private ?int $customer_id;
    private ?string $created_at;
    private ?string $estado_sunat;
    private ?Customer $supplier;
    private ?Customer $address_supplier;
    private ?DocumentType $reference_document_type;


    public function __construct(
        ?int $id,
        ?Company $company,
        ?Branch $branch,
        string $serie,
        string $correlativo,
        ?EmissionReason $emission_reason,
        ?string $description,
        ?Branch $destination_branch,
        ?TransportCompany $transport,
        ?string $observations,
        ?string $num_orden_compra,
        ?string $doc_referencia,
        ?string $num_referencia,
        ?string $date_referencia,
        bool $status,
        ?Driver $conductor,
        ?string $license_plate,
        ?float $total_weight,
        ?int $transfer_type,
        ?bool $vehicle_type,
        ?int $destination_branch_client,
        ?int $customer_id,
        ?Customer $supplier,
        ?Customer $address_supplier,
        ?DocumentType $reference_document_type,
        ?string $created_at,
        ?string $estado_sunat = null


    ) {
        $this->id = $id;
        $this->company = $company;
        $this->branch = $branch;
        $this->serie = $serie;
        $this->correlativo = $correlativo;
        $this->emission_reason = $emission_reason;
        $this->description = $description;
        $this->destination_branch = $destination_branch;
        $this->transport = $transport;
        $this->observations = $observations;
        $this->num_orden_compra = $num_orden_compra;
        $this->doc_referencia = $doc_referencia;
        $this->num_referencia = $num_referencia;
        $this->date_referencia = $date_referencia;
        $this->status = $status;
        $this->conductor = $conductor;
        $this->license_plate = $license_plate;
        $this->total_weight = $total_weight;
        $this->transfer_type = $transfer_type;
        $this->vehicle_type = $vehicle_type;
        $this->destination_branch_client = $destination_branch_client;
        $this->customer_id = $customer_id;
        $this->supplier = $supplier;
        $this->address_supplier = $address_supplier;
        $this->reference_document_type = $reference_document_type;
        $this->created_at = $created_at;
        $this->estado_sunat = $estado_sunat;
    }

    // Getters
    public function getId(): int|null
    {
        return $this->id;
    }
    public function getCompany(): Company|null
    {
        return $this->company;
    }
    public function getBranch(): Branch|null
    {
        return $this->branch;
    }
    public function getSerie(): string
    {
        return $this->serie;
    }
    public function getCorrelativo(): string
    {
        return $this->correlativo;
    }
    public function getEmissionReason(): EmissionReason|null
    {
        return $this->emission_reason;
    }
    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function getDestinationBranch(): Branch|null
    {
        return $this->destination_branch;
    }
    public function getTransport(): TransportCompany|null
    {
        return $this->transport;
    }
    public function getObservations(): ?string
    {
        return $this->observations;
    }
    public function getNumOrdenCompra(): ?string
    {
        return $this->num_orden_compra;
    }
    public function getDocReferencia(): ?string
    {
        return $this->doc_referencia;
    }
    public function getNumReferencia(): ?string
    {
        return $this->num_referencia;
    }
    public function getDateReferencia(): ?string
    {
        return $this->date_referencia;
    }
    public function getStatus(): bool
    {
        return $this->status;
    }
    public function getConductor(): Driver|null
    {
        return $this->conductor;
    }
    public function getLicensePlate(): ?string
    {
        return $this->license_plate;
    }
    public function getTotalWeight(): ?float
    {
        return $this->total_weight;
    }
    public function getTransferType(): ?int
    {
        return $this->transfer_type;
    }
    public function getVehicleType(): ?bool
    {
        return $this->vehicle_type;
    }
    public function getdestination_branch_client(): int|null
    {
        return $this->destination_branch_client;
    }
    public function getCustomerId(): ?int
    {
        return $this->customer_id;
    }
    public function getCreatedFecha(): ?string
    {
        return $this->created_at;
    }
    public function setCreatedAt(?string $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function getEstadoSunat(): ?string
    {
        return $this->estado_sunat;
    }

    public function setEstadoSunat(?string $estado_sunat): void
    {
        $this->estado_sunat = $estado_sunat;
    }
    public function getSupplier(): ?Customer
    {
        return $this->supplier;
    }
    public function getAddressSupplier(): ?Customer
    {
        return $this->address_supplier;
    }

    public function getReferenceDocumentType(): ?DocumentType
    {
        return $this->reference_document_type;
    }
}
