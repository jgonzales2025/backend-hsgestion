<?php
namespace App\Modules\DetEntryguidePurchaseOrder\Infrastrucutre\Resource;

use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Request;

class DetEntryguidePurchaseOrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->getId(),
            'purchase_order_id' => $this->resource->getPurchaseOrderId(),
            'entry_guide_id' => $this->resource->getEntryGuideId(),
        ];
    }
}