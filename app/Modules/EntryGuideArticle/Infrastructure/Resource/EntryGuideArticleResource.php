<?php

namespace App\Modules\EntryGuideArticle\Infrastructure\Resource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class   EntryGuideArticleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->getId(),
            'entry_guide_id' => $this->resource->getEntryGuideId(),
            'article_id' => $this->resource->getArticle()->getId(),
            'description' => $this->resource->getDescription(),
            'quantity' => $this->resource->getQuantity(),
            'sku' => $this->resource->getArticle()->getCodFab(),
            'saldo' => $this->resource->getSaldo(),
            'serials' => array_map(
                fn($itemSerial) => method_exists($itemSerial, 'getSerial') ? $itemSerial->getSerial() : $itemSerial,
                $this->resource->serials ?? []
            ),
            'subtotal' => $this->resource->getSubtotal(),
            'total' => $this->resource->getTotal(),
            'precio_costo' => $this->resource->getTotalDescuento(),
            'descuento' => $this->resource->getDescuento(),
        ];
    }
}
