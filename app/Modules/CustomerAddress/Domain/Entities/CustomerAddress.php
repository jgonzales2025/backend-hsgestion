<?php

namespace App\Modules\CustomerAddress\Domain\Entities;

use App\Modules\Ubigeo\Departments\Domain\Entities\Department;
use App\Modules\Ubigeo\Districts\Domain\Entities\District;
use App\Modules\Ubigeo\Provinces\Domain\Entities\Province;

class CustomerAddress
{
    private int $id;
    private int $customerId;
    private string $address;
    private Department $department;
    private Province $province;
    private District $district;
    private int $status;

    public function __construct(int $id, int $customerId, string $address, Department $department, Province $province, District $district, int $status)
    {
        $this->id = $id;
        $this->customerId = $customerId;
        $this->address = $address;
        $this->department = $department;
        $this->province = $province;
        $this->district = $district;
        $this->status = $status;
    }

    public function getId(): int { return $this->id; }
    public function getCustomerId(): int { return $this->customerId; }
    public function getAddress(): string { return $this->address; }
    public function getDepartment(): Department { return $this->department; }
    public function getProvince(): Province { return $this->province; }
    public function getDistrict(): District { return $this->district; }

    public function getDepartmentId(): int { return $this->department->getCoddep(); }
    public function getProvinceId(): int { return $this->province->getCodpro(); }
    public function getDistrictId(): int { return $this->district->getCoddis(); }
    public function getStatus(): int { return $this->status; }
}
