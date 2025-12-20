<?php

namespace App\Modules\CustomerDocumentType\Infrastructure\Models;

use App\Modules\Customer\Infrastructure\Models\EloquentCustomer;
use App\Modules\CustomerDocumentType\Domain\Entities\CustomerDocumentType;
use App\Modules\Driver\Infrastructure\Models\EloquentDriver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EloquentCustomerDocumentType extends Model
{
    protected $table = 'customer_document_types';

    protected $fillable = ['cod_sunat', 'description', 'abbreviation', 'st_driver', 'status'];

    protected $hidden = ['created_at', 'updated_at'];

    public function drivers(): HasMany
    {
        return $this->hasMany(EloquentDriver::class, 'customer_document_type_id');
    }

    public function customers(): HasMany
    {
        return $this->hasMany(EloquentCustomer::class, 'customer_document_type_id');
    }

    public function toDomain(EloquentCustomerDocumentType $eloquentCustomerDocumentType): CustomerDocumentType
    {
        return new CustomerDocumentType(
            id: $eloquentCustomerDocumentType->id,
            cod_sunat: $eloquentCustomerDocumentType->cod_sunat,
            description: $eloquentCustomerDocumentType->description,
            abbreviation: $eloquentCustomerDocumentType->abbreviation,
            st_driver: $eloquentCustomerDocumentType->st_driver,
            status: $eloquentCustomerDocumentType->status,
        );
    }
}
