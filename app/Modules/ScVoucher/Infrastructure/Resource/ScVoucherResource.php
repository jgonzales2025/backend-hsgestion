<?php

namespace App\Modules\ScVoucher\Infrastructure\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class ScVoucherResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->getId(),
            'cia' => $this->getCia(),
            'anopr' => $this->getAnopr(),
            'correlativo' => $this->getCorrelativo(),
            'fecha' => $this->getFecha(),
            'codban' => $this->getCodban(),
            'codigo' => $this->getCodigo(),
            'nroope' => $this->getNroope(),
            'glosa' => $this->getGlosa(),
            'orden' => $this->getOrden(),
            'tipmon' => $this->getTipmon(),
            'tipcam' => $this->getTipcam(),
            'total' => $this->getTotal(),
            'medpag' => $this->getMedpag(),
            'tipopago' => $this->getTipopago(),
            'status' => $this->getStatus(),
            'usradi' => $this->getUsradi(),
            'fecadi' => $this->getFecadi(),
            'usrmod' => $this->getUsrmod(),
            'fecmod' => $this->getFecmod(),
        ];
    }
}
