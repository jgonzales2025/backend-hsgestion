<?php

namespace App\Modules\EntryGuideArticle\Infrastructure\Resource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EntryGuideArticleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->getId(),
            'entry_guide_id' => $this->resource->getEntryGuideId(),
            'article_id' => $this->resource->getArticle()->getId(),
            'description' => $this->resource->getDescription(),
            'quantity' => $this->resource->getQuantity(),
            'serials' => $this->resource->serials ?? []
        ];
    }
}
