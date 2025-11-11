<?php

namespace App\Modules\IngressReason\Infrastructure\Persistence;

use App\Modules\IngressReason\Domain\Entities\IngressReason;
use App\Modules\IngressReason\Domain\Interfaces\IngressReasonRepositoryInterface;
use App\Modules\IngressReason\Infrastructure\Models\EloquentIngressReason;

class EloquentIngressReasonRepository implements IngressReasonRepositoryInterface
{

    public function findAll(): array
    {
        $eloquentIngressReasons = EloquentIngressReason::all();

        if ($eloquentIngressReasons->isEmpty()) {
            return [];
        }

        return $eloquentIngressReasons->map(function ($eloquentIngressReason) {
            return new IngressReason(
                id: $eloquentIngressReason->id,
                description: $eloquentIngressReason->description,
                status: $eloquentIngressReason->status
            );
        })->toArray();
    }

    public function findById(int $id): ?IngressReason
    {
        $eloquentIngressReason = EloquentIngressReason::find($id);

        if (!$eloquentIngressReason) {
            return null;
        }

        return new IngressReason(
            id: $eloquentIngressReason->id,
            description: $eloquentIngressReason->description,
            status: $eloquentIngressReason->status
        );
    }
}
