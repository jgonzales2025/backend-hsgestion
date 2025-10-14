<?php

namespace App\Modules\DocumentType\Infrastructure\Persistence;

use App\Modules\DocumentType\Domain\Entities\DocumentType;
use App\Modules\DocumentType\Domain\Interfaces\DocumentTypeRepositoryInterface;
use App\Modules\DocumentType\Infrastructure\Models\EloquentDocumentType;

class EloquentDocumentTypeRepository implements DocumentTypeRepositoryInterface
{

    public function findAll(): array
    {
        $eloquentDocumentType = EloquentDocumentType::all();

        if ($eloquentDocumentType->isEmpty()){
            return [];
        }

        return $eloquentDocumentType->map(function ($eloquentDocumentType){
            return new DocumentType(
                id: $eloquentDocumentType->id,
                cod_sunat: $eloquentDocumentType->cod_sunat,
                description: $eloquentDocumentType->description,
                abbreviation: $eloquentDocumentType->abbreviation,
                st_sales: $eloquentDocumentType->st_sales,
                st_purchases: $eloquentDocumentType->st_purchases,
                st_collections: $eloquentDocumentType->st_collections,
                status: $eloquentDocumentType->status
            );
        })->toArray();
    }
}
