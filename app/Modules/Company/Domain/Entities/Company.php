<?php

namespace App\Modules\Company\Domain\Entities;

class Company {
    private int $id;
    private string $ruc;
    private string $company_name;
    private string $address;
    private string $start_date;
    private string $ubigeo;
    private int $status;
    private ?string $password_item;

    /**
     * @param int $id
     * @param string $ruc
     * @param string $company_name
     * @param string $address
     * @param string $start_date
     * @param string $ubigeo
     * @param int $status
     * @param ?string $password_item
     */

    public function __construct(int $id, string $ruc,
    string $company_name, string $address, string $start_date,
     string $ubigeo, int $status, ?string $password_item = null
    ){
    $this->id = $id;
    $this->ruc = $ruc;
    $this->company_name = $company_name;
    $this->address = $address;
    $this->start_date = $start_date;
    $this->ubigeo = $ubigeo;
    $this->status = $status;
    $this->password_item = $password_item;
    }
        public function getId(): int
    {
        return $this->id;
    }

    public function getRuc(): string
    {
        return $this->ruc;
    }

    public function getCompanyName(): string
    {
        return $this->company_name;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getStartDate(): string
    {
        return $this->start_date;
    }
      public function getUbigeo(): string
    {
        return $this->ubigeo;
    }
      public function getStatus(): int
    {
        return $this->status;
    }

    public function getPasswordItem(): ?string
    {
        return $this->password_item;
    }
}
