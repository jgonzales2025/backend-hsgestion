<?php

namespace App\Modules\CustomerAddress\Application\DTOs;

class CustomerAddressDTO
{
    public int $customer_id;
    public string $address;
    public int $department_id;
    public int $province_id;
    public int $district_id;
    public int $status;

    public function __construct(array $data)
    {
        $this->customer_id = $data['customer_id'];
        $this->address = $data['address'];
        $this->department_id = $data['department_id'];
        $this->province_id = $data['province_id'];
        $this->district_id = $data['district_id'];
        $this->status = $data['status'];
    }
}
