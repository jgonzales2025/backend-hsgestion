<?php

namespace App\Modules\Kardex\Infrastructure\Resource;

use App\Modules\Kardex\Domain\Entities\Kardex;
use Illuminate\Http\Resources\Json\JsonResource;

class KardexResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getId(),
            'document_type_id' => $this->resource->getDocumentTypeId(),
            'document_id' => $this->resource->getDocumentId(),
            'product_id' => $this->resource->getProductId(),
            'quantity' => $this->resource->getQuantity(),
            'price' => $this->resource->getPrice(),
            'total' => $this->resource->getTotal(),
            'status' => $this->resource->getStatus(),
        ];
    }
}
