<?php

namespace App\Modules\Sale\Domain\Entities;

use App\Modules\Branch\Domain\Entities\Branch;
use App\Modules\Company\Domain\Entities\Company;
use App\Modules\CurrencyType\Domain\Entities\CurrencyType;
use App\Modules\Customer\Domain\Entities\Customer;
use App\Modules\DocumentType\Domain\Entities\DocumentType;
use App\Modules\NoteReason\Domain\Entities\NoteReason;
use App\Modules\PaymentType\Domain\Entities\PaymentType;
use App\Modules\User\Domain\Entities\User;

class Sale
{
    private int $id;
    private Company $company;
    private Branch $branch;
    private DocumentType $documentType;
    private string $serie;
    private string $document_number;
    private float $parallel_rate;
    private Customer $customer;
    private string $date;
    private string $due_date;
    private int $days;
    private User $user;
    private ?User $user_sale;
    private PaymentType $paymentType;
    private ?string $observations;
    private CurrencyType $currencyType;
    private float $subtotal;
    private float $igv;
    private float $total;
    private ?float $saldo;
    private ?float $amount_amortized;
    private ?int $status;
    private ?int $payment_status;
    private ?bool $is_locked;
    private ?int $id_prof;
    private ?string $serie_prof;
    private ?string $correlative_prof;
    private ?string $purchase_order;
    private ?User $user_authorized;
    private ?float $credit_amount;
    private ?int $coddetrac;
    private ?float $pordetrac;
    private ?float $impdetracs;
    private ?float $impdetracd;
    private ?float $stretencion;
    private ?float $porretencion;
    private ?float $impretens;
    private ?float $impretend;
    private ?float $total_costo_neto;

    public function __construct(
        int $id,
        Company $company,
        Branch $branch,
        DocumentType $documentType,
        string $serie,
        string $document_number,
        float $parallel_rate,
        Customer $customer,
        string $date,
        string $due_date,
        int $days,
        User $user,
        ?User $user_sale,
        PaymentType $paymentType,
        ?string $observations,
        CurrencyType $currencyType,
        float $subtotal,
        float $igv,
        float $total,
        ?float $saldo,
        ?float $amount_amortized,
        ?int $payment_status,
        ?bool $is_locked,
        ?int $id_prof,
        ?string $serie_prof,
        ?string $correlative_prof,
        ?string $purchase_order,
        ?User $user_authorized,
        ?float $credit_amount,
        ?int $coddetrac,
        ?float $pordetrac,
        ?float $impdetracs,
        ?float $impdetracd,
        ?float $stretencion,
        ?float $porretencion,
        ?float $impretens,
        ?float $impretend,
        ?int $status = null,
        ?float $total_costo_neto = null,
    ){
        $this->id = $id;
        $this->company = $company;
        $this->branch = $branch;
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
        $this->igv = $igv;
        $this->total = $total;
        $this->saldo = $saldo;
        $this->amount_amortized = $amount_amortized;
        $this->status = $status;
        $this->payment_status = $payment_status;
        $this->is_locked = $is_locked;
        $this->id_prof = $id_prof;
        $this->serie_prof = $serie_prof;
        $this->correlative_prof = $correlative_prof;
        $this->purchase_order = $purchase_order;
        $this->user_authorized = $user_authorized;
        $this->credit_amount = $credit_amount;
        $this->coddetrac = $coddetrac;
        $this->pordetrac = $pordetrac;
        $this->impdetracs = $impdetracs;
        $this->impdetracd = $impdetracd;
        $this->stretencion = $stretencion;
        $this->porretencion = $porretencion;
        $this->impretens = $impretens;
        $this->impretend = $impretend;
        $this->total_costo_neto = $total_costo_neto;
    }

    public function getId(): int { return $this->id; }
    public function getCompany(): Company { return $this->company; }
    public function getBranch(): Branch { return $this->branch; }
    public function getDocumentType(): DocumentType { return $this->documentType; }
    public function getSerie(): string { return $this->serie; }
    public function getDocumentNumber(): string { return $this->document_number; }
    public function getParallelRate(): float { return $this->parallel_rate; }
    public function getCustomer(): Customer { return $this->customer; }
    public function getDate(): string { return $this->date; }
    public function getDueDate(): string { return $this->due_date; }
    public function getDays(): int { return $this->days; }
    public function getUser(): User { return $this->user; }
    public function getUserSale(): User|null { return $this->user_sale; }
    public function getPaymentType(): PaymentType { return $this->paymentType; }
    public function getObservations(): string|null { return $this->observations; }
    public function getCurrencyType(): CurrencyType { return $this->currencyType; }
    public function getSubtotal(): float { return $this->subtotal; }
    public function getIgv(): float { return $this->igv; }
    public function getTotal(): float { return $this->total; }
    public function getSaldo(): ?float { return $this->saldo; }
    public function getAmountAmortized(): ?float { return $this->amount_amortized; }
    public function getStatus(): ?int { return $this->status; }
    public function getPaymentStatus(): ?int { return $this->payment_status; }
    public function getIsLocked(): ?bool { return $this->is_locked; }
    public function getIdProf(): ?int { return $this->id_prof; }
    public function getSerieProf(): string|null { return $this->serie_prof; }
    public function getCorrelativeProf(): string|null { return $this->correlative_prof; }
    public function getPurchaseOrder(): string|null { return $this->purchase_order; }
    public function getUserAuthorized(): ?User { return $this->user_authorized; }
    public function getCreditAmount(): ?float { return $this->credit_amount; }
    public function getCoddetrac(): ?int { return $this->coddetrac; }
    public function getPordetrac(): ?float { return $this->pordetrac; }
    public function getImpdetracs(): ?float { return $this->impdetracs; }
    public function getImpdetracd(): ?float { return $this->impdetracd; }
    public function getStretencion(): ?float { return $this->stretencion; }
    public function getPorretencion(): ?float { return $this->porretencion; }
    public function getImpretens(): ?float { return $this->impretens; }
    public function getImpretend(): ?float { return $this->impretend; }
    public function getTotalCostoNeto(): ?float { return $this->total_costo_neto; }
}
