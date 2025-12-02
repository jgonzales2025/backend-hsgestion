<?php

namespace App\Modules\Withholding\Infrastructure\Persistence;

use App\Modules\Withholding\Domain\Entities\Withholding;
use App\Modules\Withholding\Domain\Interface\WithholdingRepositoryInterface;
use App\Modules\Withholding\Infrastructure\Models\EloquentWithholding;

class EloquentWithholdingRepository implements WithholdingRepositoryInterface
{
    public function findByDate(string $date): ?Withholding
    {
        $eloquentWithholding = EloquentWithholding::where('date', '<=', $date)
            ->orderBy('date', 'desc')
            ->first();

        if (!$eloquentWithholding) {
            return null;
        }

        return new Withholding(
            $eloquentWithholding->id,
            $eloquentWithholding->date,
            $eloquentWithholding->percentage
        );
    }
}