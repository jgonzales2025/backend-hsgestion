<?php

namespace App\Modules\Collections\Application\UseCases;

use App\Modules\Collections\Domain\Entities\Collection;
use App\Modules\Collections\Domain\Interfaces\CollectionRepositoryInterface;

readonly class FindByIdCollectionUseCase
{
    public function __construct(private readonly CollectionRepositoryInterface $collectionRepository){}

    public function execute(int $id): ?Collection
    {
        return $this->collectionRepository->findById($id);
    }
}
