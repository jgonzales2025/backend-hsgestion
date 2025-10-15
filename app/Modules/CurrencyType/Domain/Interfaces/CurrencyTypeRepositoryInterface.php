<?php

namespace App\Modules\CurrencyType\Domain\Interfaces;

use App\Modules\CurrencyType\Domain\Entities\CurrencyType;

interface CurrencyTypeRepositoryInterface{

    public function findAllCurrencyTy():array;
    public function findById(int $id): ?CurrencyType;
}
