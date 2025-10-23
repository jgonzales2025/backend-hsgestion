<?php

namespace App\Modules\SaleArticle\Infrastructure\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleArticleResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getId(),
            'sale_id' => $this->resource->getSaleId(),
            'article_id' => $this->resource->getArticleId(),
            'description' => $this->resource->getDescription(),
            'quantity' => $this->resource->getQuantity(),
            'unit_price' => $this->resource->getUnitPrice(),
            'subtotal' => $this->resource->getSubTotal()
        ];
    }
}
