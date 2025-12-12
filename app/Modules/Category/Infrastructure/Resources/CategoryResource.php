<?php

namespace App\Modules\Category\Infrastructure\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        // Soportar tanto entidades de dominio como modelos Eloquent
        $isEntity = method_exists($this->resource, 'getId');

        if ($isEntity) {
            // Es una entidad de dominio
            return [
                'id' => $this->resource->getId(),
                'name' => $this->resource->getName(),
                'status' => ($this->resource->getStatus()) == 1 ? 'Activo' : 'Inactivo'
            ];
        } else {
            // Es un modelo Eloquent
            return [
                'id' => $this->resource->id,
                'name' => $this->resource->name,
                'status' => $this->resource->status == 1 ? 'Activo' : 'Inactivo'
            ];
        }
    }
}
