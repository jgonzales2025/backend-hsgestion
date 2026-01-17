<?php

namespace App\Modules\Warranty\Infrastructure\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class WarrantyArticleResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getId(),
            'cod_fab' => $this->resource->getCodFab(),
            'description' => $this->resource->getDescription()
        ];
    }
}