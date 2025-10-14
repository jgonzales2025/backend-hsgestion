<?php

namespace App\Modules\CurrencyType\Infrastructure\Persistence;

use App\Modules\CurrencyType\Domain\Entities\CurrencyType;
use App\Modules\CurrencyType\Domain\Interfaces\CurrencyTypeRepositoryInterface;
use App\Modules\CurrencyType\Infrastructure\Models\EloquentCurrencyType;

class EloquentCurrencyTypeRepository implements CurrencyTypeRepositoryInterface{
         public function findAllCurrencyTy():array{
              $currencyType = EloquentCurrencyType::all();
                 if ($currencyType->isEmpty()) {
            return [];
        }
              return $currencyType->map(fn( $currencyType)
              => new CurrencyType(
                    id:$currencyType->id,
                    name:$currencyType->name,
                    status:$currencyType->status
                ))->toArray();
         }
}