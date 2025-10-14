<?php

namespace App\Modules\CurrencyType\Domain\Interfaces;

interface CurrencyTypeRepositoryInterface{

    public function findAllCurrencyTy():array;
}