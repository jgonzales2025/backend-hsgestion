<?php

namespace App\Modules\Sale\Domain\Entities;

use App\Modules\Branch\Domain\Entities\Branch;
use App\Modules\Company\Domain\Entities\Company;
use App\Modules\CurrencyType\Domain\Entities\CurrencyType;
use App\Modules\Customer\Domain\Entities\Customer;
use App\Modules\DocumentType\Domain\Entities\DocumentType;
use App\Modules\PaymentType\Domain\Entities\PaymentType;
use App\Modules\User\Domain\Entities\User;

class Sale
{
    private int $id;
    private Company $company;
    private DocumentType $documentType;
    private string $serie;
    private int $document_number;
    private float $parallel_rate;
    private Customer $customer;
    private string $date;
    private string $due_date;
    private int $days;
    private User $user;
    private PaymentType $paymentType;
    private ?string $observations;
    private CurrencyType $currencyType;
    private float $subtotal;
    private float $igv;
    private float $total;
    private ?int $status;
    private ?bool $is_locked;

    public function __construct(
        int $id,
        Company $company,
        DocumentType $documentType,
        string $serie,
        int $document_number,
        float $parallel_rate,
        Customer $customer,
        string $date,
        string $due_date,
        int $days,
        User $user,
        PaymentType $paymentType,
        ?string $observations,
        CurrencyType $currencyType,
        float $subtotal,
        float $igv,
        float $total,
        ?int $status,
        ?bool $is_locked,
    ){
        $this->id = $id;
        $this->company = $company;
        $this->documentType = $documentType;
        $this->serie = $serie;
        $this->document_number = $document_number;
        $this->parallel_rate = $parallel_rate;
        $this->customer = $customer;
        $this->date = $date;
        $this->due_date = $due_date;
        $this->days = $days;
        $this->user = $user;
        $this->paymentType = $paymentType;
        $this->observations = $observations;
        $this->currencyType = $currencyType;
        $this->subtotal = $subtotal;
        $this->igv = $igv;
        $this->total = $total;
        $this->status = $status;
        $this->is_locked = $is_locked;
    }

    public function getId(): int { return $this->id; }
    public function getCompany(): Company { return $this->company; }
    public function getDocumentType(): DocumentType { return $this->documentType; }
    public function getSerie(): string { return $this->serie; }
    public function getDocumentNumber(): int { return $this->document_number; }
    public function getParallelRate(): float { return $this->parallel_rate; }
    public function getCustomer(): Customer { return $this->customer; }
    public function getDate(): string { return $this->date; }
    public function getDueDate(): string { return $this->due_date; }
    public function getDays(): int { return $this->days; }
    public function getUser(): User { return $this->user; }
    public function getPaymentType(): PaymentType { return $this->paymentType; }
    public function getObservations(): string|null { return $this->observations; }
    public function getCurrencyType(): CurrencyType { return $this->currencyType; }
    public function getSubtotal(): float { return $this->subtotal; }
    public function getIgv(): float { return $this->igv; }
    public function getTotal(): float { return $this->total; }
    public function getStatus(): ?int { return $this->status; }
    public function getIsLocked(): ?bool { return $this->is_locked; }
}
