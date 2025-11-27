<?php

namespace App\Modules\Collections\Application\UseCases;

use App\Modules\Collections\Application\DTOs\BulkCollectionDTO;
use App\Modules\Collections\Domain\Entities\BulkCollection;
use App\Modules\Collections\Domain\Interfaces\CollectionRepositoryInterface;

class StoreBulkCollectionUseCase
{
    public function __construct(
        private readonly CollectionRepositoryInterface $collectionRepository,
    ) {}

    public function execute(BulkCollectionDTO $bulkCollectionDTO, array $data): void
    {
        $collection = new BulkCollection(
            id: 0,
            company_id: $bulkCollectionDTO->company_id,
            customer_id: $bulkCollectionDTO->customer_id,
            payment_method_id: $bulkCollectionDTO->payment_method_id,
            payment_date: $bulkCollectionDTO->payment_date,
            parallel_rate: $bulkCollectionDTO->parallel_rate,
            bank_id: $bulkCollectionDTO->bank_id,
            currency_type_id: $bulkCollectionDTO->currency_type_id,
            operation_date: $bulkCollectionDTO->operation_date,
            operation_number: $bulkCollectionDTO->operation_number,
            advance_id: $bulkCollectionDTO->advance_id
        );

        $this->collectionRepository->saveBulkCollection($collection, $data);
    }
}