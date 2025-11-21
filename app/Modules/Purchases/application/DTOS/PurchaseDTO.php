<?php

namespace App\Modules\Purchases\Application\DTOS;

class PurchaseDTO{
    public int $branch_id;
    public int $supplier_id;
    public string $serie;
    public string $correlative;
    public  $exchange_type;
    public string $methodpayment;
    public  $currency;
    public string $date;
    public string $date_ven;
    public int $days;
    public string $observation;
    public  $detraccion;
    public string $fech_detraccion;
    public  $amount_detraccion;
    public bool $is_detracion;
    public  $subtotal;
    public  $total_desc;
    public  $inafecto;
    public  $igv;
    public  $total;
   
    public function __construct(array $array){
        $this->branch_id = $array['branch_id'];
        $this->supplier_id = $array['supplier_id'];
        $this->serie = $array['serie'];
        $this->correlative = $array['correlative'];
        $this->exchange_type = $array['exchange_type'];
        $this->methodpayment = $array['methodpayment'];
        $this->currency = $array['currency'];
        $this->date = $array['date'];
        $this->date_ven = $array['date_ven'];
        $this->days = $array['days'];
        $this->observation = $array['observation'];
        $this->detraccion = $array['detraccion'];
        $this->fech_detraccion = $array['fech_detraccion'];
        $this->amount_detraccion = $array['amount_detraccion'];
        $this->is_detracion = $array['is_detracion'];
        $this->subtotal = $array['subtotal'];
        $this->total_desc = $array['total_desc'];
        $this->inafecto = $array['inafecto'];
        $this->igv = $array['igv'];
        $this->total = $array['total'];
    
    }
    
}