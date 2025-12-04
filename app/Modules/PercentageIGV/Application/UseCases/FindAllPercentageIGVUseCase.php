<?php

namespace App\Modules\PercentageIGV\Application\UseCases;

use App\Modules\PercentageIGV\Domain\Interfaces\PercentageIGVRepositoryInterface;

class FindAllPercentageIGVUseCase
{
    private percentageIGVRepositoryInterface $percentageIGVRepository;

    public function __construct(PercentageIGVRepositoryInterface $percentageIGVRepository)
    {
        $this->percentageIGVRepository = $percentageIGVRepository;
    }

    public function execute()
    {
        return $this->percentageIGVRepository->findAll();
    }
}
