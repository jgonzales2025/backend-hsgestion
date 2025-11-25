<?php

namespace App\Modules\PettyCashReceipt\Domain\Entities;

use App\Modules\Branch\Domain\Entities\Branch;
use App\Modules\CurrencyType\Domain\Entities\CurrencyType;
use App\Modules\DocumentType\Domain\Entities\DocumentType;
use App\Modules\PettyCashMotive\Domain\Entities\PettyCashMotive;

class PettyCashReceipt
{
    private ?int $id;
    private int $company_id;
    private ?DocumentType $document_type;
    private string $series;
    private string $correlative;
    private string $date;
    private ?string $delivered_to;
    private ?PettyCashMotive $reason_code;
    private ?CurrencyType $currency;
    private float $amount;
    private string $observation;
    private int $status;
    private ?Branch $branch;


    public function __construct(
        ?int $id,
        int $company_id,
        ?DocumentType $document_type,
        string $series,
        string $correlative,
        string $date,
        ?string $delivered_to,
        ?PettyCashMotive $reason_code,
        ?CurrencyType $currency,
        float $amount,
        string $observation,
        int $status,
        ?Branch $branch
    ) {
        $this->id = $id;
        $this->company_id = $company_id;
        $this->document_type = $document_type;
        $this->series = $series;
        $this->correlative = $correlative;
        $this->date = $date;
        $this->delivered_to = $delivered_to;
        $this->reason_code = $reason_code;
        $this->currency = $currency;
        $this->amount = $amount;
        $this->observation = $observation;
        $this->status = $status;
        $this->branch = $branch;
    }

    public function getId(): int|null { return $this->id; }
    public function getCompany(): int { return $this->company_id; }
    public function getDocumentType(): DocumentType|null { return $this->document_type; }
    public function getSeries(): string { return $this->series; }
    public function getCorrelative(): string { return $this->correlative; }
    public function getDate(): string { return $this->date; }
    public function getDeliveredTo(): ?string { return $this->delivered_to; }
    public function getReasonCode(): ?PettyCashMotive { return $this->reason_code; }
    public function getCurrencyType(): CurrencyType|null { return $this->currency; }
    public function getAmount(): float { return $this->amount; }
    public function getObservation(): string { return $this->observation; }
    public function getStatus(): int { return $this->status; }
    public function getBranch(): ?Branch { return $this->branch; }

    
}