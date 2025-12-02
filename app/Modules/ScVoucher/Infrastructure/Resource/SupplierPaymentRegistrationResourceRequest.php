<?php

namespace App\Modules\SupplierPaymentRegistration\Infrastructure\Resource;

use App\Modules\SupplierPaymentRegistration\Domain\Entities\SupplierPaymentRegistration;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierPaymentRegistrationResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->resource->getId(),
            'cia' => $this->resource->getCia(),
            'anopr' => $this->resource->getAnopr(),
            'correlativo' => $this->resource->getCorrelativo(),
            'fecha' => $this->resource->getFecha(),
            'codban' => $this->resource->getCodban(),
            'codigo' => $this->resource->getCodigo(),
            'nroope' => $this->resource->getNroope(),
            'glosa' => $this->resource->getGlosa(),
            'orden' => $this->resource->getOrden(),
            'tipmon' => $this->resource->getTipmon(),
            'tipcam' => $this->resource->getTipcam(),
            'total' => $this->resource->getTotal(),
            'medpag' => $this->resource->getMedpag(),
            'tipopago' => $this->resource->getTipopago(),
            'status' => $this->resource->getStatus(),
            'usradi' => $this->resource->getUsradi(),
            'fecadi' => $this->resource->getFecadi(),
            'usrmod' => $this->resource->getUsrmod(),
            'fecmod' => $this->resource->getFecmod(),
        ];
    }
}
