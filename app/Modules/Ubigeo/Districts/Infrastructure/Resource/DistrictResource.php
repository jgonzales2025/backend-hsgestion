<?php

namespace App\Modules\Ubigeo\Districts\Infrastructure\Resource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DistrictResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'coddep' => $this->resource->getCoddep(),
            'codpro' => $this->resource->getCodpro(),
            'coddis' => $this->resource->getCoddis(),
            'nomdis' => $this->resource->getNomdis(),
        ];
    }
}
