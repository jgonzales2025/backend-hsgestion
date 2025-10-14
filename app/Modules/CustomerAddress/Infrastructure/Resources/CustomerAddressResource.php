<?php

namespace App\Modules\CustomerAddress\Infrastructure\Resources;

use App\Modules\Ubigeo\Departments\Infrastructure\Resources\DepartmentResource;
use App\Modules\Ubigeo\Districts\Domain\Entities\District;
use App\Modules\Ubigeo\Districts\Infrastructure\Resource\DistrictResource;
use App\Modules\Ubigeo\Provinces\Infrastructure\Resources\ProvinceResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerAddressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->getId(),
            'address' => $this->resource->getAddress(),
            'department' => new DepartmentResource($this->resource->getDepartment()),
            'province' => new ProvinceResource($this->resource->getProvince()),
            'district' => new DistrictResource($this->resource->getDistrict()),
            'status' => ($this->resource->getStatus()) == 1 ? 'Activo' : 'Inactivo',
        ];
    }
}
