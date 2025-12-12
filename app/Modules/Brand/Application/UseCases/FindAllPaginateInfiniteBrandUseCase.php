<?php

namespace App\Modules\Brand\Application\UseCases;

use App\Modules\Brand\Domain\Interfaces\BrandRepositoryInterface;

class FindAllPaginateInfiniteBrandUseCase
{
    public function __construct(private readonly BrandRepositoryInterface $brandRepository){}

    public function execute(?string $name)
    {
        return $this->brandRepository->findAllPaginateInfinite($name);
    }
}