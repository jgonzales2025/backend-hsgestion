<?php

namespace App\Modules\PaymentConcept\Application\UseCases;

use App\Modules\PaymentConcept\Domain\Interfaces\PaymentConceptRepositoryInterface;

class UpdateStatusPaymentConceptUseCase
{
    public function __construct(
        private PaymentConceptRepositoryInterface $paymentConceptRepository
    ) {}

    public function execute(int $id, int $status): void
    {
        $this->paymentConceptRepository->updateStatus($id, $status);
    }
}