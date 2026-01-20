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
    private ?int $st_dispatch_notes;
    private ?int $st_petty_cash;
    private ?int $st_warranties;
    private array $phones;

    public function __construct(?int $id, ?int $cia_id,
    string $name,string $address,string $email,string $start_date,
    string $serie,?int $status, array $phones = [], ?int $st_sales = null, ?int $st_dispatch_notes = null, ?int $st_petty_cash = null, ?int $st_warranties = null){
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
       $this->st_dispatch_notes = $st_dispatch_notes;
       $this->st_petty_cash = $st_petty_cash;
       $this->st_warranties = $st_warranties;
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
    public function getSt_dispatch_notes():?int{
        return $this->st_dispatch_notes;
    }
    public function getSt_petty_cash():?int{
        return $this->st_petty_cash;
    }
    public function getSt_Warranties():?int{
        return $this->st_warranties;
    }
        public function getPhones(): array
    {
        return $this->phones;
    }
}