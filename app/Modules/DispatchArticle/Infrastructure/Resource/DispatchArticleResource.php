<?php

namespace App\Modules\DispatchArticle\Infrastructure\Resource;

use App\Modules\Articles\Infrastructure\Models\EloquentArticle;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DispatchArticleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Obtener y normalizar los serials
        $serials = $this->resource->serials ?? [];

        // Si es un objeto único, convertirlo a array
        if (!is_array($serials)) {
            $serials = [$serials];
        }

        return [
            'id' => $this->resource->getId(),
            'dispatch_id' => $this->resource->getDispatchID(),
            'article_id' => $this->resource->getArticleID(),
            'quantity' => $this->resource->getQuantity(),
            'weight' => $this->resource->getWeight(),
            'saldo' => $this->resource->getSaldo(),
            'name' => $this->resource->getName(),
            'subtotal_weight' => $this->resource->getsubTotalWeight(),
            'cod_fab' =>  EloquentArticle::where('id', $this->resource->getArticleID())->value('cod_fab'),
            'serials' => array_map(
                fn($itemSerial) => method_exists($itemSerial, 'getSerial') ? $itemSerial->getSerial() : $itemSerial,
                $serials
            ),
        ];
    }
}
