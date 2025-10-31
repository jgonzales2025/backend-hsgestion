<?php

namespace App\Modules\Articles\Application\UseCases;

use App\Modules\Articles\Domain\Interfaces\ArticleExporterInterface;
use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;
use Illuminate\Support\Collection;



class ExportArticlesToExcelUseCase
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository,
        private ArticleExporterInterface $excelExporter
    ) {}

    public function execute(int $articleId): string
    {
        $article = $this->articleRepository->findById($articleId);
        
        if (!$article) {
            throw new \Exception('No se encontró el artículo con el ID proporcionado');
        }
        
        return $this->excelExporter->export($article);
    }
}