<?php

namespace App\Modules\DispatchNotes\application\DTOS;

class DispatchNoteDTO
{
public int $cia_id;
public int $branch_id;
public string $serie;
public string $correlativo;
public string $date;
public int $emission_reason_id;
public ?string $description;
public int $destination_branch_id;
public string $destination_address_customer;
public int $transport_id;
public ?string $observations;
public ?string $num_orden_compra;
public ?string $doc_referencia;
public ?string $num_referencia;
public ?string $date_referencia;
public bool $status;
public int $cod_conductor;
public string $license_plate;
public float $total_weight;
public string $transfer_type;
public string $vehicle_type;
public int $document_type_id;
 public int $destination_branch_client_id;
 public int $customer_id;

  
   public function __construct(array $date){
    $this->cia_id = $date['cia_id']??1;
    $this->branch_id = $date['branch_id']??1;
    $this->serie = $date['serie']??2;
    $this->correlativo = $date['correlativo'] ?? '';
    $this->date = $date['date'];
    $this->emission_reason_id = $date['emission_reason_id']??1;
    $this->description = $date['description'];
    $this->destination_branch_id = $date['destination_branch_id']??1;
    $this->destination_address_customer = $date['destination_address_customer'];
    $this->transport_id = $date['transport_id']??1;
    $this->observations = $date['observations'];
    $this->num_orden_compra = $date['num_orden_compra'];
    $this->doc_referencia = $date['doc_referencia'];
    $this->num_referencia = $date['num_referencia'];
    $this->date_referencia = $date['date_referencia'];
    $this->status = $date['status'];
    $this->cod_conductor = $date['cod_conductor']??1;
    $this->license_plate = $date['license_plate'];
    $this->total_weight = $date['total_weight'];
    $this->transfer_type = $date['transfer_type'];
    $this->vehicle_type = $date['vehicle_type'];
    $this->document_type_id = $date['document_type_id']??1;
     $this->destination_branch_client_id = $date['destination_branch_client_id']??1;
       $this->customer_id = $date['customer_id']??1;
   
    }

}