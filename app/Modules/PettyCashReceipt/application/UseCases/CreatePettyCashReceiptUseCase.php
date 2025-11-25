<?php

namespace App\Modules\PettyCashReceipt\Application\UseCases;

use App\Modules\Branch\Application\UseCases\FindByIdBranchUseCase;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\CurrencyType\Application\UseCases\FindByIdCurrencyTypeUseCase;
use App\Modules\CurrencyType\Domain\Interfaces\CurrencyTypeRepositoryInterface;
use App\Modules\DocumentType\Application\UseCases\FindByIdDocumentTypeUseCase;
use App\Modules\DocumentType\Domain\Interfaces\DocumentTypeRepositoryInterface;
use App\Modules\PettyCashMotive\Application\UseCases\FindByIdPettyCashMotive;
use App\Modules\PettyCashMotive\Domain\Interface\PettyCashMotiveInterfaceRepository;
use App\Modules\PettyCashReceipt\Application\DTOS\PettyCashReceiptDTO;
use App\Modules\PettyCashReceipt\Domain\Entities\PettyCashReceipt;
use App\Modules\PettyCashReceipt\Domain\Interface\PettyCashReceiptRepositoryInterface;
use App\Services\DocumentNumberGeneratorService;

class CreatePettyCashReceiptUseCase
{
    public function __construct(
        private readonly PettyCashReceiptRepositoryInterface $pettyCashReceiptRepository,
        private readonly BranchRepositoryInterface $branchRepository,
        private readonly CurrencyTypeRepositoryInterface $currencyTypeRepository,
        private readonly DocumentNumberGeneratorService $documentNumberGeneratorService,
        private readonly DocumentTypeRepositoryInterface $documentTypeRepository,
        private readonly PettyCashMotiveInterfaceRepository $pettyCashMotiveRepository
    ) {
    }

    public function execute(PettyCashReceiptDTO $pettyCashReceipt): ?PettyCashReceipt
    {
        $lastDocumentNumber = $this->pettyCashReceiptRepository->getLastDocumentNumber($pettyCashReceipt->series);
        $pettyCashReceipt->correlative = $this->documentNumberGeneratorService->generateNextNumber($lastDocumentNumber);


        if ($pettyCashReceipt->branch_id != null) {
            # code...
            $findByIdBranchUseCase = new FindByIdBranchUseCase($this->branchRepository);
            $branch = $findByIdBranchUseCase->execute($pettyCashReceipt->branch_id);
        } else {
            $branch = null;
        }
        if ($pettyCashReceipt->currency_type != null) {
            # code...
            $findByIdCurrencyTypeUseCase = new FindByIdCurrencyTypeUseCase($this->currencyTypeRepository);
            $currency = $findByIdCurrencyTypeUseCase->execute($pettyCashReceipt->currency_type);
        } else {
            $currency = null;

        }
        if ($pettyCashReceipt->document_type_id != null) {
            $documentypeUseCase = new FindByIdDocumentTypeUseCase($this->documentTypeRepository);

            $documentType = $documentypeUseCase->execute($pettyCashReceipt->document_type_id);
        } else {
            $documentType = null;
        }
        $tPettyCashMotiveUseCASE = new FindByIdPettyCashMotive($this->pettyCashMotiveRepository);
        $reason_code = $tPettyCashMotiveUseCASE->execute($pettyCashReceipt->reason_code_id);
    
        $pettyCashReceipts = new PettyCashReceipt(
            id: null,
            company_id: $pettyCashReceipt->company_id,
            document_type: $documentType,
            series: $pettyCashReceipt->series,
            correlative: $pettyCashReceipt->correlative,
            date: $pettyCashReceipt->date,
            delivered_to: $pettyCashReceipt->delivered_to,
            reason_code: $reason_code,
            currency: $currency,
            amount: $pettyCashReceipt->amount,
            observation: $pettyCashReceipt->observation,
            status: $pettyCashReceipt->status,
            branch: $branch
        );
        return $this->pettyCashReceiptRepository->save($pettyCashReceipts);


    }
}