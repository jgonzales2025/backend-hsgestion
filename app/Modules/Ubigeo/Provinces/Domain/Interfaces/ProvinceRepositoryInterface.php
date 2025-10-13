<?php

namespace App\Modules\Ubigeo\Provinces\Domain\Interfaces;

use App\Modules\Ubigeo\Provinces\Domain\Entities\Province;

interface ProvinceRepositoryInterface
{
    public function findAll($coddep): array;
    public function findById(int $copdep, int $codpro): ?Province;
}
