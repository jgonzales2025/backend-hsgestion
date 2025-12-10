<?php

namespace App\Modules\PaymentConcept\Application\UseCases;

use App\Modules\PaymentConcept\Domain\Interfaces\PaymentConceptRepositoryInterface;

class UpdatePaymentConceptUseCase
{
    public function __construct(
        private PaymentConceptRepositoryInterface $paymentConceptRepository
    ) {}

    public function execute(int $id, array $data): void
    {
        $this->paymentConceptRepository->update($id, $data);
    }
}