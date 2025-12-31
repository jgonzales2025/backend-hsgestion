<?php

namespace App\Modules\Branch\Domain\Entities;


class Branch{
    private ?int $id;
    private ?int $cia_id;
    private string $name;
    private string $address;
    private string $email;
    private string $start_date;
    private string $serie;
    private ?int $status;
    private ?int $st_sales;
    private ?int $st_entry_guide;
    private ?int $st_petty_cash;
    private array $phones;

    public function __construct(?int $id, ?int $cia_id,
    string $name,string $address,string $email,string $start_date,
    string $serie,?int $status, array $phones = [], ?int $st_sales = null, ?int $st_entry_guide = null, ?int $st_petty_cash = null){
       $this->id = $id;
       $this->cia_id = $cia_id;
       $this->name = $name;
       $this->address = $address;
       $this->email = $email;
       $this->start_date = $start_date;
       $this->serie = $serie;
       $this->status = $status;
       $this->phones = $phones;
       $this->st_sales = $st_sales;
       $this->st_entry_guide = $st_entry_guide;
       $this->st_petty_cash = $st_petty_cash;
    }

    public function getId(): ?int {
        return $this->id;
    }
    public function getCia_id(): ?int {
        return $this->cia_id;
    }
    public function getName():string{
         return $this->name;
    }
    public function getAddress():string{
        return $this->address;
    }
    public function getEmail():string{
        return $this->email;
    }
    public function getStart_date(): string {
        return $this->start_date;
    }
    public function getSerie():string{
       return $this->serie;
    }
    public function getStatus():?int{
        return $this->status;
    }

    public function getSt_sales():?int{
        return $this->st_sales;
    }
    public function getSt_entry_guide():?int{
        return $this->st_entry_guide;
    }
    public function getSt_petty_cash():?int{
        return $this->st_petty_cash;
    }
        public function getPhones(): array
    {
        return $this->phones;
    }
}