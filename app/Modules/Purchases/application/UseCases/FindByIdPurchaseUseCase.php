<?php
namespace App\Modules\Purchases\Application\UseCases;

use App\Modules\Purchases\Domain\Entities\Purchase;
use App\Modules\Purchases\Domain\Interface\PurchaseRepositoryInterface;

class FindByIdPurchaseUseCase
{
    public function __construct(private readonly PurchaseRepositoryInterface $purchaseRepository)
    {
    }
    public function execute(int $id): ?Purchase
    {
        return $this->purchaseRepository->findById($id);

    }
}