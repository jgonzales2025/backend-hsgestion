<?php

namespace App\Modules\Brand\Application\UseCases;

use App\Modules\Brand\Application\DTOs\BrandDTO;
use App\Modules\Brand\Domain\Entities\Brand;
use App\Modules\Brand\Domain\Interfaces\BrandRepositoryInterface;

class UpdateBrandUseCase
{
    private brandRepositoryInterface $brandRepository;

    public function __construct(brandRepositoryInterface $brandRepository)
    {
        $this->brandRepository = $brandRepository;
    }

    public function execute(int $id, BrandDTO $brandDTO): ?Brand
    {
        $brand = new Brand(
            id: $id,
            name: $brandDTO->name
        );

       return $this->brandRepository->update($brand);
    }
}
