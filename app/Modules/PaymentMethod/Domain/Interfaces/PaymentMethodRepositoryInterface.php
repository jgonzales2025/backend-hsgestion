<?php

namespace App\Modules\PaymentMethod\Domain\Interfaces;

use App\Modules\PaymentMethod\Domain\Entities\PaymentMethod;

interface PaymentMethodRepositoryInterface
{
    public function findAllPaymentMethods(): array;
    public function findById(int $id): ?PaymentMethod;
    // public function save(PaymentMethod $paymentMethod): ?PaymentMethod;
    // public function update(PaymentMethod $paymentMethod): void;
    // public function delete(PaymentMethod $paymentMethod): void;
}
