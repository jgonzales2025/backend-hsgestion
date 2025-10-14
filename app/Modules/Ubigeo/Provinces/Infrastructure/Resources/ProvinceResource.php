<?php

namespace App\Modules\Ubigeo\Provinces\Infrastructure\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProvinceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'coddep' => $this->resource->getCoddep(),
            'codprov' => $this->resource->getCodpro(),
            'nomprov' => $this->resource->getNompro()
        ];
    }
}
