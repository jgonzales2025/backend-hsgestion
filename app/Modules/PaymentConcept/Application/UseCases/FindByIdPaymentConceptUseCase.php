<?php

namespace App\Modules\PaymentConcept\Application\UseCases;

use App\Modules\PaymentConcept\Domain\Entities\PaymentConcept;
use App\Modules\PaymentConcept\Domain\Interfaces\PaymentConceptRepositoryInterface;

class FindByIdPaymentConceptUseCase
{
    public function __construct(
        private PaymentConceptRepositoryInterface $paymentConceptRepository
    ) {}

    public function execute(int $id): ?PaymentConcept
    {
        return $this->paymentConceptRepository->findById($id);
    }
}