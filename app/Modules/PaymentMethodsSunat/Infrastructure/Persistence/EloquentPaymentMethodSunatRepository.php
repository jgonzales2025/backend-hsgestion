<?php

namespace App\Modules\PaymentMethodsSunat\Infrastructure\Persistence;

use App\Modules\PaymentMethodsSunat\Domain\Entities\PaymentMethodSunat;
use App\Modules\PaymentMethodsSunat\Domain\Interface\PaymentMethodSunatRepositoryInterface;
use App\Modules\PaymentMethodsSunat\Infrastructure\Models\EloquentPaymentMethodSunat;

class EloquentPaymentMethodSunatRepository implements PaymentMethodSunatRepositoryInterface
{
    public function findAll(): array
    {
        return EloquentPaymentMethodSunat::all()->map(function ($item) {
            return new PaymentMethodSunat(
                cod: $item->cod,
                des: $item->des
            );
        })->toArray();
    }

    public function findById(int $cod): ?PaymentMethodSunat
    {
        $item = EloquentPaymentMethodSunat::find($cod);

        if (!$item) {
            return null;
        }

        return new PaymentMethodSunat(
            cod: $item->cod,
            des: $item->des
        );
    }

    public function create(PaymentMethodSunat $paymentMethodSunat): ?PaymentMethodSunat
    {
        $item = EloquentPaymentMethodSunat::create([
            'cod' => $paymentMethodSunat->getCod(),
            'des' => $paymentMethodSunat->getDes()
        ]);

        return new PaymentMethodSunat(
            cod: $item->cod,
            des: $item->des
        );
    }

    public function update(int $cod, PaymentMethodSunat $paymentMethodSunat): ?PaymentMethodSunat
    {
        $item = EloquentPaymentMethodSunat::find($cod);

        if (!$item) {
            return null;
        }

        $item->update([
            'cod' => $paymentMethodSunat->getCod(),
            'des' => $paymentMethodSunat->getDes()
        ]);

        return new PaymentMethodSunat(
            cod: $item->cod,
            des: $item->des
        );
    }
}
