<?php

namespace App\Modules\PaymentMethodsSunat\Application\UseCases;

use App\Modules\PaymentMethodsSunat\Domain\Entities\PaymentMethodSunat;
use App\Modules\PaymentMethodsSunat\Domain\Interface\PaymentMethodSunatRepositoryInterface;

class FindByIdPaymentMethodSunatUseCase
{
    public function __construct(
        private readonly PaymentMethodSunatRepositoryInterface $repository
    ) {}

    public function execute(int $cod): ?PaymentMethodSunat
    {
        return $this->repository->findById($cod);
    }
}
