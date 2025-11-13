<?php

namespace App\Modules\PettyCashReceipt\Application\UseCases;

use App\Modules\PettyCashReceipt\Domain\Interface\PettyCashReceiptRepositoryInterface;

class FindAllPettyCashReceiptUseCase
{
    public function __construct(private readonly PettyCashReceiptRepositoryInterface $pettyCashReceiptRepository)
    {
    }

    public function execute()
    {
        return $this->pettyCashReceiptRepository->findAll();

    }
}