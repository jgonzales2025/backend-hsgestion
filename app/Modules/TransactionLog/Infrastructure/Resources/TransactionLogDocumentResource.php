<?php

namespace App\Modules\TransactionLog\Infrastructure\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionLogDocumentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->getId(),
            'user' => [
                'id' => $this->resource->getUser()->getId(),
                'username' => $this->resource->getUser()->getUsername(),
            ],
            'transaction_description' => $this->resource->getDescriptionLog(),
            'observations' => $this->resource->getObservations(),
            'action' => $this->resource->getAction(),
            'branch' => [
                'id' => $this->resource->getBranch()?->getId(),
                'name' => $this->resource->getBranch()?->getName()
            ],
            'datetime' => $this->resource->getCreatedAt()
        ];
    }
}