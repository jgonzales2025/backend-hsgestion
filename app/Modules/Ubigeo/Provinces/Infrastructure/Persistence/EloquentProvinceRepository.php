<?php

namespace App\Modules\Ubigeo\Provinces\Infrastructure\Persistence;

use App\Modules\Ubigeo\Provinces\Domain\Entities\Province;
use App\Modules\Ubigeo\Provinces\Domain\Interfaces\ProvinceRepositoryInterface;
use App\Modules\Ubigeo\Provinces\Infrastructure\Models\EloquentProvince;

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
