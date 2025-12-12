<?php

namespace App\Modules\DispatchNotes\application\DTOS;

class DispatchNoteDTO
{
  public int $cia_id;
  public int $branch_id;
  public string $serie;
  public string $correlativo;
  public int $emission_reason_id;
  public ?string $description;
  public ?int $destination_branch_id;
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
  public ?bool $vehicle_type;
  public int $reference_document_type_id;
  public ?int $destination_branch_client ;
  public int $customer_id;
  public ?int $supplier_id;
  public ?int $address_supplier_id;



  public function __construct(array $date)
  {
    $this->cia_id = $date['cia_id'] ?? 1;
    $this->branch_id = $date['branch_id'] ?? 1;
    $this->serie = $date['serie'] ?? '';
    $this->correlativo = $date['correlativo'] ?? '';
    $this->emission_reason_id = $date['emission_reason_id'] ?? 1;
    $this->description = $date['description'] ?? '';
    $this->destination_branch_id = $date['destination_branch_id'] ?? null;
    $this->destination_address_customer = $date['destination_address_customer'] ?? '';
    $this->transport_id = $date['transport_id'] ?? 1;
    $this->observations = $date['observations'] ?? '';
    $this->num_orden_compra = $date['num_orden_compra'] ?? null;
    $this->doc_referencia = $date['doc_referencia'] ?? null;
    $this->num_referencia = $date['num_referencia'] ?? null;
    $this->date_referencia = $date['date_referencia'] ?? null;
    $this->status = $date['status'] ?? true;
    $this->cod_conductor = $date['cod_conductor'] ?? 1;
    $this->license_plate = $date['license_plate'] ?? '';
    $this->total_weight = $date['total_weight'];
    $this->transfer_type = $date['transfer_type'];
    $this->vehicle_type = $date['vehicle_type']?? 1;
    $this->reference_document_type_id = $date['reference_document_type_id'] ?? 1;
    $this->destination_branch_client = $date['destination_branch_client_id'] ?? null;
    $this->customer_id = $date['customer_id'] ?? 1;
    $this->supplier_id = $date['supplier_id'] ?? null;
    $this->address_supplier_id = $date['address_supplier_id'] ?? null;
  

  }

}