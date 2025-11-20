<?php

namespace App\Modules\DispatchArticle\Application\UseCases;

use App\Modules\DispatchArticle\Domain\Interface\DispatchArticleRepositoryInterface;

class FindByDispatchArticleIdUseCase
{
    public function __construct(private DispatchArticleRepositoryInterface $dispatchArticleRepository){}

    public function execute(int $id): ?array
    {
        return $this->dispatchArticleRepository->findByDispatchNoteId($id);
    }
}