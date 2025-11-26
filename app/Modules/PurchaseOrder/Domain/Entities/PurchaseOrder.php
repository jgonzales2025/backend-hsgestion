<?php

namespace App\Modules\PurchaseOrder\Domain\Entities;

use App\Modules\Branch\Domain\Entities\Branch;
use App\Modules\CurrencyType\Domain\Entities\CurrencyType;
use App\Modules\Customer\Domain\Entities\Customer;
use App\Modules\PaymentType\Domain\Entities\PaymentType;

class PurchaseOrder
{
    private int $id;
    private int $company_id;
    private Branch $branch;
    private string $serie;
    private ?string $correlative;
    private string $date;
    private ?string $delivery_date;
    private ?string $due_date;
    private ?int $days;
    private CurrencyType $currencyType;
    private float $parallel_rate;
    private ?string $contact_name;
    private ?string $contact_phone;
    private PaymentType $paymentType;
    private ?string $order_number_supplier;
    private ?string $observations;
    private Customer $supplier;
    private ?string $status;
    private float $subtotal;
    private float $igv;
    private float $total;

    public function __construct(int $id, int $company_id, Branch $branch, string $serie, ?string $correlative, string $date, ?string $delivery_date, ?string $due_date, ?int $days, CurrencyType $currencyType, float $parallel_rate, ?string $contact_name, ?string $contact_phone, PaymentType $paymentType, ?string $order_number_supplier, ?string $observations, Customer $supplier, ?string $status, float $subtotal, float $igv, float $total)
    {
        $this->id = $id;
        $this->company_id = $company_id;
        $this->branch = $branch;
        $this->serie = $serie;
        $this->correlative = $correlative;
        $this->date = $date;
        $this->delivery_date = $delivery_date;
        $this->due_date = $due_date;
        $this->days = $days;
        $this->currencyType = $currencyType;
        $this->parallel_rate = $parallel_rate;
        $this->contact_name = $contact_name;
        $this->contact_phone = $contact_phone;
        $this->paymentType = $paymentType;
        $this->order_number_supplier = $order_number_supplier;
        $this->observations = $observations;
        $this->supplier = $supplier;
        $this->status = $status;
        $this->subtotal = $subtotal;
        $this->igv = $igv;
        $this->total = $total;
    }

    public function getId(): int { return $this->id; }
    public function getCompanyId(): int { return $this->company_id; }
    public function getBranch(): Branch { return $this->branch; }
    public function getSerie(): string { return $this->serie; }
    public function getCorrelative(): string|null { return $this->correlative; }
    public function getDate(): string { return $this->date; }
    public function getDeliveryDate(): ?string { return $this->delivery_date; }
    public function getDueDate(): ?string { return $this->due_date; }
    public function getDays(): ?int { return $this->days; }
    public function getCurrencyType(): CurrencyType { return $this->currencyType; }
    public function getParallelRate(): float { return $this->parallel_rate; }
    public function getContactName(): ?string { return $this->contact_name; }
    public function getContactPhone(): ?string { return $this->contact_phone; }
    public function getPaymentType(): PaymentType { return $this->paymentType; }
    public function getOrderNumberSupplier(): ?string { return $this->order_number_supplier; }
    public function getObservations(): ?string { return $this->observations; }
    public function getSupplier(): Customer { return $this->supplier; }
    public function getStatus(): ?string { return $this->status; }
    public function getSubtotal(): float { return $this->subtotal; }
    public function getIgv(): float { return $this->igv; }
    public function getTotal(): float { return $this->total; }
}
