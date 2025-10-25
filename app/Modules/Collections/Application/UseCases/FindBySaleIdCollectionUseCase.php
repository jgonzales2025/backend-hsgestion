<?php

namespace App\Modules\Collections\Application\UseCases;

use App\Modules\Collections\Domain\Interfaces\CollectionRepositoryInterface;

readonly class FindBySaleIdCollectionUseCase
{
    public function __construct(private readonly CollectionRepositoryInterface $collectionRepository){}

    public function execute(int $saleId): array
    {
        return $this->collectionRepository->findBySaleId($saleId);
    }
}
