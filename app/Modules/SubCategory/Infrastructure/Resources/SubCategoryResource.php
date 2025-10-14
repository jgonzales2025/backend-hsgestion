<?php

namespace App\Modules\SubCategory\Infrastructure\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubCategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getId(),
            'name' => $this->resource->getName(),
            'category_id' => $this->resource->getCategoryId(),
            'category_name' => $this->resource->getCategoryName(),
            'status' => ($this->resource->getStatus()) == 1 ? 'Activo' : 'Inactivo'
        ];
    }
}
