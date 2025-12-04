<?php

namespace App\Modules\BuildDetailPc\Infrastructure\Resource;

use App\Modules\Articles\Infrastructure\Models\EloquentArticle;
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
            'cod_fab' => $this->resource->getCodFab(),
            'description' => $this->resource->getDescription(),
        ];
    }
}
