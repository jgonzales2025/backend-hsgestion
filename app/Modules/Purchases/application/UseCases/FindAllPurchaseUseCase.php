<?php

namespace App\Modules\Purchases\Application\UseCases;

use App\Modules\Purchases\Domain\Interface\PurchaseRepositoryInterface;

class FindAllPurchaseUseCase
{
    public function __construct(private readonly PurchaseRepositoryInterface $purchaseRepository)
    {
    }
    public function execute(?string $description, ?string $num_doc, ?int $id_proveedr)
    {
        return $this->purchaseRepository->findAll($description, $num_doc, $id_proveedr);
    }
}