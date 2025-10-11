<?php

namespace App\Modules\CustomerEmail\Infrastructure\Models;

use App\Modules\Customer\Infrastructure\Models\EloquentCustomer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentCustomerEmail extends Model
{
    protected $table = 'customer_emails';

    protected $fillable = ['email', 'customer_id', 'status'];

    protected $hidden = ['created_at', 'updated_at'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(EloquentCustomer::class, 'customer_id');
    }
}
