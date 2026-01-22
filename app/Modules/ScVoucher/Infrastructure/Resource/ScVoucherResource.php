<?php

namespace App\Modules\ScVoucher\Infrastructure\Resource;

use App\Modules\DetVoucherPurchase\Infrastructure\Resource\DetVoucherPurchaseResource;
use App\Modules\ScVoucherdet\Infrastructure\Resource\ScVoucherdetResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ScVoucherResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getId(),
            'cia' => $this->resource->getCia(),
            'anopr' => $this->resource->getAnopr(),
            'correlativo' => $this->resource->getCorrelativo(),
            'fecha' => $this->resource->getFecha(),
            'codban' => [
                'id' => $this->resource->getCodban()?->getId(),
                'name' => $this->resource->getCodban()?->getName(),
            ],
            'codigo' => [
                'id' => $this->resource->getCodigo()?->getId(),
                'name' => $this->resource->getCodigo()?->getName() ?? $this->resource->getCodigo()?->getCompanyName(),
            ],
            'nroope' => $this->resource->getNroope(),
            'glosa' => $this->resource->getGlosa(),
            'orden' => $this->resource->getOrden(),
            'tipmon' => [
                'id' => $this->resource->getTipmon()?->getId(),
                'name' => $this->resource->getTipmon()?->getName(),
            ],
            'tipcam' => $this->resource->getTipcam(),
            'total' => $this->resource->getTotal(),
            'medpag' => [
                'id' => $this->resource->getMedpag()?->getCod(),
                'name' => $this->resource->getMedpag()?->getDes(),
            ],
            'tipopago' => [
                'id' => $this->resource->getTipopago()?->getId(),
                 'name' => $this->resource->getTipopago()?->getDescription(),
            ],
            'status' => $this->resource->getStatus(),
            'usradi' => $this->resource->getUsradi(),
            'fecadi' => $this->resource->getFecadi(),
            'usrmod' => $this->resource->getUsrmod(),
            'total_soles' => $this->resource->getTipmon()?->getName() === 'DOLARES' ? (float)number_format($this->resource->getTotal() * $this->resource->getTipcam(), 4) : $this->resource->getTotal(),
            'total_dolares' => $this->resource->getTipmon()?->getName() === 'SOLES' ? (float)number_format($this->resource->getTotal() / $this->resource->getTipcam(), 4) : $this->resource->getTotal(),
            'detail_sc_voucher' => ScVoucherdetResource::collection($this->resource->getDetails()),
            'detail_voucher_purchase'=>DetVoucherPurchaseResource::collection($this->resource->getDetailVoucherpurchase()),
        ];
    }
}
