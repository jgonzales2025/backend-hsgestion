<?php

namespace App\Modules\PaymentMethod\Application\UseCases;

use App\Modules\PaymentMethod\Domain\Entities\PaymentMethod;
use App\Modules\PaymentMethod\Domain\Interfaces\PaymentMethodRepositoryInterface;

readonly class FindByIdPaymentMethodUseCase
{
    public function __construct(private readonly PaymentMethodRepositoryInterface $paymentMethodRepository){}

    public function execute(int $id): ?PaymentMethod
    {
        return $this->paymentMethodRepository->findById($id);
    }
}
