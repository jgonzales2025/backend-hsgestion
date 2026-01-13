<?php

namespace App\Modules\DocumentEntryGuide\Infrastructure\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class DocumentEntryGuideResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->resource->getId(),
            'entry_guide_id' => $this->resource->getEntryGuideId(),
            'reference_document' => [
                'id' => $this->resource->getReferenceDocument()->getId(),
                'name' => $this->resource->getReferenceDocument()->getAbbreviation(),
                
            ],
            'reference_serie' => $this->resource->getReferenceSerie(),
            'reference_correlative' => $this->resource->getReferenceCorrelative(),
        ];
    }
}
