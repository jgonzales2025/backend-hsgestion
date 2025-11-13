<?php

namespace App\Modules\Brand\Application\UseCases;

use App\Modules\Brand\Domain\Interfaces\BrandRepositoryInterface;

class UpdateStatusBrand
{
    private BrandRepositoryInterface $brandRepository;

    public function __construct(BrandRepositoryInterface $brandRepository)
    {
        $this->brandRepository = $brandRepository;
    }

    public function execute(int $brandId, int $status): void
    {
        $this->brandRepository->updateStatus($brandId, $status);
    }
}
