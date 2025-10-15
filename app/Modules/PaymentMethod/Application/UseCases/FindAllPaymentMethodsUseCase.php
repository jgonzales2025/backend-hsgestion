<?php

namespace App\Modules\PaymentMethod\Application\UseCases;

use App\Modules\PaymentMethod\Domain\Interfaces\PaymentMethodRepositoryInterface;

class FindAllPaymentMethodsUseCase
{
    private PaymentMethodRepositoryInterface $paymentMethodRepository;

    public function __construct(PaymentMethodRepositoryInterface $paymentMethodRepository)
    {
        $this->paymentMethodRepository = $paymentMethodRepository;
    }

    public function execute()
    {
        return $this->paymentMethodRepository->findAllPaymentMethods();
    }
}