<?php

namespace App\Modules\PaymentConcept\Infrastructure\Persistence;

use App\Modules\PaymentConcept\Domain\Entities\PaymentConcept;
use App\Modules\PaymentConcept\Domain\Interfaces\PaymentConceptRepositoryInterface;
use App\Modules\PaymentConcept\Infrastructure\Model\EloquentPaymentConcept;

class EloquentPaymentConceptRepository implements PaymentConceptRepositoryInterface
{
    public function findAll(?string $description, ?int $status)
    {
        $eloquentPaymentConcepts = EloquentPaymentConcept::when($description, fn($query) => $query->where('description', 'like', '%' . $description . '%'))
            ->when($status !== null, fn($query) => $query->where('status', $status))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $eloquentPaymentConcepts->getCollection()->transform(fn($eloquentPaymentConcept) => new PaymentConcept(
            id: $eloquentPaymentConcept->id,
            description: $eloquentPaymentConcept->description,
            status: $eloquentPaymentConcept->status
        ));

        return $eloquentPaymentConcepts;
    }

    public function findAllInfinity(?string $description, ?int $status)
    {
        return EloquentPaymentConcept::when($description, fn($query) => $query->where('description', 'like', '%' . $description . '%'))
            ->when($status !== null, fn($query) => $query->where('status', $status))
            ->orderBy('id', 'desc')
            ->cursorPaginate(10);
    }

    public function findById(int $id): ?PaymentConcept
    {
        $paymentConcept = EloquentPaymentConcept::find($id);

        if (!$paymentConcept) {
            return null;
        }

        return new PaymentConcept(
            id: $paymentConcept->id,
            description: $paymentConcept->description,
            status: $paymentConcept->status
        );
    }

    public function create(array $data): void
    {
        EloquentPaymentConcept::create($data);
    }

    public function update(int $id, array $data): void
    {
        EloquentPaymentConcept::find($id)->update($data);
    }

    public function updateStatus(int $id, int $status): void
    {
        EloquentPaymentConcept::where('id', $id)->update(['status' => $status]);
    }
}
