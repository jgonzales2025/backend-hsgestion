<?php

namespace App\Modules\PurchaseOrder\Application\DTOs;

class PurchaseOrderDTO
{
    public int $company_id;
    public int $branch_id;
    public string $serie;
    public ?string $correlative;
    public string $date;
    public ?string $delivery_date;
    public ?string $due_date;
    public ?int $days;
    public int $currency_type_id;
    public float $parallel_rate;
    public ?string $contact_name;
    public ?string $contact_phone;
    public int $payment_type_id;
    public ?string $order_number_supplier;
    public ?string $observations;
    public int $supplier_id;
    public ?int $status;
    public int $percentage_igv;
    public bool $is_igv_included;
    public float $subtotal;
    public float $igv;
    public float $total;

    public function __construct(array $data)
    {
        $this->company_id = $data['company_id'];
        $this->branch_id = $data['branch_id'];
        $this->serie = $data['serie'];
        $this->correlative = $data['correlative'] ?? null;
        $this->date = $data['date'];
        $this->delivery_date = $data['delivery_date'];
        $this->due_date = $data['due_date'];
        $this->days = $data['days'];
        $this->currency_type_id = $data['currency_type_id'];
        $this->parallel_rate = $data['parallel_rate'];
        $this->contact_name = $data['contact_name'];
        $this->contact_phone = $data['contact_phone'];
        $this->payment_type_id = $data['payment_type_id'];
        $this->order_number_supplier = $data['order_number_supplier'] ?? null;
        $this->observations = $data['observations'] ?? null;
        $this->supplier_id = $data['supplier_id'];
        $this->status = $data['status'] ?? null;
        $this->percentage_igv = $data['percentage_igv'];
        $this->is_igv_included = $data['is_igv_included'];
        $this->subtotal = $data['subtotal'];
        $this->igv = $data['igv'];
        $this->total = $data['total'];
    }
}
