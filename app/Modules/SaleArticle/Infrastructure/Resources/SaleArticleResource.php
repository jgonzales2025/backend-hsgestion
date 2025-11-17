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
            'sku' => $this->resource->getSku(),
            'article_id' => $this->resource->getArticleId(),
            'description' => $this->resource->getDescription(),
            'quantity' => $this->resource->getQuantity(),
            'unit_price' => $this->resource->getUnitPrice(),
            'subtotal' => $this->resource->getSubTotal(),
            'state_modify_article' => $this->resource->getStateModifyArticle(),
            'series_enabled' => $this->resource->getSeriesEnabled(),
            'serials' => array_map(
                fn($itemSerial) => method_exists($itemSerial, 'getSerial') ? $itemSerial->getSerial() : $itemSerial,
                $this->resource->serials ?? []
            ),
        ];
    }
}
