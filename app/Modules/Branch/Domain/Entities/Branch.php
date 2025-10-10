<?php

namespace App\Modules\Branch\Domain\Entities;


class Branch{
    private int $id;
    private int $cia_id;
    private string $name;
    private string $address;
    private string $email;
    private string $start_date;
    private string $serie;
    private int $status;

        /**
     * @param int $id
     * @param int $cia_id
     * @param string $name
     * @param string $address
     * @param string $email
     * @param string $start_date
     * @param string $serie
     * @param int $status
     */
    public function __construct(int $id, int $cia_id,
    string $name,string $address,string $email,string $start_date,
    string $serie, int $status){
       $this->id = $id;
       $this->cia_id = $cia_id;
       $this->name = $name;
       $this->address = $address;
       $this->email = $email;
       $this->start_date = $start_date;
       $this->serie = $serie;
       $this->status = $status;
    }

    public function getId(): int {
        return $this->id;
    }
    public function getCia_id(): int {
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
    public function getStatus():int{
        return $this->status;
    }
}