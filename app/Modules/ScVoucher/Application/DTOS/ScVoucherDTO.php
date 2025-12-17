<?php

namespace App\Modules\ScVoucher\Application\DTOS;

use App\Modules\DetVoucherPurchase\application\DTOS\DetVoucherPurchaseDTO;
use App\Modules\ScVoucherdet\application\DTOS\ScVoucherdetDTO;

class ScVoucherDTO
{
    public ?int $cia;
    public string $anopr;
    public string $correlativo;
    public string $fecha;
    public int $codban;
    public int $codigo;
    public string $nroope;
    public ?string $glosa;
    public ?string $orden;
    public int $tipmon;
    public float $tipcam;
    public float $total;
    public int $medpag;
    public int $tipopago;
    public int $status;
    public int $usradi;
    public string $fecadi;
    public int $usrmod;
    public array $detail_sc_voucher = [];
    public array $detail_voucher_purchase = [];


    public function __construct(array $data)
    {
        $this->cia = $data['cia'] ?? null;
        $this->anopr = $data['anopr'] ?? "";
        $this->correlativo = $data['correlative'] ?? '';
        $this->fecha = $data['fecha'];
        $this->codban = $data['codban'];
        $this->codigo = $data['codigo'];
        $this->nroope = $data['nroope'];
        $this->glosa = $data['glosa'] ?? null;
        $this->orden = $data['orden'] ?? null;
        $this->tipmon = $data['tipmon'];
        $this->tipcam = $data['tipcam'];
        $this->total = $data['total'];
        $this->medpag = $data['medpag_id'];
        $this->tipopago = $data['tipopago'];
        $this->status = $data['status'] ?? 1;
        $this->usradi = $data['usradi'];
        $this->fecadi = $data['fecadi'] ?? "2025-12-02";
        $this->usrmod = $data['usrmod'];
        $this->detail_sc_voucher = array_map(
            fn($d) => new ScVoucherdetDTO($d),
            $data['detail_sc_voucher'] ?? []
        );
        $this->detail_voucher_purchase = array_map(
            fn($d) => new DetVoucherPurchaseDTO($d),
            $data['detail_voucher_purchase'] ?? []
        );
    }
}
