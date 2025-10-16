<?php

namespace App\Modules\CustomerPortfolio\Infrastructure\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerPortfolioResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $customer = $this->resource->getCustomer();
        $isCompany = $customer->getCustomerDocumentTypeId() == 2;

        return [
            'id' => $this->resource->getId(),
            'customer' => [
                'id' => $customer->getId(),
                'document_type' => $customer->getCustomerDocumentTypeAbbreviation(),
                ...($isCompany ? [
                    'document_number' => $customer->getDocumentNumber(),
                    'business_name' => $customer->getCompanyName(),
                ] : [
                    'document_number' => $customer->getDocumentNumber(),
                    'name' => $customer->getName(),
                ]),
                'addresses' => $this->formatAddresses($customer->getAddresses()),
                'phones' => $this->formatPhones($customer->getPhones()),
            ],
            'user' => [
                'id' => $this->resource->getUser()->getId(),
                'firstname' => $this->resource->getUser()->getFirstName(),
                'lastname' => $this->resource->getUser()->getLastName()
            ],
            'created_at' => $this->resource->getCreatedAt(),
        ];
    }

    private function formatAddresses(?array $addresses): array
    {
        if (!$addresses) {
            return [];
        }

        return array_map(function ($address) {
            return [
                'id' => $address->getId(),
                'address' => $address->getAddress(),
                'department' => [
                    'id' => $address->getDepartment()->getCoddep(),
                    'name' => $address->getDepartment()->getNomDep()
                ],
                'province' => [
                    'id' => $address->getProvince()->getCodpro(),
                    'name' => $address->getProvince()->getNomPro()
                ],
                'district' => [
                    'id' => $address->getDistrict()->getCoddis(),
                    'name' => $address->getDistrict()->getNomDis()
                ],
                'status' => $address->getStatus()
            ];
        }, $addresses);
    }

    private function formatPhones(?array $phones): array
    {
        if (!$phones) {
            return [];
        }

        return array_map(function ($phone) {
            return [
                'id' => $phone->getId(),
                'phone' => $phone->getPhone(),
                'status' => $phone->getStatus()
            ];
        }, $phones);
    }
}
