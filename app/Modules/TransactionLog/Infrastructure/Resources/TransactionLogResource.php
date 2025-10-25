<?php

namespace App\Modules\TransactionLog\Infrastructure\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->getId(),
            'user' => [
                'id' => $this->resource->getUser()->getId(),
                'username' => $this->resource->getUser()->getUsername(),
            ],
            'role_id' => $this->resource->getRoleId(),
            'role_name' => $this->resource->getRoleName(),
            'description_log' => $this->resource->getDescriptionLog(),
            'action' => $this->resource->getAction(),
            'company' => [
                'id' => $this->resource->getCompany()->getId(),
                'ruc' => $this->resource->getCompany()->getRuc(),
                'company_name' => $this->resource->getCompany()->getCompanyName(),
                ],
            'branch' => [
                'id' => $this->resource->getBranch()->getId(),
                'name' => $this->resource->getBranch()->getName()
            ],
            'document_type' => [
                'id' => $this->resource->getDocumentType()->getId(),
                'name' => $this->resource->getDocumentType()->getDescription()
            ],
            'serie' => $this->resource->getSerie(),
            'correlative' => $this->resource->getCorrelative(),
            'ip_address' => $this->resource->getIpAddress(),
            'user_agent' => $this->resource->getUserAgent()
        ];
    }
}
