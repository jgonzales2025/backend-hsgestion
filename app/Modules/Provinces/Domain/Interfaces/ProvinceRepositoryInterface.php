<?php

namespace App\Modules\Provinces\Domain\Interfaces;

interface ProvinceRepositoryInterface
{
    public function findAll($coddep): array;
}
