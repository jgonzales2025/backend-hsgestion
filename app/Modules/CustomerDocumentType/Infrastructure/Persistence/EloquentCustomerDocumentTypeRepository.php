<?php

namespace App\Modules\CustomerDocumentType\Infrastructure\Persistence;

use App\Modules\CustomerDocumentType\Domain\Entities\CustomerDocumentType;
use App\Modules\CustomerDocumentType\Domain\Interfaces\CustomerDocumentTypeRepositoryInterface;
use App\Modules\CustomerDocumentType\Infrastructure\Models\EloquentCustomerDocumentType;

class EloquentCustomerDocumentTypeRepository implements CustomerDocumentTypeRepositoryInterface
{

    public function findAllForDrivers(): array
    {
        $eloquentCustomerDocumentTypes = EloquentCustomerDocumentType::where('st_driver', 1)->orderBy('created_at', 'desc')->get();

        return $eloquentCustomerDocumentTypes->map(function ($eloquentCustomerDocumentType) {
            return new CustomerDocumentType(
                id: $eloquentCustomerDocumentType->id,
                cod_sunat: $eloquentCustomerDocumentType->cod_sunat,
                description: $eloquentCustomerDocumentType->description,
                abbreviation: $eloquentCustomerDocumentType->abbreviation,
                st_driver: $eloquentCustomerDocumentType->st_driver,
                status: $eloquentCustomerDocumentType->status,
            );
        })->toArray();   
    }
      public function findAllDrivers(): array
    {
        $eloquentCustomerDocumentTypes = EloquentCustomerDocumentType::all();

        return $eloquentCustomerDocumentTypes->map(function ($eloquentCustomerDocumentType) {
            return new CustomerDocumentType(
                id: $eloquentCustomerDocumentType->id,
                cod_sunat: $eloquentCustomerDocumentType->cod_sunat,
                description: $eloquentCustomerDocumentType->description,
                abbreviation: $eloquentCustomerDocumentType->abbreviation,
                st_driver: $eloquentCustomerDocumentType->st_driver,
                status: $eloquentCustomerDocumentType->status,
            );
        })->toArray();

        
    }
}
