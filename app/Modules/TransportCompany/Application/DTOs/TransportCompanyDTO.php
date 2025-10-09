<?php

namespace App\Modules\TransportCompany\Application\DTOs;

class TransportCompanyDTO
{
    public $ruc;
    public $company_name;
    public $address;
    public $nro_reg_mtc;
    public $status;

    public function __construct(array $data)
    {
        $this->ruc = $data['ruc'];
        $this->company_name = $data['company_name'];
        $this->address = $data['address'];
        $this->nro_reg_mtc = $data['nro_reg_mtc'];
        $this->status = $data['status'];
    }
}
