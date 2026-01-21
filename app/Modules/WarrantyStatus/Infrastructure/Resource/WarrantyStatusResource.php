<?php

namespace App\Modules\WarrantyStatus\Infrastructure\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class WarrantyStatusResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getId(),
            'name' => $this->resource->getName(),
            'color' => $this->resource->getColor(),
            'status' => $this->resource->getStatus(),
            'type' => $this->resource->getStWarranty() == 1 ? 1 : 2
        ];
    }
}