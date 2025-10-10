<?php

namespace App\Modules\CustomerType\Infrastructure\Models;

use App\Modules\Customer\Infrastructure\Models\EloquentCustomer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EloquentCustomerType extends Model
{
    protected $table = 'customer_types';

    protected $fillable = ['description', 'status'];

    protected $hidden = ['created_at', 'updated_at'];

    public function customers(): HasMany
    {
        return $this->hasMany(EloquentCustomer::class, 'customer_type_id');
    }
}
