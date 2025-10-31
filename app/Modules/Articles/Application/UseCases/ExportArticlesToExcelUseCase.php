<?php

namespace App\Modules\Articles\Application\UseCases;

use App\Domain\Article\Ports\ArticleExporterInterface;
use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;

class ExportArticlesToExcelUseCase
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository,
        private ArticleExporterInterface $excelExporter
    ) {}

    public function execute(int $articleIds): string
    {
        $articles = $this->articleRepository->findById($articleIds);
        
        if (!$articles) {
            throw new \Exception('No se encontraron artículos con los IDs proporcionados');
        }
        
        return $this->excelExporter->export($articles);
    }
}