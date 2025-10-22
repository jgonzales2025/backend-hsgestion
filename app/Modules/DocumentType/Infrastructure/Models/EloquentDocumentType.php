<?php

namespace App\Modules\DocumentType\Infrastructure\Models;

use App\Modules\DocumentType\Domain\Entities\DocumentType;
use Illuminate\Database\Eloquent\Model;

class EloquentDocumentType extends Model
{
    protected $table = 'document_types';

    protected $fillable = ['cod_sunat', 'description', 'abbreviation', 'st_sales', 'st_purchases', 'st_collections', 'status'];

    protected $hidden = ['created_at', 'updated_at'];

    public function toDomain(EloquentDocumentType $eloquentDocumentType): ?DocumentType
    {
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
    }
}
