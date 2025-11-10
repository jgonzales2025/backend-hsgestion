<?php

namespace App\Modules\EntryGuides\Application\DTOS;

class EntryGuideDTO
{
    public $cia_id;
    public $branch_id;
    public $serie;
    public $correlative;
    public $date;
    public $customer_id;
    public $guide_serie_supplier;
    public $guide_correlative_supplier;
    public $invoice_serie_supplier;
    public $invoice_correlative_supplier;
    public $observations;
    public $ingress_reason_id;
    public $reference_serie;
    public $reference_correlative;
    public $status;

    public function __construct($array){
        $this->cia_id = $array['cia_id'];
        $this->branch_id = $array['branch_id'];
        $this->serie = $array['serie'];
        $this->correlative = $array['correlative'];
        $this->date = $array['date'];
        $this->customer_id = $array['customer_id'];
        $this->guide_serie_supplier = $array['guide_serie_supplier'];
        $this->guide_correlative_supplier = $array['guide_correlative_supplier'];
        $this->invoice_serie_supplier = $array['invoice_serie_supplier'];
        $this->invoice_correlative_supplier = $array['invoice_correlative_supplier'];
        $this->observations = $array['observations'];
        $this->ingress_reason_id = $array['ingress_reason_id'];
        $this->reference_serie = $array['reference_serie'];
        $this->reference_correlative = $array['reference_correlative'];
        $this->status = $array['status'];

    }
}