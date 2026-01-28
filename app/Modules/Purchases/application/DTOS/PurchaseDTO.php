<?php

namespace App\Modules\Purchases\Application\DTOS;

use App\Modules\DetailPurchaseGuides\Application\DTOS\DetailPurchaseGuideDTO;
use App\Modules\ShoppingIncomeGuide\Application\DTOS\ShoppingIncomeGuideDTO;

class PurchaseDTO
{
    public int $company_id;
    public int $branch_id;
    public int $supplier_id;
    public string $serie;
    public string $correlative;
    public  $exchange_type;
    public int $payment_type_id;
    public  $currency;
    public string $date;
    public string $date_ven;
    public int $days;
    public ?string $observation;
    public  $detraccion;
    public  ?string $fech_detraccion;
    public  $amount_detraccion;
    public bool $is_detracion;
    public  $subtotal;
    public  $total_desc;
    public  $inafecto;
    public  $igv;
    public  $total;
    public bool $is_igv;
    public int $type_document_id;
    public ?string $reference_serie;
    public ?string $reference_correlative;
    public array $det_compras_guia_ingreso;
    public array $shopping_Income_Guide;
    public ?int $nc_document_id;
    public ?string $nc_reference_serie;
    public ?string $nc_reference_correlative;
    public bool $status;

    public function __construct(array $array)
    {
        $this->company_id = $array['company_id'];
        $this->branch_id = $array['branch_id'];
        $this->supplier_id = $array['supplier_id'];
        $this->serie = $array['serie'];
        $this->correlative = $array['correlative'] ?? '';
        $this->exchange_type = $array['exchange_type'] ?? null;
        $this->payment_type_id = $array['payment_type_id'];
        $this->currency = $array['currency_id'];
        $this->date = $array['date'];
        $this->date_ven = $array['date_ven'];
        $this->days = $array['days'];
        $this->observation = $array['observation'] ?? '';
        $this->detraccion = $array['detraccion'] ?? '';
        $this->fech_detraccion = $array['fech_detraccion'] ?? null;
        $this->amount_detraccion = $array['amount_detraccion'];
        $this->is_detracion = $array['is_detracion'];
        $this->subtotal = $array['subtotal'];
        $this->total_desc = $array['total_desc'];
        $this->inafecto = $array['inafecto'];
        $this->igv = $array['igv'];
        $this->total = $array['total'];
        $this->is_igv = $array['is_igv'];
        $this->type_document_id = $array['reference_document_type_id'];
        $this->reference_serie = $array['reference_serie'];
        $this->reference_correlative = $array['reference_correlative'];

        $this->det_compras_guia_ingreso = array_map(function ($item) {
            return new DetailPurchaseGuideDTO($item);
        }, $array['det_compras_guia_ingreso']) ?? [];

        $this->shopping_Income_Guide = array_map(function ($item) {
            return new ShoppingIncomeGuideDTO([
                'entry_guide_id' => $item
            ]);
        }, $array['entry_guide_id']) ?? [];

        $this->nc_document_id = $array['nc_document_id'] ?? null;
        $this->nc_reference_serie = $array['nc_reference_serie'] ?? null;
        $this->nc_reference_correlative = $array['nc_reference_correlative'] ?? null;
        $this->status = $array['status'] ?? true;
    }
}
