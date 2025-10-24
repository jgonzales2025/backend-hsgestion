<?php

namespace App\Modules\CurrencyType\Domain\Entities;

class CurrencyType{
    private int $id;
    private string $name;
    private string $commercial_symbol;
    private string $sunat_symbol;
    private int $status;

    public function __construct(int $id, string $name, string $commercial_symbol, string $sunat_symbol, int $status){
       $this->id = $id;
       $this->name = $name;
       $this->commercial_symbol = $commercial_symbol;
       $this->sunat_symbol = $sunat_symbol;
       $this->status = $status;
    }

    public function getId():int{
       return $this->id;
    }
       public function getName():string{
       return $this->name;
    }
    public function getCommercialSymbol():string{
       return $this->commercial_symbol;
    }
    public function getSunatSymbol():string{
       return $this->sunat_symbol;
    }
       public function getStatus():int{
       return $this->status;
    }

}
