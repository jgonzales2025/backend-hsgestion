<?php

namespace App\Modules\ScVoucherdet\Infrastructure\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class ScVoucherdetResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->resource->getId(),
            'cia' => $this->resource->getCia(),
            'codcon' => $this->resource->getCodcon(),
            'tipdoc' => $this->resource->getTipdoc(),

            'glosa' => $this->resource->getGlosa(),
            'impsol' => $this->resource->getImpsol(),
            'impdol' => $this->resource->getImpdol(),
            'id_purchase' => $this->resource->getIdPurchase(),
            'id_sc_voucher' => $this->resource->getIdScVoucher(),
            'numdoc'=>$this->resource->getNumdoc(),
            'correlativo'=>$this->resource->getCorrelativo(),
            'serie'=>$this->resource->getSerie(),
        ];
    }
}
