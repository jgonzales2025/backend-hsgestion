<?php

namespace App\Modules\PaymentMethod\Infrastructure\Model;

use App\Modules\PaymentMethod\Domain\Entities\PaymentMethod;
use Illuminate\Database\Eloquent\Model;

class EloquentPaymentMethod extends Model
{
    protected $table = 'payment_methods';
    protected $fillable = ['description', 'status'];

    protected $hidden = ['created_at', 'updated_at'];

    public function toDomain(EloquentPaymentMethod $eloquentPaymentMethod): ?PaymentMethod
    {
        return new PaymentMethod(
            id: $eloquentPaymentMethod->id,
            description: $eloquentPaymentMethod->description,
            status: $eloquentPaymentMethod->status
        );
    }
}
