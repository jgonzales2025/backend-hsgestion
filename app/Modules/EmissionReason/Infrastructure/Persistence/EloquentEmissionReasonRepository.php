<?php

namespace App\Modules\EmissionReason\Infrastructure\Persistence;

use App\Modules\EmissionReason\Domain\Entities\EmissionReason;
use App\Modules\EmissionReason\Domain\Interfaces\EmissionReasonRepositoryInterface;
use App\Modules\EmissionReason\Infrastructure\Models\EloquentEmissionReason;

class EloquentEmissionReasonRepository implements EmissionReasonRepositoryInterface
{

    public function findAll(): array
    {
        $eloquentEmissionReasons = EloquentEmissionReason::where('st_transfer', 0)->get();

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
    public function findById($id):?EmissionReason{
        $eloquentEmissionReason = EloquentEmissionReason::find($id);

        if (!$eloquentEmissionReason) {
             return null;
        }
         return new EmissionReason(
            id:$id,
            description: $eloquentEmissionReason->description,
            status: $eloquentEmissionReason->status
         );
    }
    
    public function findAllForTransferOrders(): array
    {
        $eloquentEmissionReasons = EloquentEmissionReason::where('st_transfer', 1)->get();

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
