<?php

namespace App\Modules\MeasurementUnit\Infrastructure\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MeasurementUnitResource extends JsonResource
{
    public function toArray($request): array
    {
        $isEntity = method_exists($this->resource, 'getId');
        
        if ($isEntity) {
            return [
                'id' => $this->resource->getId(),
                'name' => $this->resource->getName(),
                'abbreviation' => $this->resource->getAbbreviation(),
                'status' => $this->resource->getStatus() == 1 ? 'Activo' : 'Inactivo'
            ];
        } else {
            return [
                'id' => $this->resource->id,
                'name' => $this->resource->name,
                'abbreviation' => $this->resource->abbreviation,
                'status' => $this->resource->status == 1 ? 'Activo' : 'Inactivo'
            ];
        }
    }
}
