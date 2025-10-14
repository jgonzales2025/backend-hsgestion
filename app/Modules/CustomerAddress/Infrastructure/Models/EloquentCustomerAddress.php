<?php

namespace App\Modules\CustomerAddress\Infrastructure\Models;

use App\Modules\Customer\Infrastructure\Models\EloquentCustomer;
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
        'status'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(EloquentCustomer::class, 'customer_id');
    }
}
