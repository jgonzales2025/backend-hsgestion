<?php

namespace App\Modules\PurchaseOrderArticle\Infrastructure\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseOrderArticleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->getId(),
            'purchase_order_id' => $this->resource->getPurchaseOrderId(),
            'article_id' => $this->resource->getArticleId(),
            'description' => $this->resource->getDescription(),
            'weight' => $this->resource->getWeight(),
            'quantity' => $this->resource->getQuantity(),
            'purchase_price' => $this->resource->getPurchasePrice(),
            'subtotal' => $this->resource->getSubTotal(),
        ];
    }
}
