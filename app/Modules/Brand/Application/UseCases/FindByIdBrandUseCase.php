<?php

namespace App\Modules\Brand\Application\UseCases;

use App\Modules\Brand\Domain\Interfaces\BrandRepositoryInterface;

class FindByIdBrandUseCase
{
    private brandRepositoryInterface $brandRepository;

    public function __construct(BrandRepositoryInterface $brandRepository)
    {
        $this->brandRepository = $brandRepository;
    }

    public function execute(int $id)
    {
        return $this->brandRepository->findById($id);
    }
}
