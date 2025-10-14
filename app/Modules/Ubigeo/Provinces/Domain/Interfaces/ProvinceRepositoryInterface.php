<?php

namespace App\Modules\Ubigeo\Provinces\Domain\Interfaces;

interface ProvinceRepositoryInterface
{
    public function findAll($coddep): array;
}
