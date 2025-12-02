<?php

namespace App\Modules\Detraction\Infrastructure\Persistence;

use App\Modules\Detraction\Domain\Entities\Detraction;
use App\Modules\Detraction\Domain\Interface\DetractionRepositoryInterface;
use App\Modules\Detraction\Infrastructure\Models\EloquentDetraction;

class EloquentDetractionRepository implements DetractionRepositoryInterface
{
    public function findAll(): array
    {
        $detractions = EloquentDetraction::all()->toArray();

        return array_map(function ($detraction) {
            return new Detraction(
                id: $detraction['id'],
                cod_sunat: $detraction['cod_sunat'],
                description: $detraction['description'],
                percentage: $detraction['percentage'],
            );
        }, $detractions);
    }
}