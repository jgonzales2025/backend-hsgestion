<?php
namespace App\Modules\DispatchNotes\Domain\Entities;

use App\Modules\Branch\Domain\Entities\Branch;
use App\Modules\Company\Domain\Entities\Company;
use App\Modules\Driver\Domain\Entities\Driver;
use App\Modules\EmissionReason\Domain\Entities\EmissionReason;
use App\Modules\TransportCompany\Domain\Entities\TransportCompany;

class DispatchNote
{
    private int $id;
    private ?Company $company;
    private ?Branch $branch;
    private string $serie;
    private int $correlativo;
    private string $date;
    private ?EmissionReason $emission_reason;
    private ?string $description;
    private ?Branch $destination_branch;
    private string $destination_address_customer;
    private ?TransportCompany $transport;
    private ?string $observations;
    private ?string $num_orden_compra;
    private ?string $doc_referencia;
    private ?string $num_referencia;
    private ?string $serie_referencia;
    private ?string $date_referencia;
    private bool $status;
    private ?Driver $conductor;
    private string $license_plate;
    private float $total_weight;
    private string $transfer_type;
    private string $vehicle_type;

    public function __construct(
        int $id,
        ?Company $company,
        ?Branch $branch,
        string $serie,
        int $correlativo,
        string $date,
        ?EmissionReason $emission_reason,
        ?string $description,
        ?Branch $destination_branch,
        string $destination_address_customer,
        ?TransportCompany $transport,
        ?string $observations,
        ?string $num_orden_compra,
        ?string $doc_referencia,
        ?string $num_referencia,
        ?string $serie_referencia,
        ?string $date_referencia,
        bool $status,
        ?Driver $conductor,
        string $license_plate,
        float $total_weight,
        string $transfer_type,
        string $vehicle_type
    ) {
        $this->id = $id;
        $this->company = $company;
        $this->branch = $branch;
        $this->serie = $serie;
        $this->correlativo = $correlativo;
        $this->date = $date;
        $this->emission_reason = $emission_reason;
        $this->description = $description;
        $this->destination_branch = $destination_branch;
        $this->destination_address_customer = $destination_address_customer;
        $this->transport = $transport;
        $this->observations = $observations;
        $this->num_orden_compra = $num_orden_compra;
        $this->doc_referencia = $doc_referencia;
        $this->num_referencia = $num_referencia;
        $this->serie_referencia = $serie_referencia;
        $this->date_referencia = $date_referencia;
        $this->status = $status;
        $this->conductor = $conductor;
        $this->license_plate = $license_plate;
        $this->total_weight = $total_weight;
        $this->transfer_type = $transfer_type;
        $this->vehicle_type = $vehicle_type;
    }

    // Getters
    public function getId(): int { return $this->id; }
    public function getCompany(): Company|null { return $this->company; }
    public function getBranch(): Branch|null { return $this->branch; }
    public function getSerie(): string { return $this->serie; }
    public function getCorrelativo(): int { return $this->correlativo; }
    public function getDate(): string { return $this->date; }
    public function getEmissionReason(): EmissionReason|null { return $this->emission_reason; }
    public function getDescription(): ?string { return $this->description; }
    public function getDestinationBranch(): Branch|null { return $this->destination_branch; }
    public function getDestinationAddressCustomer(): string { return $this->destination_address_customer; }
    public function getTransport(): TransportCompany|null { return $this->transport; }
    public function getObservations(): ?string { return $this->observations; }
    public function getNumOrdenCompra(): ?string { return $this->num_orden_compra; }
    public function getDocReferencia(): ?string { return $this->doc_referencia; }
    public function getNumReferencia(): ?string { return $this->num_referencia; }
    public function getSerieReferencia(): ?string { return $this->serie_referencia; }
    public function getDateReferencia(): ?string { return $this->date_referencia; }
    public function isStatus(): bool { return $this->status; }
    public function getConductor(): Driver|null { return $this->conductor; }
    public function getLicensePlate(): string { return $this->license_plate; }
    public function getTotalWeight(): float { return $this->total_weight; }
    public function getTransferType(): string { return $this->transfer_type; }
    public function getVehicleType(): string { return $this->vehicle_type; }
}