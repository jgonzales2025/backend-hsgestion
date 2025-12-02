<?php

namespace App\Modules\ScVoucher\Application\DTOS;

class ScVoucherDTO
{
    public ?int $cia;
    public int $anopr;
    public int $correlativo;
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
    public string $fecmod;

    public function __construct(array $data)
    {
        $this->cia = $data['cia'] ?? null;
        $this->anopr = $data['anopr'];
        $this->correlativo = $data['correlativo'];
        $this->fecha = $data['fecha'];
        $this->codban = $data['codban'];
        $this->codigo = $data['codigo'];
        $this->nroope = $data['nroope'];
        $this->glosa = $data['glosa'] ?? null;
        $this->orden = $data['orden'] ?? null;
        $this->tipmon = $data['tipmon'];
        $this->tipcam = $data['tipcam'];
        $this->total = $data['total'];
        $this->medpag = $data['medpag'];
        $this->tipopago = $data['tipopago'];
        $this->status = $data['status'];
        $this->usradi = $data['usradi'];
        $this->fecadi = $data['fecadi'];
        $this->usrmod = $data['usrmod'];
        $this->fecmod = $data['fecmod'];
    }
}
