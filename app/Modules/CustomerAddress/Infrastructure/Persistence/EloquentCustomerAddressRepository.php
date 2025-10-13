<?php

namespace App\Modules\CustomerAddress\Infrastructure\Persistence;

use App\Modules\CustomerAddress\Domain\Entities\CustomerAddress;
use App\Modules\CustomerAddress\Domain\Interfaces\CustomerAddressRepositoryInterface;
use App\Modules\CustomerAddress\Infrastructure\Models\EloquentCustomerAddress;
use App\Modules\Ubigeo\Departments\Domain\Interfaces\DepartmentRepositoryInterface;
use App\Modules\Ubigeo\Districts\Domain\Interfaces\DistrictRepositoryInterface;
use App\Modules\Ubigeo\Provinces\Domain\Interfaces\ProvinceRepositoryInterface;

class EloquentCustomerAddressRepository implements CustomerAddressRepositoryInterface
{

    public function save(CustomerAddress $customerAddress): ?CustomerAddress
    {
        $eloquentCustomerAddress = EloquentCustomerAddress::create([
            'customer_id' => $customerAddress->getCustomerId(),
            'address' => $customerAddress->getAddress(),
            'department_id' => $customerAddress->getDepartmentId(),
            'province_id' => $customerAddress->getProvinceId(),
            'district_id' => $customerAddress->getDistrictId(),
            'status' => $customerAddress->getStatus(),
        ]);

        return new CustomerAddress(
            id: $eloquentCustomerAddress->id,
            customerId: $eloquentCustomerAddress->customer_id,
            address: $eloquentCustomerAddress->address,
            department: $customerAddress->getDepartment(),
            province: $customerAddress->getProvince(),
            district: $customerAddress->getDistrict(),
            status: $eloquentCustomerAddress->status
        );
    }
}
