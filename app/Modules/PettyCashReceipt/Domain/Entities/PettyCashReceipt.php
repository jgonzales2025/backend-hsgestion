<?php

namespace App\Modules\PettyCashReceipt\Domain\Entities;
 

class PettyCashReceipt
{
    private ?int $id;
    private ?int $company;
    private int $document_type;
    private string $series;
    private string $correlative;
    private string $date;
    private ?string $delivered_to;
    private int $reason_code;
    private int $currency_type;
    private float $amount;
    private string $observation;
    private int $status;
    private ?int $created_by;
    private ?string $created_at_manual;
    private ?int $updated_by;
    private ?string $updated_at_manual;

    public function __construct(
        ?int $id,
        ?int $company,
        int $document_type,
        string $series,
        string $correlative,
        string $date,
        ?string $delivered_to,
        int $reason_code,
        int $currency_type,
        float $amount,
        string $observation,
        int $status,
        ?int $created_by,
        ?string $created_at_manual,
        ?int $updated_by,
        ?string $updated_at_manual
    ) {
        $this->id = $id;
        $this->company = $company;
        $this->document_type = $document_type;
        $this->series = $series;
        $this->correlative = $correlative;
        $this->date = $date;
        $this->delivered_to = $delivered_to;
        $this->reason_code = $reason_code;
        $this->currency_type = $currency_type;
        $this->amount = $amount;
        $this->observation = $observation;
        $this->status = $status;
        $this->created_by = $created_by;
        $this->created_at_manual = $created_at_manual;
        $this->updated_by = $updated_by;
        $this->updated_at_manual = $updated_at_manual;
    }

    public function getId(): int|null { return $this->id; }
    public function getCompany(): int { return $this->company; }
    public function getDocumentType(): int { return $this->document_type; }
    public function getSeries(): string { return $this->series; }
    public function getCorrelative(): string { return $this->correlative; }
    public function getDate(): string { return $this->date; }
    public function getDeliveredTo(): ?string { return $this->delivered_to; }
    public function getReasonCode(): int { return $this->reason_code; }
    public function getCurrencyType(): int { return $this->currency_type; }
    public function getAmount(): float { return $this->amount; }
    public function getObservation(): string { return $this->observation; }
    public function getStatus(): int { return $this->status; }
    public function getCreatedBy(): ?int { return $this->created_by; }
    public function getCreatedAtManual(): ?string { return $this->created_at_manual; }
    public function getUpdatedBy(): ?int { return $this->updated_by; }
    public function getUpdatedAtManual(): ?string { return $this->updated_at_manual; }
}