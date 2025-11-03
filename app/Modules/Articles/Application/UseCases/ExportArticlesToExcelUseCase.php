<?php

namespace App\Modules\Articles\Application\UseCases;

use App\Modules\Articles\Domain\Interfaces\ArticleExporterInterface;
use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;
use App\Modules\Articles\Infrastructure\Models\EloquentArticle;
use App\Modules\UserAssignment\Domain\Interfaces\UserAssignmentRepositoryInterface;
use Illuminate\Support\Collection;



class ExportArticlesToExcelUseCase
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository,
        private ArticleExporterInterface $excelExporter,
       private readonly UserAssignmentRepositoryInterface $userAssignmentRepository
    ) {}

public function execute(): string
{
    $articles = $this->articleRepository->findAllExcel('');

    if ($articles->isEmpty()) {
        throw new \Exception('No se encontraron artículos para exportar');
    }

    return $this->excelExporter->export($articles);
}

}