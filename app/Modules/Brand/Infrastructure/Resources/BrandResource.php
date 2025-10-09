<?php

namespace App\Modules\Brand\Infrastructure\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getId(),
            'name' => $this->resource->getName(),
            'status' => ($this->resource->getStatus()) == 1 ? 'Activo' : 'Inactivo' ,
        ];
    }
}
