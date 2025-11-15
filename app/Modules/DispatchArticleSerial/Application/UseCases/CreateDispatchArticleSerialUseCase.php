<?php

namespace App\Modules\DispatchArticleSerial\Application\UseCases;

use App\Modules\DispatchArticleSerial\Application\DTOs\DispatchArticleSerialDTO;
use App\Modules\DispatchArticleSerial\Domain\Interfaces\DispatchArticleSerialRepositoryInterface;
use App\Modules\DispatchArticleSerial\Domain\Entities\DispatchArticleSerial;

class CreateDispatchArticleSerialUseCase
{
    private DispatchArticleSerialRepositoryInterface $dispatchArticleSerialRepository;

    public function __construct(DispatchArticleSerialRepositoryInterface $dispatchArticleSerialRepository)
    {
        $this->dispatchArticleSerialRepository = $dispatchArticleSerialRepository;
    }

    public function execute(DispatchArticleSerialDTO $dispatchArticleSerialDTO): ?DispatchArticleSerial
    {
        $dispatchArticleSerial = new DispatchArticleSerial(
            id: 0,
            dispatch_note_id: $dispatchArticleSerialDTO->dispatchNoteId,
            article_id: $dispatchArticleSerialDTO->articleId,
            serial: $dispatchArticleSerialDTO->serial,
            status: $dispatchArticleSerialDTO->status,
            origin_branch_id: $dispatchArticleSerialDTO->originBranchId,  
            destination_branch_id: $dispatchArticleSerialDTO->destinationBranchId,
        );
        return $this->dispatchArticleSerialRepository->save($dispatchArticleSerial);
    }
}
