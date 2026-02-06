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
    public ?string $reference_serie;
    public ?string $reference_correlative;
    public  float $subtotal;
    public float $total_descuento;
    public float $total;
    public bool $update_price;
    public float $entry_igv;
    public int $currency_id;
    public bool $includ_igv;
    public int $reference_document_id;
    public ?int $nc_document_id;
    public ?string $nc_reference_serie;
    public ?string $nc_reference_correlative;
    public ?int $payment_type_id;
    public ?int $days;
    public ?string $date_ven;



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
        $this->reference_serie = $array['reference_serie'] ?? null;
        $this->reference_correlative = $array['reference_correlative'] ?? null;
        $this->subtotal = $array['subtotal'];
        $this->total_descuento = $array['total_descuento'];
        $this->total = $array['total'];
        $this->update_price = (bool) ($array['update_price'] ?? false);
        $this->entry_igv = $array['entry_igv'] ?? 0;
        $this->currency_id = $array['currency_id'];
        $this->includ_igv = (bool) ($array['includ_igv'] ?? false);
        $this->reference_document_id = $array['reference_document_id'];
        $this->nc_document_id = $array['nc_document_id'] ?? null;
        $this->nc_reference_serie = $array['nc_reference_serie'] ?? null;
        $this->nc_reference_correlative = $array['nc_reference_correlative'] ?? null;
        $this->payment_type_id = $array['payment_type_id'] ?? null;
        $this->days = $array['days'] ?? null;
        $this->date_ven = $array['date_ven'] ?? null;
    }
}
