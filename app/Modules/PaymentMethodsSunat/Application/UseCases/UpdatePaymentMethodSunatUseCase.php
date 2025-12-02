<?php

namespace App\Modules\PaymentMethodsSunat\Application\UseCases;

use App\Modules\PaymentMethodsSunat\Application\DTO\PaymentMethodSunatDTO;
use App\Modules\PaymentMethodsSunat\Domain\Entities\PaymentMethodSunat;
use App\Modules\PaymentMethodsSunat\Domain\Interface\PaymentMethodSunatRepositoryInterface;

class UpdatePaymentMethodSunatUseCase
{
    public function __construct(
        private readonly PaymentMethodSunatRepositoryInterface $repository
    ) {}

    public function execute(int $cod, PaymentMethodSunatDTO $dto): ?PaymentMethodSunat
    {
        $paymentMethodSunat = new PaymentMethodSunat(
            cod: $dto->cod,
            des: $dto->des
        );

        return $this->repository->update($cod, $paymentMethodSunat);
    }
}
