<?php

namespace App\Modules\Collections\Application\UseCases;

use App\Modules\Collections\Application\DTOs\CollectionCreditNoteDTO;
use App\Modules\Collections\Domain\Entities\Collection;
use App\Modules\Collections\Domain\Interfaces\CollectionRepositoryInterface;
use App\Modules\PaymentMethod\Application\UseCases\FindByIdPaymentMethodUseCase;
use App\Modules\PaymentMethod\Domain\Interfaces\PaymentMethodRepositoryInterface;
use App\Modules\Sale\Application\UseCases\FindByIdSaleUseCase;
use App\Modules\Sale\Domain\Interfaces\SaleRepositoryInterface;

readonly class CreateCollectionCreditNoteUseCase
{
    public function __construct(
        private readonly CollectionRepositoryInterface $collectionRepository,
        private readonly PaymentMethodRepositoryInterface $paymentMethodRepository,
        private readonly SaleRepositoryInterface $saleRepository,
    ){}

    public function execute(CollectionCreditNoteDTO $collectionCreditNoteDTO): ?Collection
    {
        $paymentMethodUseCase = new FindByIdPaymentMethodUseCase($this->paymentMethodRepository);
        $paymentMethod = $paymentMethodUseCase->execute($collectionCreditNoteDTO->payment_method_id);

        $saleUseCase = new FindByIdSaleUseCase($this->saleRepository);
        $sale = $saleUseCase->execute($collectionCreditNoteDTO->sale_id);

        $collection = new Collection(
            id: 0,
            company_id: $collectionCreditNoteDTO->company_id,
            sale_id: $collectionCreditNoteDTO->sale_id,
            sale_document_type_id: $collectionCreditNoteDTO->sale_document_type_id,
            sale_serie: $collectionCreditNoteDTO->sale_serie,
            sale_correlative: $collectionCreditNoteDTO->sale_correlative,
            payment_method: $paymentMethod,
            payment_date: $collectionCreditNoteDTO->payment_date,
            currency_type_id: $sale->getCurrencyType()->getId(),
            parallel_rate: $sale->getParallelRate(),
            amount: $collectionCreditNoteDTO->amount,
            change: null,
            digital_wallet_id: null,
            bank_id: null,
            operation_date: null,
            operation_number: null,
            lote_number: null,
            for_digits: null,
            credit_document_type_id: $collectionCreditNoteDTO->credit_document_type_id,
            credit_serie: $collectionCreditNoteDTO->credit_serie,
            credit_correlative: $collectionCreditNoteDTO->credit_correlative,
        );

        return $this->collectionRepository->saveCollectionCreditNote($collection);
    }
}
