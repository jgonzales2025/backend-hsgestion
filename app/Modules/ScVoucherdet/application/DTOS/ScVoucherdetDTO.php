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

    public function __construct(array $data) {
        $this->cia = $data['cia'];
        $this->codcon = $data['codcon'];
        $this->tipdoc = $data['tipdoc'];
        $this->glosa = $data['glosa'];
        $this->impsol = $data['impsol'];
        $this->impdol = $data['impdol'];
    }
}