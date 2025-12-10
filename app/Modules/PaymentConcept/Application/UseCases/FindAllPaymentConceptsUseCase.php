<?php

namespace App\Modules\PaymentConcept\Application\UseCases;

use App\Modules\PaymentConcept\Domain\Interfaces\PaymentConceptRepositoryInterface;

class FindAllPaymentConceptsUseCase
{
    public function __construct(
        private PaymentConceptRepositoryInterface $paymentConceptRepository
    ) {}

    public function execute(?string $description, ?int $status)
    {
        return $this->paymentConceptRepository->findAll($description, $status);
    }
}