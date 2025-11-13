<?php
namespace App\Modules\Articles\Application\UseCases;

use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;

class RequiredSerialUseCase
{
    private $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function execute(int $articleId): bool
    {
        return $this->articleRepository->requiredSerial($articleId);
    }
}
