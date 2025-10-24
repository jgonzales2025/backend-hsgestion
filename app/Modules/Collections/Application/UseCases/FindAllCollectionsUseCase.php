<?php

namespace App\Modules\Collections\Application\UseCases;

use App\Modules\Collections\Domain\Interfaces\CollectionRepositoryInterface;

readonly class FindAllCollectionsUseCase
{
    public function __construct(private readonly CollectionRepositoryInterface $collectionRepository){}

    public function execute(): array
    {
        return $this->collectionRepository->findAll();
    }
}
