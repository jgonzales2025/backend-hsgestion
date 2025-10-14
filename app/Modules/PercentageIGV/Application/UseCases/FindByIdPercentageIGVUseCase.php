<?php

namespace App\Modules\PercentageIGV\Application\UseCases;

use App\Modules\PercentageIGV\Domain\Entities\PercentageIGV;
use App\Modules\PercentageIGV\Domain\Interfaces\PercentageIGVRepositoryInterface;

class FindByIdPercentageIGVUseCase
{
    private percentageIGVRepositoryInterface $percentageIGVRepository;

    public function __construct(PercentageIGVRepositoryInterface $percentageIGVRepository)
    {
        $this->percentageIGVRepository = $percentageIGVRepository;
    }

    public function execute(int $id): ?PercentageIGV
    {
        return $this->percentageIGVRepository->findById($id);
    }
}
