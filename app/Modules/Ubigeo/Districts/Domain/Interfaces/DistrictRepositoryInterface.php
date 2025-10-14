<?php

namespace App\Modules\Ubigeo\Districts\Domain\Interfaces;

interface DistrictRepositoryInterface
{
    public function findAll($coddep, $codpro): array;
}
