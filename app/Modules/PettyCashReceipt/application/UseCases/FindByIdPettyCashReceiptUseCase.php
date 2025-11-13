<?php

namespace App\Modules\PettyCashReceipt\Application\UseCases;

use App\Modules\PettyCashReceipt\Domain\Entities\PettyCashReceipt;
use App\Modules\PettyCashReceipt\Domain\Interface\PettyCashReceiptRepositoryInterface;

class FindByIdPettyCashReceiptUseCase
{
    public function __construct(private readonly PettyCashReceiptRepositoryInterface $pettyCashReceiptRepository)
    {
    }

    public function execute(int $id): ?PettyCashReceipt
    {
        return $this->pettyCashReceiptRepository->findById($id);
    }

}