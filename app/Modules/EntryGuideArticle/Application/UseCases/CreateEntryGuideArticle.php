<?php

namespace App\Modules\EntryGuideArticle\Application\UseCases;

use App\Modules\Articles\Application\UseCases\FindByIdArticleUseCase;
use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;
use App\Modules\EntryGuideArticle\Domain\Entities\EntryGuideArticle;
use App\Modules\EntryGuideArticle\Domain\Interface\EntryGuideArticleRepositoryInterface;
use App\Modules\EntryGuideArticle\Application\DTOS\EntryGuideArticleDTO;

class CreateEntryGuideArticle
{

    public function __construct(
        private readonly EntryGuideArticleRepositoryInterface $entryGuideArticleRepositoryInterface,
        private readonly ArticleRepositoryInterface $articleRepositoryInterface,
    ) {}

    public function execute(EntryGuideArticleDTO $entryGuideArticleDTO): ?EntryGuideArticle
    {

        $articleUseCase = new FindByIdArticleUseCase($this->articleRepositoryInterface);
        $article = $articleUseCase->execute($entryGuideArticleDTO->article_id);

        $entryGuideArticle = new EntryGuideArticle(
            id: null,
            entry_guide_id: $entryGuideArticleDTO->entry_guide_id,
            article: $article,
            description: $entryGuideArticleDTO->description,
            quantity: $entryGuideArticleDTO->quantity,
            saldo: $entryGuideArticleDTO->saldo,
        );

        return $this->entryGuideArticleRepositoryInterface->save($entryGuideArticle);
    }
}
