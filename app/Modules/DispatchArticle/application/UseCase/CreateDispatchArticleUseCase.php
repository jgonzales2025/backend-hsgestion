<?php

namespace App\Modules\DispatchArticle\Application\UseCase;

use App\Modules\DispatchArticle\Application\DTOS\DispatchArticleDTO;
use App\Modules\DispatchArticle\Domain\Entities\DispatchArticle;
use App\Modules\DispatchArticle\Domain\Interface\DispatchArticleRepositoryInterface;
use App\Modules\DispatchNotes\Domain\Interfaces\DispatchNotesRepositoryInterface;

class CreateDispatchArticleUseCase
{
    public function __construct(private readonly DispatchArticleRepositoryInterface $dispatchArticlesRepositoryInterface)
    {
    }

    public function execute(DispatchArticleDTO $dispatchArticle)
    {
        $dispatchArticle = new DispatchArticle(
            id: null,
            dispatch_id: $dispatchArticle->dispatch_id,
            article_id: $dispatchArticle->article_id,
            quantity: $dispatchArticle->quantity,
            weight: $dispatchArticle->weight,
            saldo: $dispatchArticle->saldo,
            name: $dispatchArticle->name,
            subtotal_weight:$dispatchArticle->subtotal_weight
        );

        return $this->dispatchArticlesRepositoryInterface->save($dispatchArticle);
    }
}