<?php

namespace App\Modules\CustomerAddress\Application\UseCases;

use App\Modules\CustomerAddress\Application\DTOs\CustomerAddressDTO;
use App\Modules\CustomerAddress\Domain\Entities\CustomerAddress;
use App\Modules\CustomerAddress\Domain\Interfaces\CustomerAddressRepositoryInterface;
use App\Modules\Ubigeo\Departments\Application\UseCases\FindByIdDepartmentUseCase;
use App\Modules\Ubigeo\Departments\Domain\Interfaces\DepartmentRepositoryInterface;
use App\Modules\Ubigeo\Districts\Application\UseCases\FindByIdDistrictUseCase;
use App\Modules\Ubigeo\Districts\Domain\Interfaces\DistrictRepositoryInterface;
use App\Modules\Ubigeo\Provinces\Application\UseCases\FindByIdProvinceUseCase;
use App\Modules\Ubigeo\Provinces\Domain\Interfaces\ProvinceRepositoryInterface;

readonly class CreateCustomerAddressUseCase
{
    public function __construct(
        private readonly CustomerAddressRepositoryInterface $customerAddressRepository,
        private readonly DepartmentRepositoryInterface $departmentRepository,
        private readonly ProvinceRepositoryInterface $provinceRepository,
        private readonly DistrictRepositoryInterface $districtRepository
    ){}

    public function execute(CustomerAddressDTO $customerAddressDTO): ?CustomerAddress
    {
        $departmentUseCase = new FindByIdDepartmentUseCase($this->departmentRepository);
        $department = $departmentUseCase->execute($customerAddressDTO->department_id);

        $provinceUseCase = new FindByIdProvinceUseCase($this->provinceRepository);
        $province = $provinceUseCase->execute($customerAddressDTO->department_id,$customerAddressDTO->province_id);

        $districtUseCase = new FindByIdDistrictUseCase($this->districtRepository);
        $district = $districtUseCase->execute($customerAddressDTO->department_id, $customerAddressDTO->province_id, $customerAddressDTO->district_id);

        $customerAddress = new CustomerAddress(
            id: 0,
            customerId: $customerAddressDTO->customer_id,
            address: $customerAddressDTO->address,
            department: $department,
            province: $province,
            district: $district,
            status: $customerAddressDTO->status,
            st_principal: $customerAddressDTO->st_principal
        );

        return $this->customerAddressRepository->save($customerAddress);
    }
}
