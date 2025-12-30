<?php

namespace App\Modules\Company\Infrastructure\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getId(),
            'ruc' => $this->resource->getRuc(),
            'company_name' => $this->resource->getCompanyName(),
            'address' => $this->resource->getAddress(),
            'ubigeo' => $this->resource->getUbigeo(),
            'start_date' => $this->resource->getStartDate(),
            'default_currency_type_id' => $this->resource->getDefaultCurrencyType()->getId(),
            'min_profit' => $this->resource->getMinProfit(),
            'max_profit' => $this->resource->getMaxProfit(),
            'status' => ($this->resource->getStatus()) == 1 ? 'Activo' : 'Inactivo',
            'detrac_cta_banco' => $this->resource->getDetracCtaBanco(),
        ];
    }
}
