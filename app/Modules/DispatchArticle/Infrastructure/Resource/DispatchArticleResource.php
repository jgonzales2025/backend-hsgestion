<?php

namespace App\Modules\DispatchArticle\Infrastructure\Resource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DispatchArticleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->getId(),
            'dispatch_id' => $this->resource->getDispatchID(),
            'article_id' => $this->resource->getArticleID(),
            'quantity' => $this->resource->getQuantity(),
            'weight' => $this->resource->getWeight(),
            'saldo' => $this->resource->getSaldo(),
            'name' => $this->resource->getName(),
            'subtotal_weight' => $this->resource->getsubTotalWeight()
        ];
    }
}