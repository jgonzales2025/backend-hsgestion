<?php

namespace App\Modules\PettyCashReceipt\Application\UseCases;

use App\Modules\PettyCashReceipt\Application\DTOS\PettyCashReceiptDTO;
use App\Modules\PettyCashReceipt\Domain\Entities\PettyCashReceipt;
use App\Modules\PettyCashReceipt\Domain\Interface\PettyCashReceiptRepositoryInterface;

class CreatePettyCashReceiptUseCase{
    public function __construct(private readonly PettyCashReceiptRepositoryInterface $pettyCashReceiptRepository){}
   
    public function execute(PettyCashReceiptDTO $pettyCashReceipt):?PettyCashReceipt{
        $pettyCashReceipts = new PettyCashReceipt(
            id:null,
            company: $pettyCashReceipt->company,
            document_type: $pettyCashReceipt->document_type,
            series: $pettyCashReceipt->series,
            correlative: $pettyCashReceipt->correlative,
            date: $pettyCashReceipt->date,
            delivered_to: $pettyCashReceipt->delivered_to,
            reason_code: $pettyCashReceipt->reason_code,
            currency_type: $pettyCashReceipt->currency_type,
            amount: $pettyCashReceipt->amount,
            observation: $pettyCashReceipt->observation,
            status: $pettyCashReceipt->status,
            created_by: $pettyCashReceipt->created_by,
            created_at_manual: $pettyCashReceipt->created_at_manual,
            updated_by: $pettyCashReceipt->updated_by,
            updated_at_manual: $pettyCashReceipt->updated_at_manual
        );
        return $this->pettyCashReceiptRepository->save($pettyCashReceipts);
    
        
    }
}