<?php

namespace App\Modules\ScVoucherdet\application\DTOS;

class ScVoucherdetDTO
{
    public int $cia;
    public int $codcon;
    public int $tipdoc;
    public string $glosa;
    public float $impsol;
    public float $impdol;
    public ?int $id_purchase;
    public ?int $id_sc_voucher;
    public string $numdoc;
    public ?string $correlativo;
    public ?string $serie;

    public function __construct(array $data)
    {
        $this->cia = $data['cia']??0;
        $this->codcon = $data['codcon'];
        $this->tipdoc = $data['tipdoc'];
        $this->glosa = $data['glosa'];
        $this->impsol = $data['impsol'];
        $this->impdol = $data['impdol'];
        $this->id_purchase = $data['id_purchase'] ?? null;
        $this->id_sc_voucher = $data['id_sc_voucher'] ?? null;
        $this->numdoc = $data['numdoc'];
        $this->correlativo = $data['correlativo'] ?? null;
        $this->serie = $data['serie'] ?? null;
    }
}
