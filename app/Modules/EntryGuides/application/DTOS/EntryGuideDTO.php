<?php

namespace App\Modules\EntryGuides\Application\DTOS;

class EntryGuideDTO
{
    public $cia_id;
    public $branch_id;
    public ?string $serie;
    public ?string $correlative;
    public $date;
    public $customer_id;
    public $observations;
    public $ingress_reason_id;
    public ?string $reference_po_serie;
    public ?string $reference_po_correlative;
    public  float $subtotal;
    public float $total_descuento;
    public float $total;
    public bool $update_price;



    public function __construct($array)
    {
        $this->cia_id = $array['company_id'];
        $this->branch_id = $array['branch_id'];
        $this->serie = $array['serie'] ?? null;
        $this->correlative = $array['correlative'] ?? null;
        $this->date = $array['date'];
        $this->customer_id = $array['customer_id'];
        $this->observations = $array['observations'] ?? '';
        $this->ingress_reason_id = $array['ingress_reason_id'];
        $this->reference_po_serie = $array['reference_po_serie'] ?? null;
        $this->reference_po_correlative = $array['reference_po_correlative'] ?? null;
        $this->subtotal = $array['subtotal'];
        $this->total_descuento = $array['total_descuento'];
        $this->total = $array['total'];
        $this->update_price = (bool) ($array['update_price'] ?? false);
    }
}
