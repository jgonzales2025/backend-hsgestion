<?php

namespace App\Modules\SubCategory\Infrastructure\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubCategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        $isEntity = method_exists($this->resource, 'getId');

        if ($isEntity) {
            return [
                'id' => $this->resource->getId(),
                'name' => $this->resource->getName(),
                'category_id' => $this->resource->getCategoryId(),
                'category_name' => $this->resource->getCategoryName(),
                'status' => ($this->resource->getStatus()) == 1 ? 'Activo' : 'Inactivo'
            ];
        } else {
            // Es un modelo Eloquent - acceder a category_name a través de la relación
            return [
                'id' => $this->resource->id,
                'name' => $this->resource->name,
                'category_id' => $this->resource->category_id,
                'category_name' => $this->resource->category?->name ?? 'Sin categoría',
                'status' => ($this->resource->status) == 1 ? 'Activo' : 'Inactivo'
            ];
        }


    }
}
