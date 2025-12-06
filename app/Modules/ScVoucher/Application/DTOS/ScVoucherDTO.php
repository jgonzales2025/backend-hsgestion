<?php

namespace App\Modules\ScVoucher\Application\DTOS;

class ScVoucherDTO
{
    public ?int $cia;
    public int $anopr;
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

    public function __construct(array $data)
    {
        $this->cia = $data['cia'] ?? null;
        $this->anopr = $data['anopr'] ?? 2025;
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
        $this->fecadi = $data['fecadi'];
        $this->usrmod = $data['usrmod'];
    }
}
