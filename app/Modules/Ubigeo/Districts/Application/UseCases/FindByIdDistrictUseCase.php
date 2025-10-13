<?php

namespace App\Modules\Ubigeo\Districts\Application\UseCases;

use App\Modules\Ubigeo\Districts\Domain\Entities\District;
use App\Modules\Ubigeo\Districts\Domain\Interfaces\DistrictRepositoryInterface;

readonly class FindByIdDistrictUseCase
{
    public function __construct(private readonly DistrictRepositoryInterface $districtRepository){}

    public function execute(int $coddep, int $codpro, int $coddis): ?District
    {
        return $this->districtRepository->findById($coddep, $codpro, $coddis);

    }
}
