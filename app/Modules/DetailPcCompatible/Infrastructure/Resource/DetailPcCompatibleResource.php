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
            'id' => $detail && $detail->articleAccessory ? $detail->articleAccessory->id : null,
            'cod_fab' => $detail && $detail->articleAccessory ? $detail->articleAccessory->cod_fab : null,
            'description' => $detail && $detail->articleAccessory ? $detail->articleAccessory->description : null,
            'status' => $this->resource->getStatus(),
        ];
    }
}
