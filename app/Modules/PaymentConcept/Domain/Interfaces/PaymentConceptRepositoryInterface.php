<?php

namespace App\Modules\PaymentConcept\Domain\Interfaces;

use App\Modules\PaymentConcept\Domain\Entities\PaymentConcept;

interface PaymentConceptRepositoryInterface
{
    public function findAll(?string $description, ?int $status);
    public function findById(int $id): ?PaymentConcept;
    public function create(array $data): void;
    public function update(int $id, array $data): void;
    public function updateStatus(int $id, int $status): void;
}