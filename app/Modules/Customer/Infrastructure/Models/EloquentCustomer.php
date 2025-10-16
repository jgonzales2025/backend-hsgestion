<?php

namespace App\Modules\Customer\Infrastructure\Models;

use App\Modules\Customer\Domain\Entities\Customer;
use App\Modules\CustomerAddress\Application\UseCases\FindByIdCustomerAddressUseCase;
use App\Modules\CustomerAddress\Domain\Interfaces\CustomerAddressRepositoryInterface;
use App\Modules\CustomerAddress\Infrastructure\Models\EloquentCustomerAddress;
use App\Modules\CustomerDocumentType\Infrastructure\Models\EloquentCustomerDocumentType;
use App\Modules\CustomerEmail\Application\UseCases\FindByCustomerIdEmailUseCase;
use App\Modules\CustomerEmail\Domain\Interfaces\CustomerEmailRepositoryInterface;
use App\Modules\CustomerEmail\Infrastructure\Models\EloquentCustomerEmail;
use App\Modules\CustomerPhone\Application\UseCases\FindByCustomerIdPhoneUseCase;
use App\Modules\CustomerPhone\Domain\Interfaces\CustomerPhoneRepositoryInterface;
use App\Modules\CustomerPhone\Infrastructure\Models\EloquentCustomerPhone;
use App\Modules\CustomerType\Infrastructure\Models\EloquentCustomerType;
use App\Modules\RecordType\Infrastructure\Models\EloquentRecordType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EloquentCustomer extends Model
{
    protected $table = 'customers';

    protected $fillable = [
        'record_type_id',
        'customer_document_type_id',
        'document_number',
        'company_name',
        'name',
        'lastname',
        'second_lastname',
        'customer_type_id',
        'fax',
        'contact',
        'is_withholding_applicable',
        'status'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function customerType(): BelongsTo
    {
        return $this->belongsTo(EloquentCustomerType::class, 'customer_type_id');
    }

    public function recordType(): BelongsTo
    {
        return $this->belongsTo(EloquentRecordType::class, 'record_type_id');
    }

    public function customerDocumentType(): BelongsTo
    {
        return $this->belongsTo(EloquentCustomerDocumentType::class, 'customer_document_type_id');
    }

    public function emails(): HasMany
    {
        return $this->hasMany(EloquentCustomerEmail::class, 'customer_id');
    }

    public function phones(): HasMany
    {
        return $this->hasMany(EloquentCustomerPhone::class, 'customer_id');
    }

    public function address(): HasMany
    {
        return $this->hasMany(EloquentCustomerAddress::class, 'customer_id');
    }

    public function toDomain(EloquentCustomer $eloquentCustomer): Customer
    {

        // Cargar las relaciones si no están cargadas
        $phoneUseCase = new FindByCustomerIdPhoneUseCase(app(CustomerPhoneRepositoryInterface::class));
        $phones = $phoneUseCase->execute($eloquentCustomer->id);

        $emailUseCase = new FindByCustomerIdEmailUseCase(app(CustomerEmailRepositoryInterface::class));
        $emails = $emailUseCase->execute($eloquentCustomer->id);

        $addressUseCase = new FindByIdCustomerAddressUseCase(app(CustomerAddressRepositoryInterface::class));
        $addresses = $addressUseCase->execute($eloquentCustomer->id);

        return new Customer(
            id: $eloquentCustomer->id,
            record_type_id: $eloquentCustomer->record_type_id,
            record_type_name: $eloquentCustomer->recordType->name,
            customer_document_type_id: $eloquentCustomer->customer_document_type_id,
            customer_document_type_name: $eloquentCustomer->customerDocumentType->description,
            customer_document_type_abbreviation: $eloquentCustomer->customerDocumentType->abbreviation,
            document_number: $eloquentCustomer->document_number,
            company_name: $eloquentCustomer->company_name,
            name: $eloquentCustomer->name,
            lastname: $eloquentCustomer->lastname,
            second_lastname: $eloquentCustomer->second_lastname,
            customer_type_id: $eloquentCustomer->customer_type_id,
            customer_type_name: $eloquentCustomer->customerType->name,
            fax: $eloquentCustomer->fax,
            contact: $eloquentCustomer->contact,
            is_withholding_applicable: $eloquentCustomer->is_withholding_applicable,
            status: $eloquentCustomer->status,
            phones: $phones,
            emails: $emails,
            addresses: $addresses
        );
    }
}
