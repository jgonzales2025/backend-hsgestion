<?php

namespace App\Modules\CustomerPhone\Infrastructure\Models;

use App\Modules\Customer\Infrastructure\Models\EloquentCustomer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentCustomerPhone extends Model
{
    protected $table = 'customer_phones';

    protected $fillable = [
        'phone',
        'customer_id',
        'status',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(EloquentCustomer::class, 'customer_id');
    }
}
