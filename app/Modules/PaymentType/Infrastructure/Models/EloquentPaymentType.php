<?php

namespace App\Modules\PaymentType\Infrastructure\Models;

use App\Modules\PaymentType\Domain\Entities\PaymentType;
use Illuminate\Database\Eloquent\Model;

class EloquentPaymentType extends Model
{
     protected $table = 'payment_types';
    protected $fillable = ['name', 'status'];

    protected $hidden = ['created_at', 'updated_at'];

    public function toDomain(EloquentPaymentType $eloquentPaymentType): PaymentType
    {
        return new PaymentType(
            id: $eloquentPaymentType->id,
            name: $eloquentPaymentType->name,
            status: $eloquentPaymentType->status
        );
    }
}
