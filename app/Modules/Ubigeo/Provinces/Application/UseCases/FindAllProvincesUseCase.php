<?php

namespace App\Modules\Ubigeo\Provinces\Application\UseCases;

use App\Modules\Ubigeo\Provinces\Domain\Interfaces\ProvinceRepositoryInterface;

readonly class FindAllProvincesUseCase
{
    public function __construct(private readonly ProvinceRepositoryInterface $provinceRepository){}

    public function execute($coddep) : array
    {
        return $this->provinceRepository->findAll($coddep);
    }
}
