<?php

namespace App\Modules\PaymentConcept\Application\UseCases;

use App\Modules\PaymentConcept\Domain\Interfaces\PaymentConceptRepositoryInterface;

class CreatePaymentConceptUseCase
{
    public function __construct(
        private PaymentConceptRepositoryInterface $paymentConceptRepository
    ) {}

    public function execute(array $data): void
    {
        $this->paymentConceptRepository->create($data);
    }
}