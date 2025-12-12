<?php

namespace App\Modules\Brand\Infrastructure\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    public function toArray($request): array
    {
        $isEntity = method_exists($this->resource, 'getId');

        if ($isEntity) {
            return [
                'id' => $this->resource->getId(),
                'name' => $this->resource->getName(),
                'status' => ($this->resource->getStatus()) == 1 ? 'Activo' : 'Inactivo' ,
            ];
        } else {
            return [
                'id' => $this->resource->id,
                'name' => $this->resource->name,
                'status' => ($this->resource->status) == 1 ? 'Activo' : 'Inactivo' ,
            ];
        }
    }
}
