<?php

namespace App\Modules\BuildPc\Infrastructure\Resource;

use App\Modules\Articles\Infrastructure\Models\EloquentArticle;
use Illuminate\Http\Resources\Json\JsonResource;

class BuildPcResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->resource->getId(),
            'name' => $this->resource->getName(),
            'description' => $this->resource->getDescription(),
            'total_price' => $this->resource->getTotalPrice(),
            'user_id' => $this->resource->getUserId(),
            'status' => $this->resource->getStatus(),
            'quantity' => $this->resource->getQuantity(),
            'article_ensamb_id' => $this->resource->getArticleEnsambId(),

        ];
    }
}
