<?php

namespace App\Modules\PettyCashReceipt\Application\UseCases;

use App\Modules\PettyCashReceipt\Domain\Interface\PettyCashReceiptRepositoryInterface;

class UpdatePettyCashReceiptStatusUseCase{
    public function __construct(private readonly PettyCashReceiptRepositoryInterface $pettyCashReceiptRepository){}

    public function execute(int $pettyCashReceipt,int $status): void
    {
         $this->pettyCashReceiptRepository->updateStatus($pettyCashReceipt,$status);
    }

}