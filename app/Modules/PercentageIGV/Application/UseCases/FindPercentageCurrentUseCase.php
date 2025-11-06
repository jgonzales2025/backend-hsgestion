<?php

namespace App\Modules\PercentageIGV\Application\UseCases;

use App\Modules\PercentageIGV\Domain\Entities\PercentageIGV;
use App\Modules\PercentageIGV\Domain\Interfaces\PercentageIGVRepositoryInterface;

readonly class FindPercentageCurrentUseCase
{
    public function __construct(private readonly PercentageIGVRepositoryInterface $percentageIGVRepository){}

    public function execute(): ?PercentageIGV
    {
        return $this->percentageIGVRepository->findPercentageCurrent();
    }
}
