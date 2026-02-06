<?php
namespace App\Modules\Branch\Application\DTOs;

class BranchDTO {
    public $cia_id;
    public $name;
    public $address;
    public $ubigeo;
    public $email;
    public $start_date;
    public $serie;
    public $status;

     // 🔹 Nuevo campo opcional
    public $phones;

    public function __construct(array $data)
    {
        $departmentId = strlen($data['department_id']) == 2 ? $data['department_id'] : '0' . $data['department_id'];
        $provinceId = strlen($data['province_id']) == 2 ? $data['province_id'] : '0' . $data['province_id'];
        $districtId = strlen($data['district_id']) == 2 ? $data['district_id'] : '0' . $data['district_id'];
        
        $this->cia_id = $data['cia_id'] ?? null;
        $this->name = $data['name'] ?? '';
        $this->address = $data['address'] ?? null;
        $this->ubigeo = $departmentId . $provinceId . $districtId;
        $this->email = $data['email'] ?? null;
        $this->start_date = $data['start_date'] ?? null;
        $this->serie = $data['serie'] ?? null;
        $this->status = $data['status'] ?? null;

        $this->phones = $data['phones'] ?? [];
    }
}
