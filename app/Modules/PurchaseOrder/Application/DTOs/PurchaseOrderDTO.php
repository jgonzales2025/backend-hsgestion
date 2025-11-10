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
    public ?string $contact;
    public ?string $order_number_supplier;
    public int $supplier_id;
    public ?int $status;

    public function __construct(array $data)
    {
        $this->company_id = $data['company_id'];
        $this->branch_id = $data['branch_id'];
        $this->serie = $data['serie'];
        $this->correlative = $data['correlative'] ?? null;
        $this->date = $data['date'];
        $this->delivery_date = $data['delivery_date'];
        $this->contact = $data['contact'];
        $this->order_number_supplier = $data['order_number_supplier'];
        $this->supplier_id = $data['supplier_id'];
        $this->status = $data['status'] ?? null;
    }
}
