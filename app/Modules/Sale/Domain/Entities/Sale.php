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
    private string $document_number;
    private float $parallel_rate;
    private Customer $customer;
    private string $date;
    private string $due_date;
    private int $days;
    private User $user;
    private User $user_sale;
    private PaymentType $paymentType;
    private ?string $observations;
    private CurrencyType $currencyType;
    private float $subtotal;
    private float $inafecto;
    private float $igv;
    private float $total;
    private ?int $status;
    private ?int $payment_status;
    private ?bool $is_locked;
    private ?string $serie_prof;
    private ?string $correlative_prof;
    private ?string $purchase_order;

    public function __construct(
        int $id,
        Company $company,
        DocumentType $documentType,
        string $serie,
        string $document_number,
        float $parallel_rate,
        Customer $customer,
        string $date,
        string $due_date,
        int $days,
        User $user,
        User $user_sale,
        PaymentType $paymentType,
        ?string $observations,
        CurrencyType $currencyType,
        float $subtotal,
        float $inafecto,
        float $igv,
        float $total,
        ?int $status,
        ?int $payment_status,
        ?bool $is_locked,
        ?string $serie_prof,
        ?string $correlative_prof,
        ?string $purchase_order
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
        $this->user_sale = $user_sale;
        $this->paymentType = $paymentType;
        $this->observations = $observations;
        $this->currencyType = $currencyType;
        $this->subtotal = $subtotal;
        $this->inafecto = $inafecto;
        $this->igv = $igv;
        $this->total = $total;
        $this->status = $status;
        $this->payment_status = $payment_status;
        $this->is_locked = $is_locked;
        $this->serie_prof = $serie_prof;
        $this->correlative_prof = $correlative_prof;
        $this->purchase_order = $purchase_order;
    }

    public function getId(): int { return $this->id; }
    public function getCompany(): Company { return $this->company; }
    public function getDocumentType(): DocumentType { return $this->documentType; }
    public function getSerie(): string { return $this->serie; }
    public function getDocumentNumber(): string { return $this->document_number; }
    public function getParallelRate(): float { return $this->parallel_rate; }
    public function getCustomer(): Customer { return $this->customer; }
    public function getDate(): string { return $this->date; }
    public function getDueDate(): string { return $this->due_date; }
    public function getDays(): int { return $this->days; }
    public function getUser(): User { return $this->user; }
    public function getUserSale(): User { return $this->user_sale; }
    public function getPaymentType(): PaymentType { return $this->paymentType; }
    public function getObservations(): string|null { return $this->observations; }
    public function getCurrencyType(): CurrencyType { return $this->currencyType; }
    public function getSubtotal(): float { return $this->subtotal; }
    public function getInafecto(): float { return $this->inafecto; }
    public function getIgv(): float { return $this->igv; }
    public function getTotal(): float { return $this->total; }
    public function getStatus(): ?int { return $this->status; }
    public function getPaymentStatus(): ?int { return $this->payment_status; }
    public function getIsLocked(): ?bool { return $this->is_locked; }
    public function getSerieProf(): string|null { return $this->serie_prof; }
    public function getCorrelativeProf(): string|null { return $this->correlative_prof; }
    public function getPurchaseOrder(): string|null { return $this->purchase_order; }
}
