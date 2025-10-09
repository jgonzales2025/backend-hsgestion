<?php

namespace App\Modules\Brand\Application\UseCases;

use App\Modules\Brand\Application\DTOs\BrandDTO;
use App\Modules\Brand\Domain\Entities\Brand;
use App\Modules\Brand\Domain\Interfaces\BrandRepositoryInterface;

class CreateBrandUseCase
{
    private brandRepositoryInterface $brandRepository;

    public function __construct(BrandRepositoryInterface $brandRepository)
    {
        $this->brandRepository = $brandRepository;
    }

    public function execute(BrandDTO $brandDTO)
    {
        $brand = new Brand(
            id: 0,
            name: $brandDTO->name,
            status: $brandDTO->status,
        );

        return $this->brandRepository->save($brand);
    }
}
