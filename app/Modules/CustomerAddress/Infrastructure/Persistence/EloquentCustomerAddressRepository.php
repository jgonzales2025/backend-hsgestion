<?php

namespace App\Modules\CustomerAddress\Infrastructure\Persistence;

use App\Modules\CustomerAddress\Domain\Entities\CustomerAddress;
use App\Modules\CustomerAddress\Domain\Interfaces\CustomerAddressRepositoryInterface;
use App\Modules\CustomerAddress\Infrastructure\Models\EloquentCustomerAddress;
use App\Modules\Ubigeo\Departments\Domain\Interfaces\DepartmentRepositoryInterface;
use App\Modules\Ubigeo\Departments\Infrastructure\Models\EloquentDepartment;
use App\Modules\Ubigeo\Districts\Domain\Interfaces\DistrictRepositoryInterface;
use App\Modules\Ubigeo\Districts\Infrastructure\Models\EloquentDistrict;
use App\Modules\Ubigeo\Provinces\Domain\Interfaces\ProvinceRepositoryInterface;
use App\Modules\Ubigeo\Provinces\Infrastructure\Models\EloquentProvince;

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

    public function findByCustomerId(int $customerId): array
    {
        $addresses = EloquentCustomerAddress::where('customer_id', $customerId)->get();

        return $addresses->map(function ($address) {
            $department = EloquentDepartment::where('coddep', $address->department_id)->first();
            $province = EloquentProvince::where('coddep', $department->coddep)->where('codpro', $address->province_id)->first();
            $district = EloquentDistrict::where('coddep', $department->coddep)->where('codpro', $province->codpro)->where('coddis', $address->district_id)->first();

            return new CustomerAddress(
                id: $address->id,
                customerId: $address->customer_id,
                address: $address->address,
                department: $department->toDomain($department),
                province: $province->toDomain($province),
                district: $district->toDomain($district),
                status: $address->status,
            );
        })->toArray();
    }

    public function update(CustomerAddress $customerAddress, int $customerId): ?CustomerAddress
    {
        $addresses = EloquentCustomerAddress::where('customer_id', $customerId)->get();

        foreach ($addresses as $address) {
            $address->delete();
        }

        return $this->save($customerAddress);
    }
}
