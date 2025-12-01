<?php

namespace App\Modules\DetailPcCompatible\Infrastructure\Resource;

use App\Modules\DetailPcCompatible\Infrastructure\Models\EloquentDetailPcCompatible;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailPcCompatibleResource extends JsonResource
{
    public function toArray($request)
    {
        // Get the Eloquent model to access relationships
        $detail = EloquentDetailPcCompatible::with(['articleMajor', 'articleAccessory'])
            ->find($this->resource->getId());

        return [
            'id' => $this->resource->getId(),
            'article_major_id' => $this->resource->getArticleMajorId(),
            'article_major' => $detail && $detail->articleMajor ? [
                'id' => $detail->articleMajor->id,
                'cod_fab' => $detail->articleMajor->cod_fab,
                'description' => $detail->articleMajor->description,
            ] : null,
            'article_accesory_id' => $this->resource->getArticleAccesoryId(),
            'article_accessory' => $detail && $detail->articleAccessory ? [
                'id' => $detail->articleAccessory->id,
                'cod_fab' => $detail->articleAccessory->cod_fab,
                'description' => $detail->articleAccessory->description,
            ] : null,
            'status' => $this->resource->getStatus(),
        ];
    }
}
