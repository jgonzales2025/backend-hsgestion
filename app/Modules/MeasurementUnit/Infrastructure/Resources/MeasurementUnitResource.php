<?php

namespace App\Modules\MeasurementUnit\Infrastructure\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MeasurementUnitResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getId(),
            'name' => $this->resource->getName(),
            'abbreviation' => $this->resource->getAbbreviation(),
            'status' => $this->resource->getStatus(),
        ];
    }
}
