<?php

namespace App\Modules\CustomerType\Infrastructure\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerTypeResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getId(),
            'description' => $this->resource->getDescription(),
            'status' => $this->resource->getStatus(),
        ];
    }
}
