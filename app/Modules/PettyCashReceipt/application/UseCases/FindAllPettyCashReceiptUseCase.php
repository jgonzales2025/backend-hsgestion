<?php

namespace App\Modules\PettyCashReceipt\Application\UseCases;

use App\Modules\PettyCashReceipt\Domain\Interface\PettyCashReceiptRepositoryInterface;

class FindAllPettyCashReceiptUseCase
{
    public function __construct(private readonly PettyCashReceiptRepositoryInterface $pettyCashReceiptRepository)
    {
    }

    public function execute(?string $filter)
    {
        return $this->pettyCashReceiptRepository->findAll($filter);

    }
}