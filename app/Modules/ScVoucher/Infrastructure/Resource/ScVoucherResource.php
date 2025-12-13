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
            'codban' => [
                'id' => $this->getCodban()?->getId(),
                'name' => $this->getCodban()?->getName(),
            ],
            'codigo' => [
                'id' => $this->getCodigo()?->getId(),
                'name' => $this->getCodigo()?->getName()?? $this->getCodigo()?->getCompanyName(),
            ],
            'nroope' => $this->getNroope(),
            'glosa' => $this->getGlosa(),
            'orden' => $this->getOrden(),
            'tipmon' => [
                'id' => $this->getTipmon()?->getId(),
                'name' => $this->getTipmon()?->getName(),
            ],
            'tipcam' => $this->getTipcam(),
            'total' => $this->getTotal(),
            'medpag' => [
                'id' => $this->getMedpag()?->getCod(),
                'name' => $this->getMedpag()?->getDes(),
            ],
            'tipopago' => [
                'id' => $this->getTipopago()?->getId(),
                'name' => $this->getTipopago()?->getName(),
            ],
            'status' => $this->getStatus(),
            'usradi' => $this->getUsradi(),
            'fecadi' => $this->getFecadi(),
            'usrmod' => $this->getUsrmod(),
            'total_soles' =>(float) number_format($this->getTotal() / $this->getTipcam(),4),
            'total_dolares' => (float)number_format($this->getTotal() * $this->getTipcam(),4),
        ];
    }
}
