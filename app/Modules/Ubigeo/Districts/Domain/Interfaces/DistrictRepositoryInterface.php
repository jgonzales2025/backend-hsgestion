<?php

namespace App\Modules\Ubigeo\Districts\Domain\Interfaces;

use App\Modules\Ubigeo\Districts\Domain\Entities\District;

interface DistrictRepositoryInterface
{
    public function findAll($coddep, $codpro): array;

    public function findById(int $coddep, int $codpro, int $coddis): ?District;
}
