<?php

namespace App\Modules\Ubigeo\Districts\Application\UseCases;

use App\Modules\Ubigeo\Districts\Domain\Interfaces\DistrictRepositoryInterface;

readonly class FindAllDistrictsUseCases
{
    public function __construct(private readonly DistrictRepositoryInterface $districtRepository){}

    public function execute($coddep, $codpro): array
    {
        return $this->districtRepository->findAll($coddep, $codpro);
    }
}
