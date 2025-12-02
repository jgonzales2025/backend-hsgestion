<?php

namespace App\Modules\BuildDetailPc\Infrastructure\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class BuildDetailPcResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getId(),
            'build_pc_id' => $this->resource->getBuildPcId(),
            'article_id' => $this->resource->getArticleId(),
            'quantity' => $this->resource->getQuantity(),
            'price' => $this->resource->getPrice(),
            'subtotal' => $this->resource->getSubtotal(),
        ];
    }
}
