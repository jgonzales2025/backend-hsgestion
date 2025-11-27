<?php

namespace App\Modules\DetailPurchaseGuides\Infrastructure\Resource;

use App\Modules\Articles\Infrastructure\Models\EloquentArticle;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailPurchaseGuideResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->resource->getId(),
            'article_id' => $this->resource->getArticleId(),
            'purchase_order_id' => $this->resource->getPurchaseId(),
            'description' => $this->resource->getDescription(),
            'cantidad' => $this->resource->getCantidad(),
            'precio_costo' => $this->resource->getPrecioCosto(),
            'descuento' => $this->resource->getDescuento(),
            'sub_total' => $this->resource->getSubTotal(),
            'total' => $this->resource->getTotal(),
            'cantidad_update' => $this->resource->getCantidadUpdate(),
            'process_status' => $this->resource->getProcessStatus(),
            // 'SKU' => EloquentArticle::find($this->resource->getArticleId())->getCodFab(),
        ];
    }
}
