<?php

namespace App\Modules\PurchaseGuideArticle\Infrastructure\Resource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseGuideArticleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->getId(),
            'purchase_Guide_id' => $this->resource->getPurchaseGuideId(),
            'article_id' => $this->resource->getArticleId(),
            'description' => $this->resource->getDescription(),
            'quantity' => $this->resource->getQuantity(),

        ];
    }
}