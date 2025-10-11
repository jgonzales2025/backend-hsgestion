<?php

namespace App\Modules\Provinces\Infrastructure\Persistence;

use App\Modules\Provinces\Domain\Entities\Province;
use App\Modules\Provinces\Domain\Interfaces\ProvinceRepositoryInterface;
use App\Modules\Provinces\Infrastructure\Models\EloquentProvince;

class EloquentProvinceRepository implements ProvinceRepositoryInterface
{

    public function findAll($coddep): array
    {
        $provinces = EloquentProvince::all()->where('coddep', $coddep);

        return $provinces->map(function ($province) {
            return new Province(
                coddep: $province->coddep,
                codpro: $province->codpro,
                nompro: $province->nompro,
            );
        })->toArray();
    }
}
