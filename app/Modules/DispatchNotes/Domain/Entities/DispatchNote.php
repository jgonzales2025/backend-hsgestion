<?php
namespace App\Modules\DispatchNotes\Domain\Entities;

use App\Modules\Branch\Domain\Entities\Branch;
use App\Modules\Company\Domain\Entities\Company;
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
    private string $destination_address_customer;
    private ?TransportCompany $transport;
    private ?string $observations;
    private ?string $num_orden_compra;
    private ?string $doc_referencia;
    private ?string $num_referencia;
    private ?string $date_referencia;
    private bool $status;
    private ?Driver $conductor;
    private string $license_plate;
    private float $total_weight;
    private string $transfer_type;
    private bool $vehicle_type;
    private ?DocumentType $document_type;
    private ?int $destination_branch_client;
    private int $customer_id;
    private string $created_at = ""; 

    public function __construct(
        ?int $id,
        ?Company $company,
        ?Branch $branch,
        string $serie,
        string $correlativo,
        ?EmissionReason $emission_reason,
        ?string $description,
        ?Branch $destination_branch,
        string $destination_address_customer,
        ?TransportCompany $transport,
        ?string $observations,
        ?string $num_orden_compra,
        ?string $doc_referencia,
        ?string $num_referencia,
        ?string $date_referencia,
        bool $status,
        ?Driver $conductor,
        string $license_plate,
        float $total_weight,
        string $transfer_type,
        bool $vehicle_type,
        ?DocumentType $document_type,
        ?int $destination_branch_client,
        int $customer_id,

    ) {
        $this->id = $id;
        $this->company = $company;
        $this->branch = $branch;
        $this->serie = $serie;
        $this->correlativo = $correlativo;
        $this->emission_reason = $emission_reason;
        $this->description = $description;
        $this->destination_branch = $destination_branch;
        $this->destination_address_customer = $destination_address_customer;
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
        $this->document_type = $document_type;
        $this->destination_branch_client = $destination_branch_client;
        $this->customer_id = $customer_id;
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
    public function getDestinationAddressCustomer(): string
    {
        return $this->destination_address_customer;
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
    public function getLicensePlate(): string
    {
        return $this->license_plate;
    }
    public function getTotalWeight(): float
    {
        return $this->total_weight;
    }
    public function getTransferType(): string
    {
        return $this->transfer_type;
    }
    public function getVehicleType(): bool
    {
        return $this->vehicle_type;
    }
    public function getDocumentType(): ?DocumentType
    {
        return $this->document_type;
    }
    public function getdestination_branch_client(): int|null
    {
        return $this->destination_branch_client;
    }
    public function getCustomerId(): int
    {
        return $this->customer_id;
    }
    public function getCreatedFecha():?string{
        return $this->created_at;
    }
        public function setCreatedAt(?string $date): void
    {
        $this->created_at = $date;
    }

}