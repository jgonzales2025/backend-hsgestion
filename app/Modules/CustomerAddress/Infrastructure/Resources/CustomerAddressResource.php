<?php

namespace App\Modules\CustomerAddress\Infrastructure\Resources;

use App\Modules\Ubigeo\Departments\Infrastructure\Resources\DepartmentResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerAddressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->getId(),
            'customer_id' => $this->resource->getCustomerId(),
            'address' => $this->resource->getAddress(),
            'department' => new DepartmentResource($this->resource->getDepartment()),
            'province' => new DepartmentResource($this->resource->getProvince()),
            'district' => new DepartmentResource($this->resource->getDistrict()),
            'status' => $this->resource->getStatus(),
        ];
    }
}
