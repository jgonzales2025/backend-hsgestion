<?php

namespace App\Modules\Warranty\Application\UseCases;

use App\Modules\Warranty\Domain\Interfaces\WarrantyRepositoryInterface;

class GetLastDocumentNumberUseCase
{
    public function __construct(
        private readonly WarrantyRepositoryInterface $warrantyRepository
    ){}

    public function __invoke(string $serie): ?string
    {
        return $this->warrantyRepository->getLastDocumentNumber($serie);
    }
}