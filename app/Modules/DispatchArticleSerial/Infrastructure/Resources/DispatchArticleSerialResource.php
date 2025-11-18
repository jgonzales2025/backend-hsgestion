<?php

namespace App\Modules\DispatchArticleSerial\Infrastructure\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DispatchArticleSerialResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getId(),
            'dispatch_note' => [
                'id' => $this->resource->getDispatchNote()->getId(),
                'serie' => $this->resource->getDispatchNote()->getSerie(),
                'correlative' => $this->resource->getDispatchNote()->getCorrelative(),
            ],
            'article' => [
                'id' => $this->resource->getArticle()->getId(),
                'name' => $this->resource->getArticle()->getDescription(),
            ],
            'serial' => $this->resource->getSerial(),
            'emission_reasons_id' => $this->resource->getEmissionReasonsId(),
            'status' => $this->resource->getStatus(),
            'origin_branch' => [
                'id' => $this->resource->getOriginBranch()->getId(),
                'name' => $this->resource->getOriginBranch()->getName(),
            ],
            'destination_branch' => [
                'id' => $this->resource->getDestinationBranch()->getId(),
                'name' => $this->resource->getDestinationBranch()->getName(),
            ],
        ];
    }
}