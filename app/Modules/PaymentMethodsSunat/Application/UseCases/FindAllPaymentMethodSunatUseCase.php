<?php

namespace App\Modules\PaymentMethodsSunat\Application\UseCases;

use App\Modules\PaymentMethodsSunat\Domain\Interface\PaymentMethodSunatRepositoryInterface;

class FindAllPaymentMethodSunatUseCase
{
    public function __construct(
        private readonly PaymentMethodSunatRepositoryInterface $repository
    ) {}

    public function execute(): array
    {
        return $this->repository->findAll();
    }
}
