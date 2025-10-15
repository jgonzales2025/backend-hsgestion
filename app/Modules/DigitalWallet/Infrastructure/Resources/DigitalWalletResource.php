<?php

namespace App\Modules\DigitalWallet\Infrastructure\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DigitalWalletResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->getId(),
            'name' => $this->resource->getName(),
            'phone' => $this->resource->getPhone(),
            'company' => [
                'id' => $this->resource->getCompany()->getId(),
                'ruc' => $this->resource->getCompany()->getRuc(),
                'company_name' => $this->resource->getCompany()->getCompanyName(),
            ],
            'user' => [
                'id' => $this->resource->getUser()->getId(),
                'username' => $this->resource->getUser()->getUsername(),
                'firstname' => $this->resource->getUser()->getFirstName(),
            ],
            'status' => ($this->resource->getStatus()) == 1 ? 'Activo' : 'Inactivo',
        ];
    }
}
