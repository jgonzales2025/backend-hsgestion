<?php

namespace App\Modules\Ubigeo\Provinces\Application\UseCases;

use App\Modules\Ubigeo\Provinces\Domain\Entities\Province;
use App\Modules\Ubigeo\Provinces\Domain\Interfaces\ProvinceRepositoryInterface;

readonly class FindByIdProvinceUseCase
{
    public function __construct(private readonly ProvinceRepositoryInterface $provinceRepository){}

    public function execute(int $coddep, int $codpro): ?Province
    {
        return $this->provinceRepository->findById($coddep, $codpro);
    }
}
