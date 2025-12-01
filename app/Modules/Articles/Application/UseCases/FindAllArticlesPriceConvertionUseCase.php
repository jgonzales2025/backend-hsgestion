<?php

namespace App\Modules\Articles\Application\UseCases;

use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;

readonly class FindAllArticlesPriceConvertionUseCase
{
    public function __construct(private readonly ArticleRepositoryInterface $articleRepository){}

    public function execute(string $date, ?string $description, ?int $articleId, ?int $branchId)
    {
        return $this->articleRepository->findAllArticlePriceConvertion($date, $description, $articleId, $branchId);
    }
}
