<?php

namespace App\Modules\PaymentMethodsSunat\Domain\Interface;

use App\Modules\PaymentMethodsSunat\Domain\Entities\PaymentMethodSunat;

interface PaymentMethodSunatRepositoryInterface
{
    public function findAll(): array;
    public function findById(int $cod): ?PaymentMethodSunat;
    public function create(PaymentMethodSunat $paymentMethodSunat): ?PaymentMethodSunat;
    public function update(int $cod, PaymentMethodSunat $paymentMethodSunat): ?PaymentMethodSunat;
}