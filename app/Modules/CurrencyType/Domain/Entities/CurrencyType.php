<?php

namespace App\Modules\CurrencyType\Domain\Entities;

class CurrencyType{
    private int $id;
    private string $name;
    private int $status;

    public function __construct(int $id, string $name, int $status){
       $this->id = $id;
       $this->name = $name;
       $this->status = $status;
    }

    public function getId():int{
       return $this->id;
    }
       public function getName():string{
       return $this->name;
    }
       public function getStatus():int{
       return $this->status;
    }

}