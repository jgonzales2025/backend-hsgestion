<?php

namespace App\Modules\SaleArticle\Infrastructure\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleArticleCreditNoteResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getId(),
            'sale_id' => $this->resource->getSaleId(),
            'sku' => $this->resource->getSku(),
            'article_id' => $this->resource->getArticle()->getId(),
            'description' => $this->resource->getDescription(),
            'updated_quantity' => $this->resource->getQuantity(),
            'unit_price' => $this->resource->getUnitPrice(),
            'subtotal' => $this->resource->getSubTotal(),
            'state_modify_article' => $this->resource->getStateModifyArticle(),
            'serials' => $this->when(
                isset($this->resource->serials) && !empty($this->resource->serials),
                array_map(fn($itemSerial) => $itemSerial->getSerial(), $this->resource->serials ?? [])
            ),
        ];
    }
}
