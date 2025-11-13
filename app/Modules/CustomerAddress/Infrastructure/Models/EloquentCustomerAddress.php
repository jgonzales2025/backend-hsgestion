<?php

namespace App\Modules\CustomerAddress\Infrastructure\Models;

use App\Modules\Customer\Infrastructure\Models\EloquentCustomer;
use App\Modules\CustomerAddress\Domain\Entities\CustomerAddress;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentCustomerAddress extends Model
{
    protected $table = 'customer_addresses';

    protected $fillable = [
        'customer_id',
        'address',
        'department_id',
        'province_id',
        'district_id',
        'status',
        'st_principal'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(EloquentCustomer::class, 'customer_id');
    }
    public function toDomain(EloquentCustomerAddress $eloquentCustomerAddress): ?CustomerAddress
    {
        return new CustomerAddress(
          id : $eloquentCustomerAddress->id,
        customerId : $eloquentCustomerAddress->customerId,
        address : $eloquentCustomerAddress->address,
        department : $eloquentCustomerAddress->department,
        province : $eloquentCustomerAddress->province,
        district : $eloquentCustomerAddress->district,
        status : $eloquentCustomerAddress->status,
        st_principal : $eloquentCustomerAddress->st_principal
        );
    }
}
