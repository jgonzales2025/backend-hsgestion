<?php

namespace App\Modules\Ubigeo\Districts\Infrastructure\Persistence;

use App\Modules\Ubigeo\Districts\Domain\Entities\District;
use App\Modules\Ubigeo\Districts\Domain\Interfaces\DistrictRepositoryInterface;
use App\Modules\Ubigeo\Districts\Infrastructure\Models\EloquentDistrict;

class EloquentDistrictRepository implements DistrictRepositoryInterface
{

    public function findAll($coddep, $codpro): array
    {
        $districts = EloquentDistrict::all()->where('coddep', $coddep)->where('codpro', $codpro);

        return $districts->map(function ($district) {
            return new District(
                coddep: $district->coddep,
                codpro: $district->codpro,
                coddis: $district->coddis,
                nomdis: $district->nomdis,
            );
        })->toArray();
    }
}
