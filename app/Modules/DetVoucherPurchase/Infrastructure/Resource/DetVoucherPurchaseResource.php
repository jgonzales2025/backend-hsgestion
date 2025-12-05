<?php
namespace App\Modules\DetVoucherPurchase\Infrastructure\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class DetVoucherPurchaseResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getId(),
            'voucher_id' => $this->resource->getVoucherId(),
            'purchase_id' => $this->resource->getPurchaseId(),
            'amount' => $this->resource->getAmount(),
        ];
    }
    
}