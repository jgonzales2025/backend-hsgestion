<?php

namespace App\Modules\DispatchNotes\Domain\Entities;

use App\Modules\Branch\Domain\Entities\Branch;
use App\Modules\Company\Domain\Entities\Company;
use App\Modules\EmissionReason\Domain\Entities\EmissionReason;

class TransferOrder
{
    private ?int $id;
    private ?Company $company;
    private ?Branch $branch;
    private ?string $serie;
    private ?string $correlative;
    private ?EmissionReason $emission_reason;
    private ?Branch $destination_branch;
    private ?string $observations;
    private ?bool $status;

    public function __construct(
        ?int $id,
        ?Company $company,
        ?Branch $branch,
        ?string $serie,
        ?string $correlative,
        ?EmissionReason $emission_reason,
        ?Branch $destination_branch,
        ?string $observations,
        ?bool $status = false,
    ) {
        $this->id = $id;
        $this->company = $company;
        $this->branch = $branch;
        $this->serie = $serie;
        $this->correlative = $correlative;
        $this->emission_reason = $emission_reason;
        $this->destination_branch = $destination_branch;
        $this->observations = $observations;
        $this->status = $status;
    }

    public function getId(): ?int { return $this->id; }
    public function getCompany(): ?Company { return $this->company; }
    public function getBranch(): ?Branch { return $this->branch; }
    public function getSerie(): ?string { return $this->serie; }
    public function getCorrelative(): ?string { return $this->correlative; }
    public function getEmissionReason(): ?EmissionReason { return $this->emission_reason; }
    public function getDestinationBranch(): ?Branch { return $this->destination_branch; }
    public function getObservations(): ?string { return $this->observations; }
    public function getStatus(): ?bool { return $this->status; }
}