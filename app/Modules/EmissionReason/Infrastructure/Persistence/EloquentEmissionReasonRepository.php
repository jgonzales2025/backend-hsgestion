<?php

namespace App\Modules\EmissionReason\Infrastructure\Persistence;

use App\Modules\EmissionReason\Domain\Entities\EmissionReason;
use App\Modules\EmissionReason\Domain\Interfaces\EmissionReasonRepositoryInterface;
use App\Modules\EmissionReason\Infrastructure\Models\EloquentEmissionReason;

class EloquentEmissionReasonRepository implements EmissionReasonRepositoryInterface
{

    public function findAll(): array
    {
        $eloquentEmissionReasons = EloquentEmissionReason::all();

        if ($eloquentEmissionReasons->isEmpty()) {
            return [];
        }

        return $eloquentEmissionReasons->map(function ($eloquentEmissionReason) {
            return new EmissionReason(
                id: $eloquentEmissionReason->id,
                description: $eloquentEmissionReason->description,
                status: $eloquentEmissionReason->status
            );
        })->toArray();
    }
}
