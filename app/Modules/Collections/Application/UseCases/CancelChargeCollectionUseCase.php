<?php

namespace App\Modules\Collections\Application\UseCases;

use App\Modules\Collections\Domain\Interfaces\CollectionRepositoryInterface;

readonly class CancelChargeCollectionUseCase
{
    public function __construct(private readonly CollectionRepositoryInterface $collectionRepository){}

    public function execute(int $id): void
    {
        $this->collectionRepository->cancelCharge($id);
    }
}
