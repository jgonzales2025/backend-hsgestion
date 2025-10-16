<?php

namespace App\Modules\CustomerPortfolio\Infrastructure\Models;

use App\Modules\Customer\Infrastructure\Models\EloquentCustomer;
use App\Modules\User\Infrastructure\Model\EloquentUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EloquentCustomerPortfolio extends Model
{
    protected $table = 'customer_portfolios';

    protected $fillable = ['customer_id', 'user_id', 'assigned_at'];

    protected $hidden = ['created_at', 'updated_at'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(EloquentCustomer::class, 'customer_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(EloquentUser::class, 'user_id');
    }
}
