<?php

namespace App\Modules\PercentageIGV\Application\UseCases;

use App\Modules\PercentageIGV\Application\DTOs\PercentageIGVDTO;
use App\Modules\PercentageIGV\Domain\Entities\PercentageIGV;
use App\Modules\PercentageIGV\Domain\Interfaces\PercentageIGVRepositoryInterface;

class CreatePercentageIGVUseCase
{
    private percentageIgvRepositoryInterface $percentageIGVRepository;

    public function __construct(percentageIgvRepositoryInterface $percentageIGVRepository)
    {
        $this->percentageIGVRepository = $percentageIGVRepository;
    }

    public function execute(PercentageIGVDTO $percentageIGVDTO): PercentageIGV
    {
        $percentageIGV = new PercentageIGV(
            id: 0,
            date: $percentageIGVDTO->date,
            percentage: $percentageIGVDTO->percentage
        );

        return $this->percentageIGVRepository->save($percentageIGV);
    }
}
