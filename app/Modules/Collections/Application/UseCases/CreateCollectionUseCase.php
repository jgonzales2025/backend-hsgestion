<?php

namespace App\Modules\Collections\Application\UseCases;

use App\Modules\Collections\Application\DTOs\CollectionDTO;
use App\Modules\Collections\Domain\Entities\Collection;
use App\Modules\Collections\Domain\Interfaces\CollectionRepositoryInterface;
use App\Modules\PaymentMethod\Application\UseCases\FindByIdPaymentMethodUseCase;
use App\Modules\PaymentMethod\Domain\Interfaces\PaymentMethodRepositoryInterface;

readonly class CreateCollectionUseCase
{
    public function __construct(
        private readonly CollectionRepositoryInterface $collectionRepository,
        private readonly PaymentMethodRepositoryInterface $paymentMethodRepository,
    ){}

    public function execute(CollectionDTO $collectionDTO): ?Collection
    {
        $paymentMethodUseCase = new FindByIdPaymentMethodUseCase($this->paymentMethodRepository);
        $paymentMethod = $paymentMethodUseCase->execute($collectionDTO->payment_method_id);

        $collection = new Collection(
            id: 0,
            company_id: $collectionDTO->company_id,
            sale_id: $collectionDTO->sale_id,
            sale_document_type_id: $collectionDTO->sale_document_type_id,
            sale_serie: $collectionDTO->sale_serie,
            sale_correlative: $collectionDTO->sale_correlative,
            payment_method: $paymentMethod,
            payment_date: $collectionDTO->payment_date,
            currency_type_id: $collectionDTO->currency_type_id,
            parallel_rate: $collectionDTO->parallel_rate,
            amount: $collectionDTO->amount,
            change: $collectionDTO->change,
            digital_wallet_id: $collectionDTO->digital_wallet_id,
            bank_id: $collectionDTO->bank_id,
            operation_date: $collectionDTO->operation_date,
            operation_number: $collectionDTO->operation_number,
            lote_number: $collectionDTO->lote_number,
            for_digits: $collectionDTO->for_digits
        );

        return $this->collectionRepository->save($collection);
    }
}
