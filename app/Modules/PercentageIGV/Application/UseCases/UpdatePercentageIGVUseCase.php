<?php

namespace App\Modules\PercentageIGV\Application\UseCases;

use App\Modules\PercentageIGV\Application\DTOs\PercentageIGVDTO;
use App\Modules\PercentageIGV\Domain\Entities\PercentageIGV;
use App\Modules\PercentageIGV\Domain\Interfaces\PercentageIGVRepositoryInterface;

class UpdatePercentageIGVUseCase
{
    private percentageIGVRepositoryInterface $percentageIGVRepository;

    public function __construct(PercentageIGVRepositoryInterface $percentageIGVRepository)
    {
        $this->percentageIGVRepository = $percentageIGVRepository;
    }

    public function execute(int $id, PercentageIGVDTO $percentageIGVDTO): ?PercentageIGV
    {
        $percentageIGV = new PercentageIGV(
            id: $id,
            date: $percentageIGVDTO->date,
            percentage: $percentageIGVDTO->percentage,
        );

        return $this->percentageIGVRepository->update($percentageIGV);
    }
}
