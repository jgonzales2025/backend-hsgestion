<?php

namespace App\Modules\PettyCashMotive\Application\UseCases;

use App\Modules\DocumentType\Application\UseCases\FindByIdDocumentTypeUseCase;
use App\Modules\DocumentType\Domain\Interfaces\DocumentTypeRepositoryInterface;
use App\Modules\PettyCashMotive\Application\DTOS\PettyCashMotiveDTO;
use App\Modules\PettyCashMotive\Domain\Entities\PettyCashMotive;
use App\Modules\PettyCashMotive\Domain\Interface\PettyCashMotiveInterfaceRepository; 

class CreatePettyCashMotive
{
    public function __construct(
        private readonly PettyCashMotiveInterfaceRepository $pettyCashMotiveInterfaceRepository,
        private readonly DocumentTypeRepositoryInterface $documentTypeInterfaceRepository,)
    {
    }

    public function execute(PettyCashMotiveDTO $pettyCashMotiveDTO): ?PettyCashMotive
    {  

        $documentTypeUseCase = new FindByIdDocumentTypeUseCase($this->documentTypeInterfaceRepository);
        $documentType = $documentTypeUseCase->execute($pettyCashMotiveDTO->receipt_type);

        $pettyCashMotive = new PettyCashMotive(
            id: null,
            company_id: $pettyCashMotiveDTO->company_id,
            description: $pettyCashMotiveDTO->description,
            receipt_type: $documentType,
            user_id: $pettyCashMotiveDTO->user_id,
            status: $pettyCashMotiveDTO->status,
        );
        return $this->pettyCashMotiveInterfaceRepository->save($pettyCashMotive);

    }
}