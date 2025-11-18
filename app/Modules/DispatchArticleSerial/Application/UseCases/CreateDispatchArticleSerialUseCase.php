<?php

namespace App\Modules\DispatchArticleSerial\Application\UseCases;

use App\Modules\Articles\Application\UseCases\FindByIdArticleUseCase;
use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;
use App\Modules\Branch\Application\UseCases\FindByIdBranchUseCase;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\DispatchArticleSerial\Application\DTOs\DispatchArticleSerialDTO;
use App\Modules\DispatchArticleSerial\Domain\Interfaces\DispatchArticleSerialRepositoryInterface;
use App\Modules\DispatchArticleSerial\Domain\Entities\DispatchArticleSerial;
use App\Modules\DispatchNotes\Application\UseCases\FindByIdDispatchNoteUseCase;
use App\Modules\DispatchNotes\Domain\Interfaces\DispatchNotesRepositoryInterface;

class CreateDispatchArticleSerialUseCase
{
    public function __construct(
        private DispatchArticleSerialRepositoryInterface $dispatchArticleSerialRepository, 
        private ArticleRepositoryInterface $articleRepository,
        private BranchRepositoryInterface $branchRepository,
        private DispatchNotesRepositoryInterface $dispatchNoteRepository,
        )
    {}

    public function execute(DispatchArticleSerialDTO $dispatchArticleSerialDTO): ?DispatchArticleSerial
    {

        $articleUseCase = new FindByIdArticleUseCase($this->articleRepository);
        $article = $articleUseCase->execute($dispatchArticleSerialDTO->articleId);

        $dispatchUseCase = new FindByIdDispatchNoteUseCase($this->dispatchNoteRepository);
        $dispatchNote = $dispatchUseCase->execute($dispatchArticleSerialDTO->dispatchNoteId);

        $dispatchArticleSerial = new DispatchArticleSerial(
            id: 0,
            dispatch_note: $dispatchNote,
            article: $article,
            serial: $dispatchArticleSerialDTO->serial,
            emission_reasons_id: $dispatchArticleSerialDTO->emissionReasonsId,
            status: $dispatchArticleSerialDTO->status,
            origin_branch: $dispatchArticleSerialDTO->originBranch,
            destination_branch: $dispatchArticleSerialDTO->destinationBranch,
        );
        return $this->dispatchArticleSerialRepository->save($dispatchArticleSerial);
    }
}
