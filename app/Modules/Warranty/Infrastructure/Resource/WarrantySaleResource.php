<?php

namespace App\Modules\Warranty\Infrastructure\Resource;

use App\Modules\CustomerEmail\Infrastructure\Resources\CustomerEmailResource;
use App\Modules\CustomerPhone\Infrastructure\Resources\CustomerPhoneResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class WarrantySaleResource extends JsonResource
{
    protected $phones;
    protected $emails;

    public function __construct($resource, $phones = null, $emails = null)
    {
        parent::__construct($resource);
        $this->phones = $phones;
        $this->emails = $emails;
    }

    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getId(),
            'company_id' => $this->resource->getCompany()->getId(),
            'branch_id' => $this->resource->getBranch()->getId(),
            'document_type' => [
                'id' => $this->resource->getDocumentType()->getId(),
                'name' => $this->resource->getDocumentType()->getDescription(),
                'abbreviation' => $this->resource->getDocumentType()->getAbbreviation(),
            ],
            'serie' => $this->resource->getSerie(),
            'document_number' => $this->resource->getDocumentNumber(),
            'customer' => [
                'id' => $this->resource->getCustomer()->getId(),
                'name' => $this->resource->getCustomer()->getCompanyName() ??
                    trim($this->resource->getCustomer()->getName() . ' ' .
                        $this->resource->getCustomer()->getLastname() . ' ' .
                        $this->resource->getCustomer()->getSecondLastname()),
                'phones' => $this->phones ? CustomerPhoneResource::collection($this->phones) : [],
                'emails' => $this->emails ? CustomerEmailResource::collection($this->emails) : [],
            ],
            'date' => Carbon::parse($this->resource->getDate())->format('d/m/Y'),
        ];
    }
}
