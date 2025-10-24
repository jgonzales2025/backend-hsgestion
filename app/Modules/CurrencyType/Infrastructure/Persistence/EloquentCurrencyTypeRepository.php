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
                    commercial_symbol: $currencyType->commercial_symbol,
                    sunat_symbol: $currencyType->sunat_symbol,
                    status:$currencyType->status
                ))->toArray();
         }

         public function findById(int $id): ?CurrencyType
         {
             $eloquentCurrencyType = EloquentCurrencyType::find($id);

             if (!$eloquentCurrencyType) {
                 return null;
             }

             return new CurrencyType(
                 id: $eloquentCurrencyType->id,
                 name: $eloquentCurrencyType->name,
                 commercial_symbol: $eloquentCurrencyType->commercial_symbol,
                 sunat_symbol: $eloquentCurrencyType->sunat_symbol,
                 status: $eloquentCurrencyType->status
             );
         }
}
