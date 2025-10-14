<?php

namespace App\Modules\PaymentMethod\Infrastructure\Persistence;

use App\Modules\PaymentMethod\Domain\Entities\PaymentMethod;
use App\Modules\PaymentMethod\Domain\Interfaces\PaymentMethodRepositoryInterface;
use App\Modules\PaymentMethod\Infrastructure\Model\EloquentPaymentMethod;

class EloquentPaymentMethodRepository implements PaymentMethodRepositoryInterface
{

    public function findAllPaymentMethods(): array
    {
        $payment_methods = EloquentPaymentMethod::all();
        if ($payment_methods->isEmpty()) {
            return [];
        }
        return $payment_methods->map(function ($payment_method) {
            return new PaymentMethod(
                id: $payment_method->id,
                description: $payment_method->description,
                status: $payment_method->status
            );
        })->toArray();
    }
}

