<?php

namespace App\Modules\TransportCompany\Domain\Entities;

class TransportCompany
{
    private int $id;
    private string $ruc;
    private string $company_name;
    private string $address;
    private string $nro_reg_mtc;
    private int $status;

    public function __construct(int $id, string $ruc, string $company_name, string $address, string $nro_reg_mtc, int $status)
    {
        $this->id = $id;
        $this->ruc = $ruc;
        $this->company_name = $company_name;
        $this->address = $address;
        $this->nro_reg_mtc = $nro_reg_mtc;
        $this->status = $status;
    }

    public function getId(): int{return $this->id;}
    public function getRuc(): string{return $this->ruc;}
    public function getCompanyName(): string{return $this->company_name;}
    public function getAddress(): string{return $this->address;}
    public function getNroRegMtc(): string{return $this->nro_reg_mtc;}
    public function getStatus(): int{return $this->status;}
}
