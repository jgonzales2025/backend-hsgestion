<?php

namespace App\Modules\PurchaseOrder\Domain\Entities;

use App\Modules\Customer\Domain\Entities\Customer;

class PurchaseOrder
{
    private int $id;
    private int $company_id;
    private int $branch_id;
    private string $serie;
    private ?string $correlative;
    private string $date;
    private ?string $delivery_date;
    private ?string $contact;
    private ?string $order_number_supplier;
    private ?string $observations;
    private Customer $supplier;
    private ?string $status;
    private float $subtotal;
    private float $igv;
    private float $total;

    public function __construct(int $id, int $company_id, int $branch_id, string $serie, ?string $correlative, string $date, ?string $delivery_date, ?string $contact, ?string $order_number_supplier, ?string $observations, Customer $supplier, ?string $status, float $subtotal, float $igv, float $total)
    {
        $this->id = $id;
        $this->company_id = $company_id;
        $this->branch_id = $branch_id;
        $this->serie = $serie;
        $this->correlative = $correlative;
        $this->date = $date;
        $this->delivery_date = $delivery_date;
        $this->contact = $contact;
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
    public function getBranchId(): int { return $this->branch_id; }
    public function getSerie(): string { return $this->serie; }
    public function getCorrelative(): string|null { return $this->correlative; }
    public function getDate(): string { return $this->date; }
    public function getDeliveryDate(): ?string { return $this->delivery_date; }
    public function getContact(): ?string { return $this->contact; }
    public function getOrderNumberSupplier(): ?string { return $this->order_number_supplier; }
    public function getObservations(): ?string { return $this->observations; }
    public function getSupplier(): Customer { return $this->supplier; }
    public function getStatus(): ?string { return $this->status; }
    public function getSubtotal(): float { return $this->subtotal; }
    public function getIgv(): float { return $this->igv; }
    public function getTotal(): float { return $this->total; }
}
